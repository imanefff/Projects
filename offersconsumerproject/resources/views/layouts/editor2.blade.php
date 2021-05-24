<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Capture creatives</title>
    <link rel="shortcut icon" href="{{ asset('icon/stroopwafel-solid.ico') }}">
	<link rel="stylesheet" href="">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    {{-- https://html2canvas.hertzen.com/dist/html2canvas.min.js --}}
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.1/ace.js"></script>

	<style>
body {
    overflow-x: hidden;
}
#editor{
	width : 100%;
	min-height : 750px;
	display:inline-block;

}
#iframe {
	/* min-height:98vh; */
	min-height:700px;
    height: -webkit-fill-available;
	display:inline-block;
	width:100%;
}

.navs {
    background-color: #272c30;
    height: 50px;
    border-bottom: 3px solid #212529;
    box-sizing: content-box;
}

ul.lst {
	list-style: none;
	padding-left:0;
    color: #fff;
	}

	.lst>li {
    	display: block;
		float : left;
        padding: 6px 20px;
	}

.lst > li > p{
    margin-top: 0px;
    margin-bottom: 0px;
    padding-top: 7px;
    padding-bottom: 7px;

    }

	.container-fluid{
		padding-left:0;
	}

	.col-lg-6{
		padding-left:0;
		padding-right:0;
	}

	.this{
		display:none;
	}

    .box {
        width: 150px;
        height: 150px;
        overflow: hidden;
        position: fixed;
        bottom: 0;
        right: 0;
    }

    .box > .circle {
    height: 300px;
    width: 300px;
    position: absolute;
    bottom: -150px;
    right: -150px;
    border-radius: 50%;
    background: #3bdda6;
    }

	#cpt {
	position: fixed;
    z-index: 1000000;
    bottom: -20px;
    right: -20px;
    width: 75px;
    height: 75px;
    border: none;
    border-radius: 37px;
	background-color: #17A2B8;
	color: #fff;


	}

	#blank{
        position: relative;
        background-color: aquamarine;
        bottom: -88px;
        right: -1px;
        border-radius: 50%;
        transform: rotate(45deg);
        padding-left: 50px;
        padding-right: 225px;
        padding-top: 90px;
        padding-bottom: 182px;
	}

    div#formbtn {
        margin: 40px;
    }
    #formbtn>form>button{
        float:right;
    }

    .show {
        background: #14151482;
        position: fixed;
        width: 100%;
        z-index: 100000000;
        top: 0;
        left: 0;
        height: 100%;
        overflow: hidden;
    }
    .show > div{
        width: 50%;
        background: #fff;
        margin: 20px auto;
        border-radius: 5px;
        border: 2px solid #111111a8;
    }

    .hideFrame , .hide{
        display: none;
    }


    .show>p{
        color: #FFFFFF;
        text-align: center;
        vertical-align: middle;
        position: relative;
        top: 50%;
    }

    #capt{
        float: right;
    }

    #BTnCaptOpenLi{
        float:right;
    }

/* #BTnCaptOpen{
    float:right;
 } */

</style>
</head>
<body>

