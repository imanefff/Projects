package com.email.utils;

import java.net.Authenticator;
import java.security.GeneralSecurityException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Properties;

import javax.mail.Address;
import javax.mail.AuthenticationFailedException;
import javax.mail.Flags;
import javax.mail.Flags.Flag;
import javax.mail.Folder;
import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.NoSuchProviderException;
import javax.mail.Session;
import javax.mail.Store;
import javax.mail.Transport;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;
import javax.mail.internet.MimeMessage.RecipientType;
import javax.mail.internet.MimeUtility;

import org.slf4j.Logger;

import com.email.EmailException;
import com.sun.mail.util.MailSSLSocketFactory;
import com.sun.mail.util.PropUtil;

public class EmailUtils {

	public static final String INBOX_ID = "INBOX";
	public static final String JUNK_ID = "JUNK";
	public static final String DELETE_ID = "TRASH";
	public static final String ARCHIVE_ID = "ARCHIVE";
	public static final String OTHER_ID = "OTHER";

	public static Store getImapStore(Logger logger, Session session, String host, int port, String userName,
			String password) throws EmailException, MessagingException {
		Store store = null;
		try {
			store = getImapStore(logger, session, host, port, userName, password, false);
		} catch (MessagingException mEx) {
			store = getImapStore(logger, session, host, port, userName, password, true);
		}
		return store;
	}

	private static Store getImapStore(Logger logger, Session session, String host, int port, String userName,
			String password, boolean clearProxy) throws EmailException, MessagingException {
		Store store = session.getStore("imaps");
		try {
			store = session.getStore("imaps");
		} catch (NoSuchProviderException e) {
			logger.error("Can't get imaps store", e);
			throw e;
		}

		if (clearProxy) {
			logger.info("Try to connect through proxy without user and password");
			Authenticator.setDefault(null);
		}
		try {
			store.connect(host, port, userName, password);
		} catch (MessagingException e) {
			logger.error("Can't connect", e);
			if (e instanceof AuthenticationFailedException) {
				if (e.getMessage().contains("AUTHENTICATE failed. Account is blocked")) {
					throw new EmailException("Account is blocked");
				} else {
					throw new EmailException("Wrong email or password");
				}
			}
			throw e;
		}
		return store;
	}

	public static Session getImapSession(Logger logger, int proxyMode, String proxyHost, Integer proxyPort,
			String proxyUser, String proxyPassword, Integer connectionTimeOut, boolean debug)
			throws GeneralSecurityException {
		MailSSLSocketFactory sf;
		try {
			sf = new MailSSLSocketFactory();
		} catch (GeneralSecurityException e) {
			logger.error("Can't create ssl factory", e);
			throw new GeneralSecurityException(e);
		}
		sf.setTrustAllHosts(true);
		Properties props = new Properties();
		props.setProperty("mail.imaps.ssl.enable", "true");
		props.put("mail.imaps.ssl.trust", "*");
		props.put("mail.imaps.ssl.socketFactory", sf);
		if (connectionTimeOut != null) {
			props.put("mail.imaps.connectiontimeout", connectionTimeOut);
			props.put("mail.imaps.timeout", connectionTimeOut);
			props.put("mail.imap.writetimeout", connectionTimeOut);
		}

		if (proxyMode == 0)
			logger.info("Try to connect without proxy");
		if (proxyMode == 1) {
			logger.info("Try to connect with http proxy");
			if (proxyHost != null)
				props.put("mail.imaps.proxy.host", proxyHost);
			if (proxyPort != null)
				props.put("mail.imaps.proxy.port", proxyPort);
			if (proxyUser != null) {
				logger.info("Try to connect with http proxy with user and password");
				props.put("mail.imaps.proxy.user", proxyUser);
			}
			if (proxyPassword != null)
				props.put("mail.imaps.proxy.password", proxyPassword);
		}
		if (proxyMode == 2) {
			logger.info("Try to connect with socks proxy");
			if (proxyHost != null)
				props.put("mail.imaps.socks.host", proxyHost);
			if (proxyPort != null)
				props.put("mail.imaps.socks.port", proxyPort);
			if (proxyUser != null) {
				logger.info("Try to connect with socks proxy with user and password");
				Authenticator.setDefault(new ProxyAuth(proxyUser, proxyPassword));
			}

		}
		Session session = Session.getInstance(props);
		session.setDebug(debug);
		return session;
	}

