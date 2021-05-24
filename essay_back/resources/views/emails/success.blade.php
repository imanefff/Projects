<!DOCTYPE >
<html >
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
 <p>Hello,</p>
   
<h2>
     offers top links : 
</h2>
     <table border="0" cellpadding="0" cellspacing="0" width="80%" align="center">
            @foreach($creatives  as $creative)
            <tr>
                <td 
                <img src="{{ $creative->creative_image}}" alt="Image" class="img-fluid"tyle="display:block;">                 
                 {{$creative->offer->offer_url}}
                </td>
            </tr>
            @endforeach
        </table>
    
       
  
                          
   <p> Allison Jones</p>
    <br>
   <p> Good by</p>
</p>
</body>
</html>
