package com.email;

import java.util.List;

import org.springframework.stereotype.Component;

import com.email.domain.Data;

@Component
public class ServerStatus {

    //private static final Logger logger = LoggerFactory.getLogger(ServerStatus.class);

    private boolean isProcessing;
    private String error;
    private List<Data> dataList;

    public boolean isProcessing() {
        return isProcessing;
    }

    public void setProcessing(boolean processing) {
        if (processing) {
            error = null;
        }
        isProcessing = processing;
    }

    public void setError(String error) {
        this.error = error;
    }

    public String getError() {
        return error;
    }

    public void setDataList(List<Data> dataList) {
        this.dataList = dataList;
    }

    public List<Data> getDataList() {
        return dataList;
    }

}
