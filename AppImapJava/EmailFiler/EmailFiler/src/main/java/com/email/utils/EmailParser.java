package com.email.utils;

import com.email.domain.Data;
import com.github.otopba.javarocketstart.RocketText;
import lombok.NonNull;
import org.apache.poi.openxml4j.exceptions.NotOfficeXmlFileException;
import org.apache.poi.xssf.usermodel.XSSFCell;
import org.apache.poi.xssf.usermodel.XSSFRow;
import org.apache.poi.xssf.usermodel.XSSFSheet;
import org.apache.poi.xssf.usermodel.XSSFWorkbook;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

public class EmailParser {

    private static final Logger logger = LoggerFactory.getLogger(EmailParser.class);

    public static List<Data> parse(@NonNull String path) {
        logger.info("Try to read " + path);

        List<Data> list = new ArrayList<>();

        InputStream inputStream;
        try {
            inputStream = new FileInputStream(path);
        } catch (FileNotFoundException e) {
            logger.error("Can't read file " + path, e);
            return list;
        }

        XSSFWorkbook workbook;
        try {
            workbook = new XSSFWorkbook(inputStream);
        } catch (IOException | NotOfficeXmlFileException e) {
            logger.error("Can't open xlsx", e);
            return list;
        }

        XSSFSheet sheet = workbook.getSheetAt(0);

        Iterator rowIterator = sheet.rowIterator();
        if (rowIterator.hasNext()) {
            rowIterator.next();
        } else {
            logger.error("File content no rows");
            return list;
        }

        int count = 1;
        while (rowIterator.hasNext()) {
            logger.info("Read row #" + count);
            XSSFRow row = (XSSFRow) rowIterator.next();

            XSSFCell cell = row.getCell(0);
            if (cell == null) {
                logger.error("No email cell. Skip");
                continue;
            }
            String email = cell.getStringCellValue();
            if (RocketText.isEmpty(email)) {
                logger.error("Empty email cell. Skip");
                continue;
            }

            cell = row.getCell(1);
            if (cell == null) {
                logger.error("No password cell. Skip");
                continue;
            }
            String password = cell.getStringCellValue();

            cell = row.getCell(2);
            if (cell == null) {
                logger.error("No proxy cell. Skip");
                continue;
            }
            String proxy = cell.getStringCellValue();

            String proxyUser = null;
            cell = row.getCell(3);
            if (cell == null) {
                logger.error("No proxy user cell");
            } else {
                proxyUser = cell.getStringCellValue();
            }

            String proxyPassword = null;
            cell = row.getCell(4);
            if (cell == null) {
                logger.error("No proxy password cell");
            } else {
                proxyPassword = cell.getStringCellValue();
            }

            cell = row.getCell(5);
            if (cell == null) {
                logger.error("No proxyPort cell. Skip");
                continue;
            }
            int proxyPort = (int) cell.getNumericCellValue();

            Data data = new Data(email, password, proxy, proxyUser, proxyPassword, proxyPort);
            list.add(data);

            logger.info("Create new data " + data);
            count++;
        }

        logger.info("Finish read " + path + " total row " + count);

        return list;
    }

}
