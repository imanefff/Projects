
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="{{asset('plugins/Jcrop/css/jquery.Jcrop.css')}}">


<style>
.imageContainer {
    background-color: #00e6ff;
    display: table;
    margin: 70px auto;
    border-radius: 0 0 7px 7px;
}
.imageContainer>div>img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-top: 20px ;
    margin-bottom: 20px;
    /* width: 50%; */
}
.imageContainer>div {
    border-bottom: 2px solid #111;
}
body {
    /* background-color: #000000e0;#525252e0 */
    background-color: #525252e0;
}

button#Crop {
    margin: 40px auto;
    display: table;
}

.cropParence {
    padding: 0 20px 20px 20px;
}

button#grayStyle {
    float: left;
}

button#blackStyle {
    float: right;
}
.captured {
    width: 250px;
    background-color: #1e1f1f;
    position: fixed;
    top: 0;
     min-height: 100vh;
    /* max-height: 100%; */
    overflow-y: auto;
    text-align: center;
    color: #fff;
}
.captured>div>h3{
padding: 15px 0;
}

.captured>div>ul{
    list-style: none;
    padding-left: 0px;
}
.captured>div>ul>li{
    margin-bottom: 10px;
}
.captured>div>ul>li>div{
    width: 200px;
    max-height: 200px;
    min-height: 50px;
    border-radius: 10px 10px 31px 31px;

    margin: 0 auto;
}
.captured>div>ul>li>div>img{
    width: 196px;
    max-height: 160px;
    min-height: 50px;
    border: 2px solid #007bff;
    border-bottom: none;
    border-radius:9px 9px 0 0;
}
.captured>div>ul>li>div>button{
    width: 196px;
    height: 40px;
    border: 2px solid #007bff;
    border-top: 1px solid #111;
    /* background-color: #007bff; */
    border-radius: 0 0 30px 30px;
    color: #fff;
}
.inner {
    max-height: 100%;
    position: fixed;
    overflow-y: auto;
    width: inherit;
}
.margins{
    margin-left: 250px;
}



::-webkit-scrollbar {
    width: 10px;
}


::-webkit-scrollbar-track {

    background: #f1f1f1;
}


::-webkit-scrollbar-thumb {

    background: #007bff;
}


::-webkit-scrollbar-thumb:hover {

    background: #1e7e34;
}

   .show {
    /* background: aquamarine; */
    background: #14151482;
    position: fixed;
    width: 100%;
    z-index: 100000000;
    top: 0;
    left: 0;
    height: 100%;
    overflow: hidden;
    text-align: center;
}
.show>img {
    margin: 100px 0;
    /* border: 3px solid #007bff; */
    border-radius: 5px;
}

.bottomDiv {
    position: absolute;
    bottom: 0;
    width: 100%;
    background: #dc3545;
    height:40px;
}
.bottomDiv > button {
    width: 100%;
}


</style>

<div class="margins">
<div class="imageContainer">
    <div>
        <img  src="{{$img}}" id="target" />
    </div>
    <input type="hidden" id="base" value="{{$img}}">

    <button id="Crop" class="btn btn-success">Crop Image</button>

    </div>
</div>
</div>

<div class="captured">

    <div class="getImages">
            <div class="form-group">
                    <select class="form-control" id="chooseImage">
                      <option>crop</option>
                      <option>select Image</option>
                    </select>
                  </div>

            <div class="input-group mb-3 selCt">
                    <div class="input-group-prepend">
                            <button class="btn btn-info btn-outline-primary EditLien" type="button">Edit</button>
                        </div>
                    <select class="form-control" id="Images">
                        </select>
                    <div class="input-group-append">
                      <button class="btn btn-info btn-outline-primary" id="ChooseCreative" type="button">Choose</button>
                    </div>
                  </div>

            <div class="input-group mb-3 EditLink">
                <input type="text" class="form-control" placeholder="Link" id="edits">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" id="saveLink" type="button">save</button>
                    </div>
                </div>

    </div>

    <div class="inner">
    <h3>Captured images</h3>

    <ul id="sortable">

    </ul>
    </div>
    <div class="bottomDiv">
        <button type="button" id="btnSave" class="btn btn-danger">Save</button>
    </div>
</div>
    <form action="/saved/Images" method="POST" id="frm1">
    </form>

<div class="show">
</div>

