package com.email.utils;

import com.email.domain.Data;
import lombok.NonNull;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.List;

public class FileUtils {

    private static final Logger logger = LoggerFactory.getLogger(FileUtils.class);
    private static final String FILE_NAME = "temp";

    public static boolean saveFile(@NonNull byte[] bytes) {
        logger.info("Try write file");

        Path path = Paths.get(FILE_NAME);
        try {
            Files.write(path, bytes);
        } catch (IOException e) {
            logger.error("Can't write file", e);
            return false;
        }

        return true;
    }

    public static List<Data> parseFile() {
        return EmailParser.parse(FILE_NAME);
    }

}
