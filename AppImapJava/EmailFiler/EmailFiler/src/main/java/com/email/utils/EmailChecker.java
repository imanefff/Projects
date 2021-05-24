package com.email.utils;

import java.security.GeneralSecurityException;
import java.util.List;
import java.util.Map;

import javax.mail.Flags;
import javax.mail.Folder;
import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.Session;
import javax.mail.Store;
import javax.mail.Transport;
import javax.mail.internet.MimeMessage;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.email.EmailException;
import com.email.domain.Data;
import com.sun.mail.imap.AppendUID;
import com.sun.mail.imap.IMAPFolder;

import lombok.NonNull;

public class EmailChecker {

	private static final Logger logger = LoggerFactory.getLogger(EmailParser.class);
	private static final int CONNECTION_TIMEOUT = 50000;

	private final Data data;
	private final Integer mode;
	private final Integer messagesChunk;
	private final String keyword;
	private final boolean setFlags;
	private final boolean replyMessage;
	private final boolean archiveMessages;
	private final String replyMessageText;
	private final boolean onlyProxy;
	private final boolean useHttp;

	public EmailChecker(@NonNull Data data, @NonNull Integer mode, Integer messagesChunk, String keyword,
			boolean setFlags, boolean replyMessage, boolean archiveMessages, String replyMessageText, boolean onlyProxy,
			boolean useHttp) {
		this.data = data;
		this.mode = mode;
		this.messagesChunk = messagesChunk;
		this.keyword = keyword == null ? null : keyword.toLowerCase();
		this.setFlags = setFlags;
		this.replyMessage = replyMessage;
		this.archiveMessages = archiveMessages;
		this.replyMessageText = replyMessageText;
		this.onlyProxy = onlyProxy;
		this.useHttp = useHttp;
	}

