<%@ page import="com.github.otopba.javarocketstart.RocketText" %>
<%@ page import="com.email.domain.Data" %>
<%@ page import="java.util.List" %>
<%@ page contentType="text/html; charset=UTF-8" %>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

<%
    String error = (String) request.getAttribute("error");
    System.out.println("Error = " + error);

    boolean processing = (Boolean) request.getAttribute("processing");
    System.out.println("processing = " + processing);

    @SuppressWarnings("unchecked")
    List<Data> dataList = (List<Data>) request.getAttribute("dataList");
    System.out.println("dataList = " + dataList);
%>

<span id="error_span" style="font-size: small; color: red; visibility:
    <% if (!RocketText.isEmpty(error)){%>
        visible
    <%} else {%>
        hidden
    <%}%>;">${error}</span>

<span id="processing_span" style="font-size: small; color: green;visibility:
    <% if (!processing){%>
        hidden
    <%} else {%>
        visible
    <%}%>;">Processing....</span>

<form method="post" action="upload" enctype="multipart/form-data">
    Select file to upload:</br>
    <input type="file" name="file" id="fileChooser" required/>
    </br>
    <input id="upload_input" type="submit" value="Upload"
            <% if (processing) {%>
           disabled="disabled"
            <%}%>/>
</form>
</br>

<form id="run_form" method="post" action="run" enctype="multipart/form-data" onsubmit="return checkForm();"
        <% if (dataList == null || dataList.isEmpty() || processing) {%>
      hidden="hidden"
        <%}%>
>
    Mode:&nbsp;&nbsp;
    <select id="mode" name="mode" onchange="changeMode()">
    	<option value="0">Normal</option> 
    	<option value="1">Advanced</option>
    	<option value="2">Delete</option>
    </select><br><br><br>
    <span id="count_span">
    Number of Emails to Process (Select 0 for All):<br>
    	<input type="number" id="emails_to_process" name="emails_to_process" value=10 required>
    </span><br>
    <span id="keyword_span">
    	Keyword:</br>
    	<input type="text" id="keyword" name="keyword">
    	</br>
    </span>
    <span id="advance_option_span">
    	<input type="checkbox" name="flag_emails" checked>Flag Emails:</input>&nbsp;&nbsp;
    	<input type="checkbox" id="reply_emails" name="reply_emails" onchange="changeReply()" checked>Reply Emails:</input>&nbsp;&nbsp;
    	<input type="checkbox" name="archive_emails" checked>Archive Emails:</input>&nbsp;&nbsp;
    </span><br>
    <span id="reply_span">
    	Reply Message:</br>
    	<textarea id="reply" name="reply" rows="2" cols="100"></textarea>
    	</br>
    </span><br>
    
    
    <input type="submit" value="Process"/>
    
</form>
<br/>
<br/>

<table border="1" id="data_table"
        <% if (dataList == null || dataList.isEmpty()) {%>
       hidden="hidden"
        <%}%>
>
    <tr>
        <th>SR NO.</th>
        <th>Email ID</th>
        <th>PROXY IP</th>
        <th>PROXY USER</th>
        <th>PROXY PASSWORD</th>
        <th>PROXY PORT</th>
        <th>ERROR</th>
        <th>STATUS</th>
    </tr>
    <%
        if (dataList != null) {
            for (int i = 0; i < dataList.size(); i++) {
                Data data = dataList.get(i);
    %>
    <tr>
        <td><%=i%>
        </td>
        <td><%=data.getEmail()%>
        </td>
        <td><%=data.getProxy()%>
        </td>
        <td><%=data.getProxyUser()%>
        </td>
        <td><%=data.getProxyPassword()%>
        </td>
        <td><%=data.getProxyPort()%>
        </td>
        <td><%=data.getError()%>
        </td>
        <td><%=data.getStatus()%>
        </td>
    </tr>
    <%
            }
        }

    %>
</table>