<div class="container-fluid">

	<div class="row">
    	<div class="col-lg-6 un">
			<div class="navs">
				<ul class="lst clearfix">

                    <li><p><a href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a></p> </li>
					<li>
	                    <select class="form-control" id="sel"></select>
                    </li>
                    <li><p>Name :</p> </li>
                    <li id='nameCretive'><p>name Of creative</p></li>
				</ul>
            </div>


            <div id="editor"></div>
    	</div>

    	<div class="col-lg-6">
			<div class="navs">
				<ul class="lst clearfix">
                    <li> <button id="tit" class="btn btn-info" data-case="off"><i class="fn fas fa-angle-double-left"></i></button>	</li>
                    <li><p>Sid :</p> </li>
					<li>@isset($sid) <p>{{ $sid }}</p>  @endisset</li>
                    <li><p>Email Id :</p> </li>
					<li>@isset($creatives) <select class="form-control" id="emailList"  placeholder="Default input">
                            <option >Choose here</option>
                            @foreach ($creatives as $creative)
                                @if(isset($disabledArray) && !empty($disabledArray) && in_array($creative , $disabledArray))
                                    <option value='{{ $creative }}' disabled >{{ $creative }}</option>
                                @else
                                    <option value='{{ $creative }}'>{{ $creative }}</option>
                                @endif
                            @endforeach
                        </select>
                        @endisset
                </li>
                <li class="uploadImage">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="validatedCustomFile" onchange="preview(this)" required>
                        <label class="custom-file-label" for="validatedCustomFile" id="lbls">Choose file...</label>
                        <div class="invalid-feedback">Example invalid custom file feedback</div>
                    </div>
                </li>

                <li id = "capt">
                    <form method="POST" action="/CropImage" id="frm"> @csrf<input  type="hidden" value="" id="hid" name="img" />
                        <input  type="hidden" value="" id="emailId" name="emailId" />
                    </form>
                    <button class="btn btn-primary" id="btns" data-use="capture" data-captured="notYet">Capture</button>
                </li>
                <li id="BTnCaptOpenLi">
                    <button class="btn btn-primary" id="BTnCaptOpen" >Capture Image</button>
                </li>

				</ul>
			</div>
			{{-- <div id="capture" style="min-height: 100vh; background: black;" > --}}
            <div id="capture" >
				{{-- <iframe id='iframe' frameBorder="0" scrolling="no" ></iframe> --}}
				<iframe id='iframe' frameBorder="0" scrolling="no" onload="resizeIframer(this)"></iframe>
			</div>
    	</div>
    </div>



</div>

<div class="show">

        <p class="hd"> <i class="fas fa-stroopwafel fa-spin fa-3x"></i></p>

</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.1/ie8.polyfils.map" ></script>

@isset($sid)
        @php

        echo "<script> \n";
            echo "var sid = ".$sid.";\n ";
            echo "var offerId = ".$offerId.";\n ";
            $js_array = json_encode($creatives);
            echo "var CrativesArray = ". $js_array . ";\n";
            echo "var apiType ='".App\Offer::find($offerId)->advertiser->api_type."';\n ";
            echo "</script> \n ";
        @endphp
@endisset