	public void checkEmail() throws EmailException {
		logger.info("Start check email " + data + " keyword = " + keyword);
		int proxyMode = 2;
		boolean imapConnected = true;

		/************* GET IMAP SESSION WITH SOCKS PROXY MODE *********/
		Session imapSession = null;
		try {
			imapSession = EmailUtils.getImapSession(logger, proxyMode, data.getProxy(), data.getProxyPort(),
					data.getProxyUser(), data.getProxyPassword(), CONNECTION_TIMEOUT > 0 ? CONNECTION_TIMEOUT : null,
					false);
		} catch (GeneralSecurityException e) {
			logger.error("Can't create ssl factory", e);
			throw new EmailException("General Exception");
		}

		/************* GET IMAP STORE WITH SOCKS PROXY MODE **********/
		Store imapStore = null;
		try {
			imapStore = EmailUtils.getImapStore(logger, imapSession, data.getImapHost(), data.getImapPort(), data.getEmail(),
					data.getPassword());
		} catch (MessagingException e) {
			imapConnected = false;
		}

		/************* IF NOT CONNECTED AND HTTP PROXY CAN BE USED THEN RECREATE SESSION AND ATTEMPT ANOTHER CONNECTION THROUGH HTTP PROXY********/
		if (!imapConnected && this.useHttp) {
			proxyMode = 1;
			imapConnected = true;
			try {

				imapSession = EmailUtils.getImapSession(logger, proxyMode, data.getProxy(), data.getProxyPort(),
						data.getProxyUser(), data.getProxyPassword(),
						CONNECTION_TIMEOUT > 0 ? CONNECTION_TIMEOUT : null, false);
			} catch (GeneralSecurityException e) {
				logger.error("Can't create ssl factory", e);
				throw new EmailException("General Exception");
			}
			/************* GET IMAP STORE WITH HTTP PROXY MODE **********/
			try {
				imapStore = EmailUtils.getImapStore(logger, imapSession, data.getImapHost(), data.getImapPort(),
						data.getEmail(), data.getPassword());
			} catch (MessagingException e) {
				imapConnected = false;
			}
		}
		
		/************* IF NOT CONNECTED AND DIRECT CONNECTION CAN BE USED THEN USE DIRECT CONNECTION********/
		if (!imapConnected && !onlyProxy) {
			proxyMode = 0;
			imapConnected = true;
			try {

				imapSession = EmailUtils.getImapSession(logger, proxyMode, data.getProxy(), data.getProxyPort(),
						data.getProxyUser(), data.getProxyPassword(),
						CONNECTION_TIMEOUT > 0 ? CONNECTION_TIMEOUT : null, false);
			} catch (GeneralSecurityException e) {
				logger.error("Can't create ssl factory", e);
				throw new EmailException("General Exception");
			}
			/************* GET IMAP STORE WITH DIRECT CONNECTION **********/
			try {
				imapStore = EmailUtils.getImapStore(logger, imapSession, data.getImapHost(), data.getImapPort(),
						data.getEmail(), data.getPassword());
			} catch (MessagingException e) {
				imapConnected = false;
			}
		}
		
		
		if (!imapConnected) {
			logger.error("No way to connect to server =(");
			throw new EmailException("Proxy are not working");
		}
		
		logger.info("Connected");

	/************CONNECTED WITH IMAP FINALLY********************/
	 Map<String, List<Folder>> imapFoldersList=EmailUtils.getFolders(logger, imapStore);
		if (mode == 0 || mode == 1) {
			List<Folder> junkFolders = imapFoldersList.get(EmailUtils.JUNK_ID);
			if (junkFolders == null || junkFolders.size() == 0)
				throw new EmailException("Unable to get Junk Folder");
			List<Folder> inboxFolders = imapFoldersList.get(EmailUtils.INBOX_ID);
			if (inboxFolders == null || inboxFolders.size() == 0)
				throw new EmailException("Unable to get Inbox Folder");
			List<Folder> archiveFolders = imapFoldersList.get(EmailUtils.ARCHIVE_ID);
			/*********** GET LIST OF MESSAGES TO PROCESS ***************/
			for (Folder junkFolder : junkFolders) {
				try {
					junkFolder.open(Folder.READ_WRITE);
				} catch (MessagingException msgEx) {
					logger.info("Unable to open Junk folder in read/write mode");
					throw new EmailException("Unable to open Junk folder in read/write mode");
				}
				List<List<Message>> messagesToProcess = EmailUtils.getMessagesFromFolder(logger, junkFolder, keyword, 0,
						messagesChunk == null ? null : messagesChunk.intValue(), false);
				if (messagesToProcess.size() == 0 || messagesToProcess.get(0).size() == 0) {
					logger.info("No Message to process");
					throw new EmailException("No Message to process");
				}
				/*********** NOW RUN THE MODES *****************************/
				switch (mode) {
				case 0:
					normalMode(messagesToProcess, junkFolder, inboxFolders.get(0));
					break;
				case 1:
					advanceMode(messagesToProcess, junkFolder, inboxFolders.get(0),
							archiveFolders == null ? null : archiveFolders.get(0),proxyMode);
					break;

				}

			}
		}
	/************DELETE MESSAGES FROM ALL FOLDERS***************/
	if(mode==2)
		deleteMode(imapFoldersList);
	/***********CLOSE THE IMAP STORE AND SESSION***************/
		try {
			imapStore.close();
		} catch (MessagingException e) {
			logger.info("Unable to close ImapStore:"+e.getMessage(),e);
		}
	}
	
	private void normalMode(List<List<Message>> messagesList, Folder junkFolder, Folder inboxFolder) throws EmailException {
		try {
			if(!inboxFolder.isOpen())
				inboxFolder.open(Folder.READ_WRITE);
			}catch(MessagingException msgEx) {
				logger.info("Unable to open inbox folder in read/write mode");
				throw new EmailException("Unable to open inbox folder in read/write mode");
			}

		for (List<Message> messages : messagesList) {
			logger.info("Try to set seen " + messages.size() + " messages in junk");
			for (Message message : messages) {
				try {
					message.setFlag(Flags.Flag.SEEN, true);
				} catch (MessagingException e) {
					logger.info(
							"Unable to set Seen flag for mesesage number:" + message.getMessageNumber() + " in junk");
				}
			}

			logger.info("Try to move " + messages.size() + " messages to inbox");
			try {
				((IMAPFolder) junkFolder).moveMessages(messages.toArray(new Message[0]), inboxFolder);
			} catch (MessagingException e) {
				logger.info("Can't move messages to inbox");
			}
		}
	}
	
