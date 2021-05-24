package com.email.domain;

import com.github.otopba.javarocketstart.RocketText;
import lombok.NonNull;

@lombok.Data
public class Data {

    private final String email;
    private final String password;
    private final String proxy;
    private final String proxyUser;
    private final String proxyPassword;
    private final int proxyPort;
    private final String imapHost;
    private final int imapPort;
    private final String smtpHost;
    private final int smtpPort;
    private int error = 0;
    private String status = "Waiting...";

    public Data(@NonNull String email, @NonNull String password, @NonNull String proxy, String proxyUser, String proxyPassword, int proxyPort) {
        this.email = email;
        this.password = password;
        this.proxy = proxy;
        this.proxyUser = proxyUser;
        this.proxyPassword = proxyPassword;
        this.proxyPort = proxyPort;
        imapHost = "imap-mail.outlook.com";
        imapPort = 993;
        smtpHost = "smtp-mail.outlook.com";
        smtpPort = 587;
    }

    public boolean haveUserProxy() {
        return !RocketText.isEmpty(proxyUser);
    }

}
