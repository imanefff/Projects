<?php
session_start();
if (!(isset($_SESSION['uid']) && !empty($_SESSION['uid'])))
//    echo $_SESSION['uid'];
    $_SESSION['uid'] = uniqid();
//    echo $_SESSION['uid'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Check Address Email </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .add {
        float: right;
    }
    </style>
</head>

<body>
    <div class="alert alert-info">
        <h4 class="h4" style="text-align: center">Check addresses Emails </h4>
    </div>
    <div class="container-fluid " style="max-width:1350px;">
        <div class="row">
            <div class="col-md-12 " style="max-width:60%; margin-left:20%; margin-left:20%">
                <h4><i class="fa fa-check" style="margin-top:100px"></i> Max email per verification 1000 lines
                    "Email:Password"</h4>

                <form id="emaillist">
                    <div class="form-group">
                        <label></label> <label></label>
                        <textarea name="emails" id="emails" class="form-control" rows="1000"
                            style="overflow-y: scroll; height:300px"></textarea>
                    </div>
                    <button type="submit" class="btn btn-info " style="margin-bottom:50px" id="check">
                        <i class="far fa-play-circle"></i> Start Verification</button>
                    <button type="reset" id="reset" class="btn btn-warning " style="margin-bottom:50px;">
                        <i class="fas fa-redo-alt"></i> Reset</button>
		    <a id="add" href="editIsp.php" class="btn btn-primary  edit" style="margin-bottom:50px">
                        <i class="fas fa-edit"></i> Edit isp</a>
                    <a id="add" href="addIsp.php" class="btn btn-success  add" style="margin-bottom:50px">
                        <i class="fas fa-plus"></i> Add isp</a>

                </form>
            </div>
            <div class="col-md-4">
                <button class="alert alert-success" style="text-align:center ; width:100%"
                    onclick=" copy('textValid')">copy <i class="far fa-copy"></i> valid adresses Emails </button>
                <textarea class="form-control" id="textValid" rows="1000"
                    style="overflow-y: scroll; height:350px ;margin-bottom:30px"></textarea>
            </div>
            <div class="col-md-4">
                <button class="alert alert-danger" style="text-align:center ; width:100%"
                    onclick=" copy('textInvalid')">copy <i class="far fa-copy"></i> invalid adresses Emails</button>
                <textarea class="form-control" id="textInvalid" rows="1000"
                    style="overflow-y: scroll; height:350px ;margin-bottom:30px"></textarea>
            </div>
            <div class="col-md-4">
                <button class="alert alert-warning " style="text-align:center; width:100%"
                    onclick=" copy('textFormat')">copy <i class="far fa-copy"></i> Incorrect Format </button>
                <textarea class="form-control" rows="1000" id="textFormat"
                    style="overflow-y: scroll; height:350px ; margin-bottom:30px"></textarea>
            </div>
        </div>
    </div>
    </div>
    </div>



    <!-- Bootstrap core JavaScript
    ===========================-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
    <?= "<script> var uid = '{$_SESSION['uid']}' ; </script> "; ?>
    <script>
    $('#reset').click(function() {
        $('#textValid').reset()
        $('#textInvalid').reset()
        $('#textFormat').reset()
    });

    $("#emaillist").submit(function(e) {
        e.preventDefault();
        $.post('./exec.php', {
            "emails": $(this).serializeArray()[0].value
        }, function(data) {
            $("#emails").val("");
            alert("Verification completed !!")
        });
    });


    function copy(id) {
        let textarea = document.getElementById(id);
        textarea.select();
        document.execCommand("copy");
    }

    var socket = io("http://69.30.210.122:3000");
    socket.on("message", (data) => {

	console.log(data);
        if (data.uiid == uid) {


            switch (data.resultat.case) {
                case 0:
                  
                    $('#textInvalid').val(
                        `${ $('#textInvalid').val() }${ data.resultat.data.email }:${ data.resultat.data.password} \n `
                    );
                    break;
                case 1:
		    
                    $('#textValid').val(
                        `${ $('#textValid').val() }${ data.resultat.data.email}:${data.resultat.data.password}\n`
                    );
                    break;
                case 2:
                    $('#textFormat').val(
                        `${$('#textFormat').val()}${ data.resultat.data.email }:${data.resultat.data.password}\n`
                    );
                    break;
            }
        }

    });

    $('.add').click(function() {
        $.ajax({
            type: "POST",
            url: "addIsp.php",
            success: function(data) {}
        })
    });
    $('.edit').click(function() {
        $.ajax({
            type: "POST",
            url: "editIsp.php",
        })
    });
    </script>
</body>

</html>
