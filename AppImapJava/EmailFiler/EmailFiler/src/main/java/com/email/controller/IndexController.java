package com.email.controller;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.time.LocalDate;
import java.time.Month;
import java.util.List;

import javax.servlet.http.HttpServletResponse;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RequestPart;
import org.springframework.web.multipart.MultipartFile;
import org.springframework.web.servlet.ModelAndView;

import com.email.EmailException;
import com.email.ServerStatus;
import com.email.domain.Data;
import com.email.utils.EmailChecker;
import com.email.utils.FileUtils;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.github.otopba.javarocketstart.RocketText;

import io.reactivex.Observable;
import io.reactivex.schedulers.Schedulers;
import lombok.NonNull;

@Controller
public class IndexController {

    private static final Logger logger = LoggerFactory.getLogger(IndexController.class);
    private static final String ERROR = "error";
    private static final String INDEX = "index";
    private static final String PROCESSING = "processing";
    private static final String DATA_LIST = "dataList";
    private static final int NEW_DATA_TIME = 200;

    @Autowired
    private ServerStatus serverStatus;

    @RequestMapping(value = "/", method = RequestMethod.GET, produces = "text/html")
    public ModelAndView index() {
        logger.info("Index request");

        ModelAndView modelAndView = new ModelAndView(INDEX);
        modelAndView.addObject(PROCESSING, serverStatus.isProcessing());
        modelAndView.addObject(ERROR, serverStatus.getError());
        modelAndView.addObject(DATA_LIST, serverStatus.getDataList());

        return modelAndView;
    }

    @RequestMapping(value = "/upload", method = RequestMethod.POST, produces = "text/html")
    public ModelAndView upload(@RequestPart MultipartFile file) {
        logger.info("Upload request");

        ModelAndView modelAndView = new ModelAndView("redirect:/");

        if (serverStatus.isProcessing()) {
            serverStatus.setError("Another file is processing");
        } else {
            if (file == null || file.isEmpty()) {
                serverStatus.setError("File is empty");
            } else {
                byte[] bytes = null;
                try {
                    bytes = file.getBytes();
                } catch (IOException e) {
                    logger.error("Can't get bytes", e);
                    serverStatus.setError("Can't read file");
                }

                if (bytes != null) {
                    if (!FileUtils.saveFile(bytes)) {
                        serverStatus.setError("Can't save file");
                    } else {
                        logger.info("Ok, run task");
                        List<Data> list = FileUtils.parseFile();
                        logger.info("Parsed file = " + list);
                        if (list == null || list.isEmpty()) {
                            serverStatus.setError("Empty or wrong format file");
                        } else {
                            serverStatus.setDataList(list);
                        }
                    }
                }
            }
        }

        return modelAndView;
    }

    @PostMapping("/run")
    public ModelAndView run(@RequestParam("mode") @NonNull Integer mode,@RequestParam("emails_to_process") @NonNull Integer emails_to_process,@RequestParam(value="flag_emails",required=false,defaultValue="false") Boolean flag_emails,@RequestParam(value="reply_emails",required=false,defaultValue="false") Boolean reply_emails,@RequestParam(value="archive_emails",required=false,defaultValue="false") Boolean archive_emails,@RequestParam(value="keyword",required=false) String keyword,@RequestParam(value="reply",required=false) String reply) {
        logger.info("Run request");
        serverStatus.setError(null);

       ModelAndView modelAndView = new ModelAndView("redirect:/");
       if(emails_to_process <0)
    	   serverStatus.setError("Number of emails to process should be 0 or greater");
       else if((mode==0 || mode ==1) && RocketText.isEmptyWithTrim(keyword))
    	   serverStatus.setError("Keyword is empty");
       else if (mode==1 && reply_emails && RocketText.isEmptyWithTrim(reply))
    	   serverStatus.setError("Reply message is empty");
       
       if(serverStatus.getError()==null) {
    	   serverStatus.setProcessing(true);
           runTask(mode,emails_to_process,keyword,flag_emails,reply_emails,archive_emails,reply);
       }
    	   
       
       /*if (RocketText.isEmpty(mode)) {
            serverStatus.setError("mode is empty");
        } 
        else {
            serverStatus.setProcessing(true);
            runTask(Integer.parseInt(mode),keyword);
        }*/

        return modelAndView;
    }

    
    
    private void runTask(@NonNull Integer mode,Integer messagesToProcess,String keyword,boolean setFlags,boolean replyMessage,boolean archiveMessages,String replyMessageText) {
    	Observable.create(
                emitter -> {
                    for (Data data : serverStatus.getDataList()) {
                        data.setStatus("Processing...");
                        EmailChecker emailChecker = new EmailChecker(data,mode,messagesToProcess, mode==2?null:keyword,setFlags,replyMessage,archiveMessages,replyMessageText, true, true);
                        try {
                            emailChecker.checkEmail();
                        } catch (EmailException ex) {
                            data.setError(1);
                            data.setStatus(ex.getMessage());
                            continue;
                        }

                        data.setError(0);
                        data.setStatus("Success");
                    }

                    emitter.onComplete();
                })
                .observeOn(Schedulers.computation())
                .subscribeOn(Schedulers.computation())
                .doFinally(() -> {
                    logger.info("Finish processing task");
                    serverStatus.setProcessing(false);
                })
                .subscribe();
    }

    @GetMapping("/new-data")
    public void getNewData(HttpServletResponse response) throws IOException {
//        logger.debug("New get data request");
        response.setContentType("text/event-stream");
        response.setCharacterEncoding("UTF-8");
        PrintWriter writer = response.getWriter();

        StringWriter stringWriter = new StringWriter();
        new ObjectMapper().writeValue(stringWriter, serverStatus);
        String data = stringWriter.toString();
//        logger.debug("Data = " + data);

        writer.write("retry: " + NEW_DATA_TIME + "\n");
        writer.write("data: " + data + "\n\n");
        writer.flush();
        writer.close();
    }
}
