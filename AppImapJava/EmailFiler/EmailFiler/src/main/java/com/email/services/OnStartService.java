package com.email.services;

import com.email.AppPreferences;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import javax.annotation.PostConstruct;

@Component
public class OnStartService {

    private static final Logger logger = LoggerFactory.getLogger(OnStartService.class);
    private final AppPreferences preferences;

    @Autowired
    public OnStartService(AppPreferences preferences) {
        this.preferences = preferences;
    }

    @PostConstruct
    public void init() {
        logger.info("OnStart service run");

    }
}