	public static Session getSmtpSession(Logger logger, int proxyMode, String proxyHost, Integer proxyPort,
			String proxyUser, String proxyPassword, Integer connectionTimeOut, boolean debug)
			throws GeneralSecurityException {

		Properties props = new Properties();
		props.put("mail.smtp.starttls.enable", "true");
		props.put("mail.smtp.auth", "true");

		if (connectionTimeOut != null) {
			props.put("mail.smtp.connectiontimeout", connectionTimeOut);
			props.put("mail.smtp.timeout", connectionTimeOut);
			props.put("mail.smtp.writetimeout", connectionTimeOut);
		}

		if (proxyMode != 0) {
			if (proxyMode == 1) {
				logger.info("Try to connect with proxy");
				if (proxyHost != null)
					props.put("mail.smtp.proxy.host", proxyHost);
				if (proxyPort != null)
					props.put("mail.smtp.proxy.port", proxyPort);
				if (proxyUser != null) {
					logger.info("Try to connect with proxy with user and password");
					props.put("mail.smtp.proxy.user", proxyUser);
				}
				if (proxyPassword != null)
					props.put("mail.smtp.proxy.password", proxyPassword);
			}
			if (proxyMode == 2) {
				logger.info("Try to connect with socks proxy");
				if (proxyHost != null)
					props.put("mail.smtp.socks.host", proxyHost);
				if (proxyPort != null)
					props.put("mail.smtp.socks.port", proxyPort);
				if (proxyUser != null) {
					logger.info("Try to connect with socks proxy with user and password");
					Authenticator.setDefault(new ProxyAuth(proxyUser, proxyPassword));
				}

			}
		}

		Session session = Session.getInstance(props);
		session.setDebug(debug);
		return session;
	}

	public static Transport getSmtpTransport(Logger logger, Session session, String host, int port, String userName,
			String password) throws EmailException, MessagingException {
		Transport trans = null;
		try {
			trans = getSmtpTransport(logger, session, host, port, userName, password, false);
		} catch (MessagingException mEx) {
			trans = getSmtpTransport(logger, session, host, port, userName, password, true);
		}
		return trans;
	}

	private static Transport getSmtpTransport(Logger logger, Session session, String host, int port, String userName,
			String password, boolean clearProxy) throws EmailException, MessagingException {
		Transport trans = null;
		try {
			trans = session.getTransport("smtp");
		} catch (NoSuchProviderException e) {
			logger.error("Can't get smtp transport", e);
			throw e;
		}

		if (clearProxy) {
			logger.info("Try to connect with http proxy without user and password");
			Authenticator.setDefault(null);
		}
		try {
			trans.connect(host, port, userName, password);
		} catch (MessagingException e) {
			logger.error("Can't connect", e);
			if (e instanceof AuthenticationFailedException) {
				if (e.getMessage().contains("AUTHENTICATE failed. Account is blocked")) {
					throw new EmailException("Account is blocked");
				} else {
					throw new EmailException("Wrong email or password");
				}
			}
			throw e;
		}
		return trans;
	}