<script src="{{asset('plugins/Jcrop/js/jquery.min.js')}}" ></script>
<script src="{{asset('plugins/Jcrop/js/jquery.Jcrop.min.js')}}" ></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@isset($emailId)
    @php
    echo "<script> \nvar emailID = $emailId ;\n";
        $js_array = json_encode($creativeArray);
        echo "var listEmail = ". $js_array . ";\n";
        $js_array = json_encode($disbled);
        echo "var disbled = ". $js_array . ";\n";
        echo "var offerId = ".$offerId.";\n ";
        echo "var apiType ='".App\Offer::find($offerId)->advertiser->api_type."';\n ";
        echo "var sid = $sid ;\n";
        echo "var url = '".url("/")."';\n";
        echo "</script>\n";

    @endphp
@endisset


<script>
    $( function() {
      $( "#sortable" ).sortable();
      $( "#sortable" ).disableSelection();
    } );
    </script>

<script>
    var CapturedImages = [];
    var Cord =[];
    var x;
    var y;
    var z;
    var h;
    $(document).ready(function(){

             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                $(".show").hide();
        $('#target').Jcrop({
            bgOpacity:.2,
            onSelect:updateCoords
        });


       $('.selCt').hide();
       $('.EditLink').hide();

       $(".EditLien").click(function(){
            $("#edits").val($("#Images").val());
            $('.EditLink').show();
        });

        $("#saveLink").click(function(){
             $("#Images").children();
             var act = $("#Images").val();
             var options = $("#Images").children();
             $("#Images").empty();
             options.each(function (){
                if(this.value == act )
                    $("#Images").append("<option value="+ $("#edits").val()+">"+this.innerText+"</option>")
                else  $("#Images").append(this);
             });
             $('.EditLink').hide();
        });

        $("#Images").change(function(){
            $('.EditLink').hide();
        });

        $("#chooseImage").change(function(){
            if($("#chooseImage").val() == "select Image"){

                $.post("/showCreative",{offerId:offerId,creativeId:emailID},function(result){
                    showImages( getImages(result));
                });

                $('.selCt').show();
            }else{
                $('.selCt').hide();
                $('.EditLink').hide();
            }

        });

        $("#ChooseCreative").click(function (){
            if( $("#Images").val().includes( url ) ){

                $.post('/get/code64',{url:$("#Images").val()},function(data){
                    var clss  = '"btn-success"';
                    var clss2 = '"btn-primary"';
                    var imgn ='"'+data+'"';
                    var index = $(".captured ul").children().length;
                    var arr = { "cordone": { x: "---", y: "----", width: "----", height: "---"} , "img": data };
                    // console.log(arr);
                    CapturedImages[CapturedImages.length] = arr;
                $(".captured ul").append("<li>  <div> <img class='croped' onclick='showImage("+imgn+")' src='"+data+"' alt='Captured'><button  class='choose btn-primary' onclick='toggellclass(this,"+clss+","+clss2+")' data-etat='notSelected' data-id="+index+" data-img="+data+">Choose </button></div> </li>");
                });

            }else if($("#Images").val().includes('data:image')){
                var clss  = '"btn-success"';
                var clss2 = '"btn-primary"';
                var imgn ='"'+$("#Images").val()+'"';
                var index = $(".captured ul").children().length;
                var arr = { "cordone": { x: "---", y: "----", width: "----", height: "---"} , "img": $("#Images").val() };
                    // console.log(arr);
                    CapturedImages[CapturedImages.length] = arr;
                $(".captured ul").append("<li>  <div> <img class='croped' onclick='showImage("+imgn+")' src='"+$("#Images").val()+"' alt='Captured'><button  class='choose btn-primary' onclick='toggellclass(this,"+clss+","+clss2+")' data-etat='notSelected' data-id="+index+" data-img="+$("#Images").val()+">Choose </button></div> </li>");

            }else if( $("#Images").val().includes('http') ) {
                $.post('/get/code64',{url:$("#Images").val()},function(data){
                var clss  = '"btn-success"';
                var clss2 = '"btn-primary"';
                var imgn ='"'+data+'"';
                var index = $(".captured ul").children().length;
                var arr = { "cordone": { x: "---", y: "----", width: "----", height: "---"} , "img": data };
                    // console.log(arr);
                    CapturedImages[CapturedImages.length] = arr;
                $(".captured ul").append("<li>  <div> <img class='croped' onclick='showImage("+imgn+")' src='"+data+"' alt='Captured'><button  class='choose btn-primary' onclick='toggellclass(this,"+clss+","+clss2+")' data-etat='notSelected' data-id="+index+" data-img="+data+">Choose </button></div> </li>");
                });
            }
        });

       $("#grayStyle").click(function(){
        $('#target').Jcrop({
             bgOpacity: .4,
             outerImage: 'storage/offers/imageTemp/JCrop/back.png',
            onSelect:updateCoords
        });
       });

    $(".show").click(function(){
        $(".show").hide();
    });



        $("#Crop").click(function(){
            var img= $("#base").val();
            $.post('/crops',{x:x,y:y,w:z,h:h,img:img,hh:"y"},function(data){
                CapturedImages[CapturedImages.length] = data;
                // console.log(CapturedImages);
                $(".captured ul").empty();
                $.each(CapturedImages,function(index,value){
                    var imgn = '"'+value["img"]+'"';
                    var clss  = '"btn-success"';
                    var clss2 = '"btn-primary"';
                    $(".captured ul").append("<li>  <div> <img class='croped' onclick='showImage("+imgn+")' src='"+value["img"]+"' alt='Captured'><button  class='choose btn-primary' onclick='toggellclass(this,"+clss+","+clss2+")' data-etat='notSelected' data-id="+index+" data-img="+value["img"]+">Choose </button></div> </li>");
                });
                Cord["x"] = data["cordone"]["x"];
                Cord["y"] = data["cordone"]["y"];
                Cord["hgt"] = data["cordone"]["height"];
                Cord["wd"] = data["cordone"]["width"];
           });
        });




        $("#btnSave").click(function(){
            var imgs= [];
            let liLest = $(".captured ul" ).children();
            var btns = liLest[0].childNodes[1].children[1];
            var i = 0;
            $.each(liLest,function(index,value){
                var slct = value.childNodes[1].children[1];

                if(slct.dataset.etat == "selected"){
                    imgs[i] = slct.dataset.img;
                    i++;
                }

            });
            if(imgs.length){
                $( "#frm1" ).empty();
                $( "#frm1" ).append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
                $( "#frm1" ).append('<input type="hidden" name="sid" value="'+sid+'">');
                $( "#frm1" ).append("<input  type='hidden' value='"+offerId+"' name='offerId' />");
                $.each(listEmail,function(index,value){
                    $( "#frm1" ).append('<input type="hidden" name="creativeIds[]" value="'+value+'">');
                });
                if(disbled.length == 1 ){
                    if( disbled[0]!="vide")
                    $( "#frm1" ).append('<input type="hidden" name="disabledArray[]" value="'+disbled[0]+'">');
                }else {
                    $.each(disbled,function(index,value){
                        $( "#frm1" ).append('<input type="hidden" name="disabledArray[]" value="'+value+'">');
                    });
                }
                $( "#frm1" ).append('<input type="hidden" name="disabledArray[]" value="'+emailID+'">');
                //  console.log(imgs);

                $.post("/creativeImage/save",{imgs:imgs,offerId:offerId,creative:emailID},function(data){
                    // console.log(data);
                    $( "#frm1" ).submit();

                });

            }else{
                alert("please choose images to save !!");
            }



        });

    });

    function updateCoords(c){
         x = c.x;   y = c.y; z = c.w;  h = c.h;
    }

   function showImage(ImageBase64){
       var x = document.getElementsByClassName("show");
       if(x[0].style.display === "none"){
            x[0].style.display = 'block';
            x[0].innerHTML = "<img class='theImage' src='"+ImageBase64+"'/>"
       }
   }
    var arry = [];
   function toggellclass(obj,cls,cls2){
      if (obj.classList) {
        obj.classList.toggle(cls);
        obj.classList.toggle(cls2);
        }

        if(obj.classList[1] == "btn-success"){
            obj.dataset.etat = "selected";
        }else if(obj.classList[1] == "btn-primary"){
            obj.dataset.etat = "notSelected";
        }
   }


       function getImages(codeHtml){

        var patt = new RegExp(/<img([^>]+)src="[/]?([^"]+)"([^>]*)>|<( *)img( *)[/>|>]/, "g");
        var array = [];
        var imgs = codeHtml.match(patt);
        imgs.forEach(item => {
        var ii =  item.substring( item.indexOf("src=")+5, item.length );
        var pp = ii.substring( 0 ,ii.indexOf('"') );
            array.push(pp);
        });
        return array;
    }

  function showImages(array){
    $("#Images").empty();
    //console.log(array);
    array.forEach(item=>{
        var name = item.substring(item.lastIndexOf("/")+1,item.length);
        item = item.replace(" ","%20")
            $("#Images").append("<option value="+item+">"+name+"<option>")
        });
  }

</script>

