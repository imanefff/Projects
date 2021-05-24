package com.email;

import com.email.domain.Data;
import com.email.utils.EmailChecker;
import org.junit.Test;

public class EmailcheckerTest {

    @Test
    public void emailTest() {
        Data my = new Data("okgo01@outlook.com", "privet2poka", "91.243.94.119",
                "connectpplviatech2702", "jt6XzHY2dw", 8085);

//        Data doc = new Data("arjun.gawande12@outlook.com", "ArjunaGaw1", "91.243.94.119",
//                "connectpplviatech2702", "jt6XzHY2dw", 8085);
        EmailChecker emailChecker = new EmailChecker(my,0,0, "welcome",false,false,false,null, false, true);
        try {
            emailChecker.checkEmail();
        } catch (EmailException e) {
            e.printStackTrace();
        }
    }

}