	public static MimeMessage createReplyMessage(MimeMessage originalMessage, Session session, String from, String body,
			boolean replyToAll, boolean setAnswered) throws MessagingException {

		MimeMessage reply = new MimeMessage(session);
		String subject = originalMessage.getHeader("Subject", null);
		if (subject != null) {
			if (!subject.regionMatches(true, 0, "Re: ", 0, 4))
				subject = "Re: " + subject;
			reply.setHeader("Subject", subject);
		}
		Address a[] = originalMessage.getReplyTo();
		reply.setRecipients(Message.RecipientType.TO, a);
		if (replyToAll) {
			List<Address> v = new ArrayList<>();
			// add my own address to list
			InternetAddress me = InternetAddress.getLocalAddress(session);
			if (me != null)
				v.add(me);
			// add any alternate names I'm known by
			String alternates = null;
			if (session != null)
				alternates = session.getProperty("mail.alternates");
			if (alternates != null)
				eliminateDuplicates(v, InternetAddress.parse(alternates, false));
			// should we Cc all other original recipients?
			// String replyallccStr = null;
			boolean replyallcc = false;
			if (session != null)
				replyallcc = PropUtil.getBooleanProperty(session.getProperties(), "mail.replyallcc", false);
			// add the recipients from the To field so far
			eliminateDuplicates(v, a);
			a = originalMessage.getRecipients(Message.RecipientType.TO);
			a = eliminateDuplicates(v, a);
			if (a != null && a.length > 0) {
				if (replyallcc)
					reply.addRecipients(Message.RecipientType.CC, a);
				else
					reply.addRecipients(Message.RecipientType.TO, a);
			}
			a = originalMessage.getRecipients(Message.RecipientType.CC);
			a = eliminateDuplicates(v, a);
			if (a != null && a.length > 0)
				reply.addRecipients(Message.RecipientType.CC, a);
			// don't eliminate duplicate newsgroups
			a = originalMessage.getRecipients(RecipientType.NEWSGROUPS);
			if (a != null && a.length > 0)
				reply.setRecipients(RecipientType.NEWSGROUPS, a);
		}

		String msgId = originalMessage.getHeader("Message-Id", null);
		if (msgId != null)
			reply.setHeader("In-Reply-To", msgId);

		/*
		 * Set the References header as described in RFC 2822:
		 *
		 * The "References:" field will contain the contents of the parent's
		 * "References:" field (if any) followed by the contents of the parent's
		 * "Message-ID:" field (if any). If the parent message does not contain a
		 * "References:" field but does have an "In-Reply-To:" field containing a single
		 * message identifier, then the "References:" field will contain the contents of
		 * the parent's "In-Reply-To:" field followed by the contents of the parent's
		 * "Message-ID:" field (if any). If the parent has none of the "References:",
		 * "In-Reply-To:", or "Message-ID:" fields, then the new message will have no
		 * "References:" field.
		 */
		String refs = originalMessage.getHeader("References", " ");
		if (refs == null) {
			// XXX - should only use if it contains a single message identifier
			refs = originalMessage.getHeader("In-Reply-To", " ");
		}
		if (msgId != null) {
			if (refs != null)
				refs = MimeUtility.unfold(refs) + " " + msgId;
			else
				refs = msgId;
		}
		if (refs != null)
			reply.setHeader("References", MimeUtility.fold(12, refs));

		if (setAnswered) {
			try {
				reply.setFlag(Flags.Flag.ANSWERED, true);
			} catch (MessagingException mex) {
				// ignore it
			}
		}

		reply.setFrom(from);
		reply.setText(body);
		return reply;
	}

	private static Address[] eliminateDuplicates(List<Address> v, Address[] addrs) {
		if (addrs == null)
			return null;
		int gone = 0;
		for (int i = 0; i < addrs.length; i++) {
			boolean found = false;
			// search the list for this address
			for (int j = 0; j < v.size(); j++) {
				if (((InternetAddress) v.get(j)).equals(addrs[i])) {
					// found it; count it and remove it from the input array
					found = true;
					gone++;
					addrs[i] = null;
					break;
				}
			}
			if (!found)
				v.add(addrs[i]); // add new address to list
		}
		// if we found any duplicates, squish the array
		if (gone != 0) {
			Address[] a;
			// new array should be same type as original array
			// XXX - there must be a better way, perhaps reflection?
			if (addrs instanceof InternetAddress[])
				a = new InternetAddress[addrs.length - gone];
			else
				a = new Address[addrs.length - gone];
			for (int i = 0, j = 0; i < addrs.length; i++)
				if (addrs[i] != null)
					a[j++] = addrs[i];
			addrs = a;
		}
		return addrs;
	}

