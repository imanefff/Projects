<?php
if (!empty($_POST['isp']) && !empty($_POST['protocol']) && !empty($_POST['port'])) {
	
    $isp = $_POST['isp'];
    $protocol = $_POST['protocol'];
    $port = $_POST['port'];
    $secure = $_POST['secure'];

    $my_file = __dir__.'/python/hoster.dat';
//    $my_file = 'hoster.dat';
    $content = file_get_contents($my_file);
    $content .= "\r\n" . $isp . ":" . $protocol . ":" . $port . ":" . $secure;
    
//	unlink( $my_file );
    file_put_contents($my_file, $content);

//    print_r( $content );
//	die;
   	 // header('Location: ' . 'index.php');
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
    .add {
        float: right;
    }
    </style>
</head>

<body>

    <div class="container-fluid " style="margin-top:100px;max-width:1350px;">
        <div class="row">
            <div class="jumbotron col-md-12 " style="max-width:60%; margin-right:20%; margin-left:20%">
                <a class="btn btn-outline-info btn-sm" style="margin-top:0px;" href="index.php"><i
                        class="fas fa-arrow-circle-left"></i> Back</a>
                <br><br><br>
                <h3 class="h3" style="text-align: center">Add Domain </h3><br>
                <form class="text-align:center" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="form-group">
                        <label>Domain : [ex => ' yahoo.com ' ]</label>
                        <input name="isp" class="form-control" type="text" placeholder="enter domain " required>
                    </div>
                    <div class="form-group">
                        <label>server : [ex => ' imap.mail.yahoo.com ']</label>
                        <input name="protocol" class="form-control" type="text" placeholder="enter server " required>
                    </div>
                    <div class="form-group">
                        <label>Port :</label>
                        <input name="port" class="form-control " type="text" placeholder="enter port " required>
                    </div>
		    <div class="form-group">
                        <label>Secure :</label>
                        <input class="col-sm-1" name="secure" class="form-check-input" type="radio" checked value="ssl">ssl
			<input class="col-sm-1" name="secure" class="form-check-input" type="radio" value="nossl" >nossl
                    </div>
                    <button type="submit" class="btn btn-info btn-lg btn-block" style="margin-bottom:50px" id="check">
                        &nbsp Add &nbsp </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
