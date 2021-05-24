package com.email;

import com.email.domain.Data;
import com.email.utils.EmailParser;
import org.junit.Assert;
import org.junit.Test;

import java.util.List;

public class EmailParserTest {

    @Test
    public void parseTest() {
        List<Data> list = EmailParser.parse("test.xlsx");

        Data data = list.get(0);

        Assert.assertEquals("arjun.gawande12@outlook.com", data.getEmail());
        Assert.assertEquals("ArjunaGaw1", data.getPassword());
        Assert.assertEquals("104.200.33.150", data.getProxy());
        Assert.assertEquals("pxuser", data.getProxyUser());
        Assert.assertEquals("BkCn9jn2dGrXttylO", data.getProxyPassword());
        Assert.assertEquals(3128, data.getProxyPort());
    }

}
