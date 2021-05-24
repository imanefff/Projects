<?php

   if(isset($_POST['save'])) {
	
    $my_file =__dir__.'/python/hoster.dat';
    file_put_contents($my_file,$_POST['hostContent']);
    //$file_open = fopen($my_file,'a+');
    //fwrite($file_open, 'hello');
    //fclose($file_open);

    }
   

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Check Address Email </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.jumbotron{
margin-left: 50px;
}
</style>
</head>
<body>

    <div class="container">
        <div class="row">
              <div class="jumbotron col-md-11"> 
                <a class="btn btn-outline-info btn-sm" href="index.php"><i class="fas fa-arrow-circle-left"></i> Back</a>
                <center><h3 class="h3" >Edit ISP </h3></center>
                <form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div class="row">
			<div class="col-sm-1"></div>
			<div class="col-sm-6">
        	            <textarea class="form-control rounded-0" name="hostContent" id="exampleFormControlTextarea1" rows="20">
				<?php
				   $my_file = __dir__.'/python/hoster.dat';
					echo file_get_contents($my_file);
				?>
				</textarea>
			</div>
			<div class="col-sm-4">
			<br><br><br><br>
		<div class="row">
                    
                        <!-- 	<label>Port :</label>
                        	<input name="port" class="form-control" type="text" placeholder="enter port " required> 
			-->
			<div class="alert alert-info col-sm-12" role="alert">
				<strong>Format :</strong><br>
				For encrypted connection <strong>(ssl)</strong><br>
				Domain<strong> : </strong>Server<strong> : </strong>Port<strong> : </strong></strong>ssl<br>
				For noencrypted connection <strong>(nossl)</strong><br>
				Domain<strong> : </strong>Server<strong> : </strong>Port<strong> : </strong>nossl
				
			
                    </div>
		 </div>
		 <br>
		 <div class="row">
                    <button name="save" type="submit" class="btn btn-info btn-lg btn-block" id="check">
                        &nbsp Save &nbsp </button>
		</div>
		</div>
                </form>
            </div>
        </div>
</div>   
</body>

</html>