<script>


		var d =[ "clouds_midnight"         , "pastel_on_dark" , "ambiance"  , "chrome"  ,
				"mono_industrial"         , "solarized_dark" , "tomorrow"  , "dracula" ,
				"crimson_editor"          , "katzenmilch"    , "textmate"  , "dawn"    ,
		    	"merbivore_soft"          , "dreamweaver"    , "terminal"  , "kuroir"  ,
			    "solarized_light"         , "idle_fingers"   , "iplastic"  , "clouds"  ,
			    "tomorrow_night"          , "vibrant_ink"    , "gruvbox"   , "cobalt"  ,
				"tomorrow_night_blue"     , "merbivore"      , "kr_theme"  , "gob"     ,
				"tomorrow_night_eighties" , "sqlserver"      , "eclipse"   , "xcode"   ,
				"tomorrow_night_bright"   , "twilight"       , "github"    , "monokai"   ];

	$(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // resizeIframer(this){

                // }




$(".show").hide();

	var e = ace.edit("editor");
	e.setTheme("ace/theme/dracula")
    e.session.setMode("ace/mode/html")
    e.setValue('<!DOCTYPE html>\n<html>\n<head>\n<link href="https://fonts.googleapis.com/css?family=Kosugi" rel="stylesheet"><style>body{font-family: "Kosugi", sans-serif;}</style></head>\n<body>\nType anything in editor <==</body>\n</html>',1);
	e.setOptions({
        fontSize: "12pt"
    });
	e.focus();
	e.getSession().on('change', function() {
        update( e.getValue() );
    });

	$("#shoot").click(function(){


	html2canvas($('#capture').get(0)).then(function(canvas) {
		var cans = canvas.toDataURL("image/png");
	    $("#blank").attr('href',cans);
		$("#blank").attr('download','this.png');
    });


	});

    $("#BTnCaptOpen").hide();


	$.each( d, function( key, value ) {
		$('#sel').append('<option value='+value+'>'+value+'</option>');
	});
	$("#sel").change(function(){
    	e.setTheme("ace/theme/"+ $("#sel").val() );
    });
    $(".uploadImage").hide();

	$("#tit").click(function() {
        $(".col-lg-6").toggleClass("col-lg-12");
		$(".un").toggleClass("this");
        $(".fn").toggleClass('fa-angle-double-left').toggleClass('fa-angle-double-right');

        if( $("#tit").attr('data-case') == 'off' ){
            $(".uploadImage").show();
            $("#btns").attr('disabled','disabled');
            $("#btns").attr('data-use','upload');
            $("#btns").removeAttr("data-captured");
            $("#tit").attr('data-case', 'on');
            $("#BTnCaptOpen").show();
        }else{
            $(".uploadImage").hide();
            $("#btns").prop( "disabled" , false );
            $("#btns").attr( 'data-use' , "capture");
            $("#btns").attr('data-captured','notYet');
            $("#tit").attr('data-case'  , 'off');
            $("#BTnCaptOpen").hide();
        }
	});
    update(e.getValue());

    $("#emailList").change(function(){
        var emailList =  $('#emailList').val();
        if (emailList != "Choose here"){
        $(".show").show();

            $.post("/showCreative",{offerId:offerId,creativeId:emailList},function(result){
                result = result.replace(/<img%20/gi, '<img ');
                result+="<br><br>";
                e.setValue(result,1);
                $(".show").hide();
            });

        }else{
            e.setValue("choose creative",1);
        }
    });

});

$("#BTnCaptOpen").click(function(){
        var body = $(iframe).contents().find('body')[0];
            // html2canvas($('#capture').get(0)).then(function(canvas) {
            html2canvas(body).then(function(canvas) {
			var cans = canvas.toDataURL("image/png");
	    	$("#blank").attr('href',cans);
			$("#blank").attr('download','this.png');
            $("#hid").val(cans);
            $("#emailId").val($("#emailList").val());
            $.each(CrativesArray,function(index,value){
                $( "#frm" ).append( " <input  type='hidden' value='"+value+"' name='creativeArray[]' />" );
            });
            var i = 0;
            $.each( $("#emailList").children() , function (index , value){
                if(value.disabled ) {
                    $( "#frm" ).append( " <input  type='hidden' value='"+value.value+"' name='disabledArray[]' />" );
                    i++;
                }
                if(index == $("#emailList").children().length-1 && i == 0) {
                    $( "#frm" ).append( " <input  type='hidden' value='vide' name='disabledArray[]' />" );
                }
            });

            $( "#frm" ).append( " <input  type='hidden' value='"+sid+"' name='sid' />" );
            $( "#frm" ).append( " <input  type='hidden' value='"+offerId+"' name='offerId' />" );
            $('#btns').removeAttr("disabled");

    	});
});