	public static List<List<Message>> getMessagesFromFolder(Logger logger, Folder folder, String keywordInSubject,
			int messageCount, int processChunk, boolean addDeleted) throws EmailException {
		logger.info("Trying to get messages from folder:" + folder.getName() + " with keyword in subject:"
				+ keywordInSubject + " including deleted:" + addDeleted);
		try {
			if(!folder.isOpen())
				folder.open(Folder.READ_ONLY);
		} catch (MessagingException ex) {
			logger.info("Unable to open folder:" + folder.getName());
			throw new EmailException("Unable to open folder:" + folder.getName());
		}
		List<List<Message>> returnedMessages = new ArrayList<>();

		Message[] allMessages = null;
		try {
			allMessages = folder.getMessages();
		} catch (MessagingException ex) {
			logger.info("Unable to get messages from folder:" + folder.getName());
			throw new EmailException("Unable to get messages from folder" + folder.getName());
		}

		List<Message> allMessagesList = new ArrayList<>();
		for (Message message : allMessages) {

			try {
				message.getFlags();
				message.getAllHeaders();
				if (addDeleted || !message.isSet(Flag.DELETED))
					allMessagesList.add(message);
			} catch (MessagingException e) {
				logger.info("Can't get message flag info for message" + message.getMessageNumber());
				continue;
			}
		}
		Collections.reverse(allMessagesList);
		if (keywordInSubject == null && messageCount != 0)
			allMessagesList = allMessagesList.subList(0, messageCount);

		List<Message> messageChunkList = new ArrayList<>();

		String subject = null;
		for (Message message : allMessagesList) {
			if (keywordInSubject != null) {
				logger.info("Check new message with subject: " + keywordInSubject);
				try {
					subject = message.getSubject();
				} catch (MessagingException e) {
					logger.info("Can't get message subject");
					continue;
				}
				subject = subject.toLowerCase();
				logger.info("Subject = " + subject);
				if (subject != null && subject.contains(keywordInSubject.toLowerCase())) {
					logger.info("Found message");
					messageChunkList.add(message);
				}
			} else
				messageChunkList.add(message);
			if (processChunk != 0) {
				if (messageChunkList.size() == processChunk) {
					returnedMessages.add(messageChunkList);
					messageChunkList = new ArrayList<>();
				}
				if (keywordInSubject != null && messageCount != 0
						&& returnedMessages.size() * processChunk + messageChunkList.size() >= messageCount)
					break;
			}
		}
		if (messageChunkList.size() > 0)
			returnedMessages.add(messageChunkList);

		logger.info("Total " + allMessagesList.size()+ " in folder and selected:");
		return returnedMessages;

	}

	public static Map<String, List<Folder>> getFolders(Logger logger, Store store) throws EmailException {
		Map<String, List<Folder>> foldersMap = new HashMap<>();
		Folder[] folders = null;
		try {
			folders = store.getDefaultFolder().list();
		} catch (MessagingException e) {
			logger.error("Can't get default folder", e);
			throw new EmailException("Unable to get Default Folder");
		}

		for (Folder folder : folders) {
			String folderName = folder.getName();
			if (folderName == null || folderName.isEmpty()) {
				continue;
			}

			logger.info("Check folder " + folderName);
			switch (folderName.toLowerCase()) {
			case "inbox": {
				List<Folder> foldersList = foldersMap.computeIfAbsent(INBOX_ID, k -> new ArrayList<>());
				foldersList.add(folder);
				break;
			}
			case "archive": {
				List<Folder> foldersList = foldersMap.computeIfAbsent(ARCHIVE_ID, k -> new ArrayList<>());
				foldersList.add(folder);
				break;
			}
			case "deleted":
			case "trash": {
				List<Folder> foldersList = foldersMap.computeIfAbsent(DELETE_ID, k -> new ArrayList<>());
				foldersList.add(folder);
				break;
			}
			case "junk":
			case "span": {
				List<Folder> foldersList = foldersMap.computeIfAbsent(JUNK_ID, k -> new ArrayList<>());
				foldersList.add(folder);
				break;
			}
			default: {
				List<Folder> foldersList = foldersMap.computeIfAbsent(OTHER_ID, k -> new ArrayList<>());
				foldersList.add(folder);
				break;
			}
			}
		}
		return foldersMap;
	}

}
