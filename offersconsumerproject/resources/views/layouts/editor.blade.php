<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title></title>
	<link rel="stylesheet" href="">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
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
            min-height:700px;
            height: -webkit-fill-available;
            display:inline-block;
            width:100%;
        }

        .navs {
            background-color: #3bdda6;
            height: 50px;
            border-bottom: 3px solid #029060;
            box-sizing: content-box;
        }

        ul.lst {
            list-style: none;
            padding-left:0;
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

</style>
</head>
<body>
<div class="container-fluid">

	<div class="row">
        <div class="col-lg-6 un">
			<div class="navs">
				<ul class="lst clearfix">
					<li><p>Editor themes :</p> </li>
					<li>
                        <select class="form-control" id="sel"></select>
					</li>
					{{-- <li>a</li>
					<li>a</li> --}}
				</ul>
			</div>

            <div id="editor">aaaa</div>
            <div id="formbtn" >
            <form class="clearfix" method="POST" action="/CropImage">
                    @csrf
                <input  type="hidden" value="" id="hid" name="img" />
                <button type="submit" class="btn btn-primary" id="btns" disabled>Submit</button>
            </form>
            </div>
        </div>

        <div class="col-lg-6">
			<div class="navs">
				<ul class="lst clearfix">
                    <li> <button id="tit" class="btn btn-info"><i class="fn fas fa-angle-double-left"></i></button>	</li>
                    <li><p>Sid :</p> </li>
					<li>@isset($Sids) <select class="form-control" id="sid"  placeholder="Default input">
                        @foreach ($Sids as $item)
                            <option value="{{$item->sid}}">{{$item->sid}}</option>
                        @endforeach</select> @endisset</li>
                    <li><p>Email Id :</p> </li>
					<li> <select class="form-control" id="emailList"  placeholder="Default input"></select> </li>
				</ul>
			</div>
			<div id="capture" >
				<iframe id='iframe' frameBorder="0" scrolling="no" onload="resizeIframe(this)"></iframe>
			</div>
        </div>
    </div>



	<button id="cpt"><i class="fas fa-camera fa-2x"></i></button>
	<div class="box">
        <div class="circle">
            <a id="blank">take</a>
        </div>
	</div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.1/ie8.polyfils.map" ></script>
<script>


		var d=[ "clouds_midnight"         , "pastel_on_dark" , "ambiance"  , "chrome"  ,
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

	var e =ace.edit("editor");
	e.setTheme("ace/theme/dracula")
    e.session.setMode("ace/mode/html")
    e.setValue('<!DOCTYPE html>\n<html>\n<head>\n<link href="https://fonts.googleapis.com/css?family=Kosugi" rel="stylesheet"><style>body{font-family: "Kosugi", sans-serif;}</style></head>\n<body>\nType  anything in editor <==</body>\n</html>',1);
	e.setOptions({
        fontSize: "12pt"
    });
	e.focus();
	e.getSession().on('change', function() {
        update(e.getValue());
    });


	$("#shoot").click(function(){


	html2canvas($('#capture').get(0)).then(function(canvas) {
			var cans = canvas.toDataURL("image/png");
            $("#blank").attr('href',cans);
			$("#blank").attr('download','this.png');
	});


	});


	$.each( d, function( key, value ) {
		$('#sel').append('<option value='+value+'>'+value+'</option>');
	});
	$("#sel").change(function(){
        e.setTheme("ace/theme/"+ $("#sel").val() );
	});

	$("#tit").click(function() {
        $(".col-lg-6").toggleClass("col-lg-12");
		$(".un").toggleClass("this");
		$(".fn").toggleClass('fa-angle-double-left').toggleClass('fa-angle-double-right');
	});



		$(".box > div").animate({
            height: 'toggle',
			width : 'toggle'

        });

	$("#cpt").click(function(){
        $(".box > div").animate({
            height: 'toggle',
			width : 'toggle'

        });

		html2canvas($('#capture').get(0)).then(function(canvas) {
			var cans = canvas.toDataURL("image/png");
            $("#blank").attr('href',cans);
			$("#blank").attr('download','this.png');
            $("#hid").val(cans);
            $('#btns').removeAttr("disabled");
        });
    });
    update(e.getValue());


    $("#sid").change(function(){
        var sid = $("#sid").val();
        $.post('/offer',{sid:sid},function(data){
            $('#emailList').children().remove();
            $.each( data, function( key, value ) {
		        $('#emailList').append('<option value='+value['id']+'>'+value['id']+'</option>');
	        });
        });
    });

    $("#emailList").change(function(){
        var sid       =  $('#sid').val();
        var emailList =  $('#emailList').val();
    $.post('/offer/content',{sid:sid,emailList:emailList},function(datas){

        var bodys = datas["body"];

            var imgs = bodys.match(/<img\s+[^>]*src="([^"]*)"[^>]*>/g);
            var replaces;

            for (var i = 0; i < imgs.length; i++) {
                replaces = getpositionOfimgclose(imgs[i],imgs[i].indexOf('src="')+4,sid,emailList);
                bodys = bodys.replace( imgs[i] , replaces);
            }

        e.setValue(bodys,1);
        //  $("#iframe").attr("srcdoc",bodys);
    });
    });


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

    function getpositionOfimgclose(string,posOne,sid,emailid){
        subs = string.substr(posOne);
        string =  string.replace(subs ,insertAt(subs.substr(1),subs.substr(1).indexOf('\"')+1,""));
        string = insertAt(string,posOne,'"http://127.0.0.1:8000/storage/offers/images/'+sid+'/'+emailid+'/');
        return string;
    }
    function resizeIframe(obj){
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }



	</script>

</body>
</html>