	private void advanceMode(List<List<Message>> messagesList, Folder junkFolder, Folder inboxFolder,
			Folder archiveFolder,int proxyMode) {
		for (List<Message> messages : messagesList) {
			logger.info("Try to set seen " + messages.size() + " messages in junk");
			AppendUID[] appendUids = null;
			for (Message message : messages) {
				try {
					message.setFlag(Flags.Flag.SEEN, true);
					if (setFlags)
						message.setFlag(Flags.Flag.FLAGGED, true);
				} catch (MessagingException e) {
					logger.error(
							"Unable to set Seen flag for mesesage number:" + message.getMessageNumber() + " in junk");
				}
			}

			try {
				logger.info("Try to open inbox folder in Read Write Mode");
				if (inboxFolder.isOpen())
					inboxFolder.close();
				inboxFolder.open(Folder.READ_WRITE);
			} catch (MessagingException msgEx) {
				logger.error("Unable to open inbox folder for read write, skipping " + messages.size());
				continue;
			}

			try {
				logger.info("Try to move " + messages.size() + " messages to inbox");
				appendUids = ((IMAPFolder) junkFolder).moveUIDMessages(messages.toArray(new Message[0]), inboxFolder);
			} catch (MessagingException e) {
				logger.error("Can't move messages to inbox");
				continue;
			}

			if (this.archiveMessages) {
				if (archiveFolder == null) {
					logger.error("No arvhive folder found");
					continue;
				}

				
				//**********CLOSING AND OPENING AGAIN AS MOVED MESSAGES FROM JUNK CAN BE READ OTHERWISE method getMessagesByUID WILL FAIL***********/
				try {
					logger.info("Try to open inbox folder in Read Mode");
					if (inboxFolder.isOpen())
						inboxFolder.close();
					inboxFolder.open(Folder.READ_ONLY);
				} catch (MessagingException msgEx) {
					logger.error("Unable to open inbox folder for read write, skipping " + messages.size());
					continue;
				}

				try {
					logger.info("Try to open archive folder in Read Write Mode");
					if (!archiveFolder.isOpen())
						archiveFolder.open(Folder.READ_WRITE);
				} catch (MessagingException msgEx) {
					logger.error("Unable to open archive folder for read write, skipping " + messages.size());
					continue;
				}

				long[] uids = new long[appendUids.length];
				for (int i = 0; i < appendUids.length; i++)
					uids[i] = appendUids[i].uid;

				Message[] messagesToArchive = null;
				try {
					logger.info("Trying to get Messages from Inbox using UIDs:" + uids);
					messagesToArchive = ((IMAPFolder) inboxFolder).getMessagesByUID(uids);
				} catch (MessagingException e) {
					logger.error("Unable to get messages from Inbox for archving");
					continue;
				}

				try {
					logger.info("Trying to moves Messages from inbox to archive");
					((IMAPFolder) inboxFolder).moveMessages(messagesToArchive, archiveFolder);
				} catch (MessagingException e) {
					logger.error("Unable to move "+messages.size()+" messages from Inbox to archive");
					continue;
				}

			}
		}

		if (this.replyMessage) {
			if (this.replyMessageText == null || replyMessageText.trim().equals("")) {
				logger.error("Reply text message is empty therefore not sending reply email");
				return;
			}
			Session smtpSession = null;
			try {
				smtpSession = EmailUtils.getSmtpSession(logger, proxyMode, data.getProxy(), data.getProxyPort(),
						data.getProxyUser(), data.getProxyPassword(),
						CONNECTION_TIMEOUT > 0 ? CONNECTION_TIMEOUT : null, false);
			} catch (GeneralSecurityException e) {
				logger.error("Unable to send reply message because there was an error creating smtp session:"
						+ e.getMessage());
				return;
			}
			Transport smtpTransport = null;
			try {
				smtpTransport = EmailUtils.getSmtpTransport(logger, smtpSession, data.getSmtpHost(), data.getSmtpPort(),
						data.getEmail(), data.getPassword());
			} catch (EmailException | MessagingException ex) {
				logger.error("Unable to send reply message because there was an error creating smtp transport:"
						+ ex.getMessage());
				return;
			}
		
			Message messageToReply= null;
				try {
					messageToReply=EmailUtils.createReplyMessage((MimeMessage)messagesList.get(0).get(0), smtpSession, data.getEmail(), replyMessageText, false, true);
				} catch (MessagingException e) {
					logger.error("Unable to send reply because there was error creating reply message:"+e.getMessage());
					messageToReply=null;
				}
				
			if(messageToReply!=null)
				try {
					smtpTransport.sendMessage(messageToReply, messageToReply.getAllRecipients());
				} catch (MessagingException e) {
					logger.error("Unable to send reply because there was error sending reply message:"+e.getMessage());
					messageToReply=null;
				}
			
			try {
				smtpTransport.close();
			} catch (MessagingException e) {
				logger.info("Unable to close Smtp Transport:"+e.getMessage(),e);
			}
		}
	}
		