$("#btns").click(function(){

    var body = $(iframe).contents().find('body')[0];

            // html2canvas(body).then(canvas => {
            // // document.body.appendChild(canvas)
            //     var cans = canvas.toDataURL("image/png");
            //     console.log( cans )
            //});
    if(  $("#btns").data("use")  == 'capture' ){
        if( document.querySelector("#btns").dataset.captured  == "notYet"){
            if( $("#emailList").val() != "Choose here"){
            // html2canvas($('#capture').get(0)).then(function(canvas) {
                html2canvas( body ).then(function(canvas) {
                    var cans = canvas.toDataURL("image/png");
                    $("#blank").attr('href',cans);
                    $("#blank").attr('download','this.png');
                    $("#hid").val(cans);
                    $("#emailId").val($("#emailList").val());
                    console.log( cans )
                    $.each(CrativesArray,function(index,value){
                        $( "#frm" ).append( " <input  type='hidden' value='"+value+"' name='creativeArray[]' />" );
                    });
                    var i = 0;
                    $.each( $("#emailList").children() , function (index , value){
                        if(value.disabled ) {
                            $( "#frm" ).append( " <input  type='hidden' value='"+value.value+"' name='disabledArray[]' />" );
                            i++;
                        }
                        if(index == $("#emailList").children().length-1 && i == 0) {
                            $( "#frm" ).append( " <input  type='hidden' value='vide' name='disabledArray[]' />" );
                        }
                    });

                    $( "#frm" ).append( " <input  type='hidden' value='"+sid+"' name='sid' />" );
                    $( "#frm" ).append( " <input  type='hidden' value='"+offerId+"' name='offerId' />" );
                    $('#btns').removeAttr("disabled");
                });

                setTimeout(function(){
                    $("#btns").attr('data-captured','Yes');
                    $("#btns").text('Next : Crop');
                    $(".btn").toggleClass( "btn-primary" ).toggleClass( "btn-success" );
                },1000);

            }else alert('choose an Email ID');

        }else if( document.querySelector("#btns").dataset.captured == "Yes") {
            $("#frm").submit();
        }
    }else{
        $("#frm").submit();
    }

});


	function  update(str){
		var idoc = document.getElementById('iframe').contentWindow.document;
			idoc.open();
			idoc.write(str);
			idoc.close();
	}


    function insertAt(string,position,stringtoAdd){
        return string.substr(0, position) + stringtoAdd + string.substr(position);
    }


    function preview (input){
        var reader = new FileReader();
        reader.readAsDataURL(input.files[0]);
        reader.onload = function(e){

            document.getElementById('lbls').innerText = input.files[0]["name"].substring(0, 20) + " ...";
            document.getElementById('hid').value = e.target.result;
            document.getElementById('emailId').value = document.getElementById('emailList').value;

            for(var i = 0 ; i < CrativesArray.length ; i++ ){
                var e = document.createElement( "input");
                    e.setAttribute('type','hidden');
                    e.setAttribute('value',CrativesArray[i]);
                    e.setAttribute('name','creativeArray[]');
                document.getElementById('frm').appendChild(e);
            }

            var empty = 0 ;
            for( var i = 0 ; i < document.getElementById("emailList").childElementCount ;i++ ){
                child = document.getElementById("emailList").children;
                if(child[i].disabled){
                    var e = document.createElement( "input");
                    e.setAttribute('type','hidden');
                    e.setAttribute('value',child[i].value);
                    e.setAttribute('name','disabledArray[]');
                    document.getElementById('frm').appendChild(e);
                    empty++;
                }

                if(i == document.getElementById("emailList").childElementCount -1 && empty == 0){
                    var e = document.createElement( "input");
                    e.setAttribute('type','hidden');
                    e.setAttribute('value','vide');
                    e.setAttribute('name','disabledArray[]');
                    document.getElementById('frm').appendChild(e);
                }
            }

            var e = document.createElement( "input");
            e.setAttribute('type','hidden');
            e.setAttribute('value',sid);
            e.setAttribute('name','sid');
            document.getElementById('frm').appendChild(e);

            var e = document.createElement( "input");
            e.setAttribute('type','hidden');
            e.setAttribute('value',offerId);
            e.setAttribute('name','offerId');
            document.getElementById('frm').appendChild(e);


            document.getElementById('btns').removeAttribute("disabled");
        }

    }

    function getImages(codeHtml){
        var patt = /<img >/i;
        return codeHtml.match(patt);
    }

    // function resizeIframer(iframe) {
    //     iframe.height = iframe.contentWindow.document.body.scrollHeight + "px";
    // }

    function resizeIframer(obj){
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }



	</script>

</body>
</html>
