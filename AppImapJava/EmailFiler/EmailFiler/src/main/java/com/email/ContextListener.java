package com.email;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;
import javax.servlet.annotation.WebListener;

@WebListener
public class ContextListener implements ServletContextListener {

    private static final Logger logger = LoggerFactory.getLogger(ContextListener.class);

    @Override
    public void contextDestroyed(ServletContextEvent event) {
        logger.info("Context destroyed");
    }

    @Override
    public void contextInitialized(ServletContextEvent event) {
        logger.info("Context initialized");
    }

}