	private void deleteMode(Map<String, List<Folder>> imapFoldersList) {

		for (List<Folder> foldersList : imapFoldersList.values())
			for (Folder folder : foldersList) {
				logger.info("Trying to delete all messages from folder :" + folder.getName());
				try {
					if (!folder.isOpen())
						folder.open(Folder.READ_WRITE);
				} catch (MessagingException msgEx) {
					logger.error("Unable to open " + folder.getName() + " folder in read/write mode");
					continue;
				}
				List<List<Message>> messagesChunkToDelete = null;
				try {
					messagesChunkToDelete = EmailUtils.getMessagesFromFolder(logger, folder, null, 0,
							this.messagesChunk, false);
				} catch (EmailException ex) {
					logger.error("Unable to read messages from folder because:" + ex.getMessage());
					continue;
				}

				for (List<Message> messagesList : messagesChunkToDelete)
					for (Message message : messagesList) {
						try {
							message.setFlag(Flags.Flag.DELETED, true);
						} catch (MessagingException ex) {
							logger.error("Unable to delete message number:" + message.getMessageNumber());
						}
					}
			}
	}
		
	
	
	/*public void checkEmail() throws EmailException {
		logger.info("Start check email " + data + " keyword = " + keyword);

		clearProxy();

		MailSSLSocketFactory sf;
		try {
			sf = new MailSSLSocketFactory();
		} catch (GeneralSecurityException e) {
			logger.error("Can't create ssl factory", e);
			return;
		}
		sf.setTrustAllHosts(true);

		Properties props = System.getProperties();
		props.setProperty("mail.store.protocol", "imaps");
		props.setProperty("mail.imaps.ssl.enable", "true");
		props.put("mail.imaps.ssl.trust", "*");
		props.put("mail.imaps.ssl.socketFactory", sf);
		props.put("mail.imaps.connectiontimeout", CONNECTION_TIMEOUT);
		props.put("mail.imaps.timeout", "5000");
		props.put("mail.imap.timeout", "5000");

		Session session = Session.getDefaultInstance(props, null);
		session.setDebug(true);
		Store store;
		try {
			store = session.getStore("imaps");
		} catch (NoSuchProviderException e) {
			logger.error("Can't get imaps store", e);
			return;
		}

		prepareSocksProxy(data);
		if (data.haveUserProxy()) {
			logger.info("Try to connect with socks proxy with user and password");
			prepareProxyAuth(data);
		} else {
			logger.info("Try to connect with socks proxy");
		}
		boolean connected = connect(store, data);

		if (!connected && data.haveUserProxy()) {
			clearProxyAuth();
			logger.info("Try to connect with socks proxy without user and password");
			connected = connect(store, data);
		}

		if (useHttp && !connected) {
			clearProxy();
			prepareHttpProxy(data);
			if (data.haveUserProxy()) {
				logger.info("Try to connect with http proxy with user and password");
				prepareProxyAuth(data);
			} else {
				logger.info("Try to connect http proxy");
			}
			connected = connect(store, data);
		}

		if (!connected && data.haveUserProxy()) {
			clearProxyAuth();
			logger.info("Try to connect with http proxy without user and password");
			connected = connect(store, data);
		}

		if (!connected && !onlyProxy) {
			logger.info("Try to connect without proxy");
			clearProxy();
			connected = connect(store, data);
		}

		if (!connected) {
			logger.error("No way to connect to server =(");
			throw new EmailException("Proxy are not working");
		}

		logger.info("Connected");
		if (setFoldersAndMessages(store)) {
			switch (mode) {
			case 0:
				normalMode();
				break;
			case 1:
				advanceMode();
				break;
			}
		}
	}

	private boolean setFoldersAndMessages(Store store) throws EmailException {
		Folder[] folders;
		try {
			folders = store.getDefaultFolder().list();
		} catch (MessagingException e) {
			logger.error("Can't get default folder", e);
			return false;
		}

		for (Folder folder : folders) {
			String folderName = folder.getName();
			if (RocketText.isEmpty(folderName)) {
				continue;
			}

			logger.info("Check folder " + folderName);
			folderName = folderName.toLowerCase();

			if (folderName.equals("inbox")) {
				logger.info("This folder is inbox folder");
				inboxFolder = folder;
				continue;
			}

			if (folderName.equals("archive")) {
				logger.info("This folder is Archive folder");
				archiveFolder = folder;
				continue;
			}

			if (!(folderName.contains("spam") || (folderName.contains("junk")))) {
				continue;
			}
			logger.info("This folder is spam folder");
			try {
				folder.open(Folder.READ_WRITE);
			} catch (MessagingException e) {
				logger.error("Can't open folder", e);
				continue;
			}

			Message[] messages;
			try {
				messages = folder.getMessages();
			} catch (MessagingException e) {
				logger.error("Can't get messages", e);
				continue;
			}

			for (Message message : messages) {
				if (messagesToProcess != null && messagesToProcess != 0 && messagesToMove.containsKey(folder)
						&& messagesToMove.get(folder).size() >= messagesToProcess) {
					Collections.reverse(messagesToMove.get(folder));
					break;
				}
					
				logger.info("Check new message with subject");
				String subject;
				try {
					subject = message.getSubject();
				} catch (MessagingException e) {
					logger.info("Can't get message subject");
					continue;
				}

				if (keyword != null) {
					subject = subject.toLowerCase();
					logger.info("Subject = " + subject);
					if (subject.contains(keyword)) {
						logger.info("Found message");

						List<Message> list = messagesToMove.computeIfAbsent(folder, k -> new ArrayList<>());
						list.add(message);
					} else {
						List<Message> list = messagesToMove.computeIfAbsent(folder, k -> new ArrayList<>());
						list.add(message);
					}
				}
			}

			logger.info("Total " + messages.length + " in folder");
		}

		if (messagesToMove.isEmpty()) {
			logger.info("No messages to move");
			return false;
		}

		if (inboxFolder == null) {
			logger.info("No inbox folder");
			return false;
		}
		return true;
	}

	private void normalMode() throws EmailException {

		for (Map.Entry<Folder, List<Message>> entry : messagesToMove.entrySet()) {
			Folder folder = entry.getKey();
			List<Message> list = entry.getValue();

			Message messages[] = list.toArray(new Message[list.size()]);
			logger.info("Try to read " + list.size() + " messages");
			try {
				for (Message message : messages) {
					message.setFlag(Flags.Flag.SEEN, true);
				}
			} catch (MessagingException e) {
				e.printStackTrace();
			}

			logger.info("Try to copy " + list.size() + " messages to inbox");
			try {
				folder.copyMessages(messages, inboxFolder);
				logger.info("Try to delete " + list.size() + " messages");
				for (Message message : messages) {
					message.setFlag(Flags.Flag.DELETED, true);
				}
			} catch (MessagingException e) {
				logger.info("Can't move messages to inbox");
			}
					}
	}

	private void advanceMode() throws EmailException {

		for (Map.Entry<Folder, List<Message>> entry : messagesToMove.entrySet()) {
			Folder folder = entry.getKey();
			List<Message> list = entry.getValue();

			Message messages[] = list.toArray(new Message[list.size()]);
			logger.info("Try to read " + list.size() + " messages");
			try {
				for (Message message : messages) {
					message.setFlag(Flags.Flag.SEEN, true);
				}
			} catch (MessagingException e) {
				e.printStackTrace();
			}

			logger.info("Try to copy " + list.size() + " messages to inbox");
			try {
				folder.copyMessages(messages, inboxFolder);
				logger.info("Try to delete " + list.size() + " messages");
				if (setFlags)
					for (Message message : messages) {
						message.setFlag(Flags.Flag.FLAGGED, true);
					}
			} catch (MessagingException e) {
				logger.info("Can't move messages to inbox");
			}

			if(archiveMessages) {
				logger.info("Try to copy " + list.size() + " messages to archive");
				try {
					folder.copyMessages(messages, archiveFolder);
				} catch (MessagingException e) {
					logger.info("Can't move messages to archive");
				}
			}
			
		}
	}

	private boolean connect(@NonNull Store store, @NonNull Data data) throws EmailException {
		try {
			store.connect(data.getHost(), data.getPort(), data.getEmail(), data.getPassword());
			return true;
		} catch (MessagingException e) {
			logger.error("Can't connect", e);
			if (e instanceof AuthenticationFailedException) {
				if (e.getMessage().contains("AUTHENTICATE failed. Account is blocked")) {
					throw new EmailException("Account is blocked");
				} else {
					throw new EmailException("Wrong email or password");
				}
			}
			return false;
		}
	}

	private void prepareHttpProxy(@NonNull Data data) {
		String proxyHost = data.getProxy();
		int proxyPort = data.getProxyPort();

		System.setProperty("http.proxyHost", proxyHost);
		System.setProperty("http.proxyPort", String.valueOf(proxyPort));
		System.setProperty("https.proxyHost", proxyHost);
		System.setProperty("https.proxyPort", String.valueOf(proxyPort));
	}

	private void prepareSocksProxy(@NonNull Data data) {
		String proxyHost = data.getProxy();
		int proxyPort = data.getProxyPort();

		System.setProperty("socksProxyHost", proxyHost);
		System.setProperty("socksProxyPort", String.valueOf(proxyPort));
		System.setProperty("socksProxySet", "true");
	}

	private void prepareProxyAuth(@NonNull Data data) {
		String user = data.getProxyUser();
		String password = data.getProxyPassword();

		System.setProperty("java.net.socks.username", user);
		System.setProperty("java.net.socks.password", password);
		Authenticator.setDefault(new ProxyAuth(user, password));
	}

	private void clearProxyAuth() {
		System.clearProperty("java.net.socks.username");
		System.clearProperty("java.net.socks.password");

		Authenticator.setDefault(null);
	}

	private void clearProxy() {
		System.clearProperty("http.proxyHost");
		System.clearProperty("http.proxyPort");
		System.clearProperty("http.proxySet");
		System.clearProperty("https.proxyHost");
		System.clearProperty("https.proxyPort");
		System.clearProperty("https.proxySet");

		System.clearProperty("socksProxyHost");
		System.clearProperty("socksProxyPort");

		System.setProperty("socksProxySet", "false");
		System.setProperty("java.net.useSystemProxies", "false");

		clearProxyAuth();
	}*/

}