<script>
    var dataSource;

	changeMode0("0");
	
    function closeNewData() {
        if (dataSource != null && dataSource !== undefined) {
            console.log("Close new data");
            dataSource.close();
        }
    }

    function askNewData() {
        closeNewData();
        console.log("Open new data");
        dataSource = new EventSource("new-data");
        dataSource.onmessage = function (event) {
            updateDataSet(event.data);
        };
    }

    function updateDataSet(data) {
        console.log("New data set");
        var temp = JSON.parse(data);

        if (temp == null) {
            return;
        }

        var errorSpan = document.getElementById("error_span");
        if (temp.error != null) {
            errorSpan.style.visibility = "visible";
            errorSpan.textContent = temp.error;
        } else {
            errorSpan.style.visibility = "hidden";
        }

        var processingSpan = document.getElementById("processing_span");
        var uploadInput = document.getElementById("upload_input");

        var processing = temp.processing;
        console.log("isProcessing = " + processing);

        if (processing) {
            uploadInput.disabled = true;
            processingSpan.style.visibility = "visible";
        } else {
            uploadInput.disabled = false;
            processingSpan.style.visibility = "hidden";
            closeNewData();
        }

        var list = temp.dataList;

        var runForm = document.getElementById("run_form");
        var table = document.getElementById("data_table");

        if (list == null && list.length === 0 || processing) {
            runForm.hidden = true;
        } else {
            runForm.hidden = false;
        }

        if (list != null && list.length !== 0) {
            table.hidden = false;

            var rowCount = table.rows.length;
            for (var i = 1; i < rowCount; i++) {
                table.deleteRow(1);
            }

            for (i = 0; i < list.length; i++) {
                var newRow = table.insertRow(i + 1);
                var newCell = newRow.insertCell(0);
                var newText = document.createTextNode(i);
                newCell.appendChild(newText);

                newCell = newRow.insertCell(1);
                newText = document.createTextNode(list[i].email);
                newCell.appendChild(newText);

                newCell = newRow.insertCell(2);
                newText = document.createTextNode(list[i].proxy);
                newCell.appendChild(newText);

                newCell = newRow.insertCell(3);
                newText = document.createTextNode(list[i].proxyUser);
                newCell.appendChild(newText);

                newCell = newRow.insertCell(4);
                newText = document.createTextNode(list[i].proxyPassword);
                newCell.appendChild(newText);

                newCell = newRow.insertCell(5);
                newText = document.createTextNode(list[i].proxyPort);
                newCell.appendChild(newText);

                newCell = newRow.insertCell(6);
                newText = document.createTextNode(list[i].error);
                newCell.appendChild(newText);

                newCell = newRow.insertCell(7);
                newText = document.createTextNode(list[i].status);
                newCell.appendChild(newText);
            }
        } else {
            table.hidden = true;
        }
    }
    
    function changeMode(){
    changeMode0(document.getElementById("mode").value);
	}
    
    function changeMode0(mode) {
	switch(mode){
		case "0":
			document.getElementById("keyword_span").hidden=false;
			document.getElementById("reply_span").hidden=true;
			document.getElementById("advance_option_span").hidden=true;
			document.getElementById("keyword").required=true;
			document.getElementById("reply").required=false;
			break;
		case "1":
			document.getElementById("keyword_span").hidden=false;
			document.getElementById("reply_span").hidden=false;
			document.getElementById("advance_option_span").hidden=false;
			document.getElementById("keyword").required=true;
			changeReply();
			break;
		case "2":
			document.getElementById("keyword_span").hidden=true;
			document.getElementById("reply_span").hidden=true;
			document.getElementById("advance_option_span").hidden=true;
			document.getElementById("keyword").required=false;
			document.getElementById("reply").required=false;
		break;
		
		}
    }
    
    function changeReply(){
    document.getElementById("reply_span").hidden=!document.getElementById("reply_emails").checked;
    document.getElementById("reply").required=document.getElementById("reply_emails").checked;
    }

	function checkForm() {
	var emails_to_process=document.getElementById("emails_to_process").value;
	if(emails_to_process < 0 )
		{
		alert("Please enter a valid number greater than 0 in field: Number of Emails to Process");
		return false;
		}
	
	return true;
	}
</script>

<%
    if (processing) {
%>
<script>
    askNewData();
</script>
<%
    }
%>
</body>
</html>