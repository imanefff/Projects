package com.email;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Component;

import java.util.prefs.BackingStoreException;
import java.util.prefs.Preferences;

@Component
public class AppPreferences {

    private static final Logger logger = LoggerFactory.getLogger(AppPreferences.class);

    private Preferences preferences;

    private AppPreferences() {
        preferences = Preferences.userNodeForPackage(AppPreferences.class);
    }

    public void clear() {
        try {
            preferences.clear();
        } catch (BackingStoreException e) {
            logger.error("Can't clear prefs", e);
        }
    }

}
