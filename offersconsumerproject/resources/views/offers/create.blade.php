@extends('layouts.temp')

@section('title')
 <title>Offers Consumer</title>
 <link rel="shortcut icon" href="{{ asset('icon/stroopwafel-solid.ico') }}">
@endsection


@section('css')
<style>
#sub,#nextBtn {
    float: right;
    margin: 5px 15px;
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
}
.show > div{
    width: 50%;
    /* background: blanchedalmond;007bffb5 */
    background: #fff;
    margin: 20px auto;
    border-radius: 5px;
    border: 2px solid #111111a8;
    }

 .hideFrame , .hide{
     display: none;
 }


 .show>p{
     /* color: purple; */
     color: #FFFFFF;
    text-align: center;
    vertical-align: middle;
    position: relative;
    top: 50%;
 }

 ul#links {
    list-style: none;
    padding: 0;
    display: inline-flex;
}

 ul#links > li{
    padding: 0px 10px;
    }

.btns{
    float: right;
    margin: 5px;
}
.contentBtnsUpdateList{
    width: 350px;
    height: 50px;
    float: right;
    margin: 5px;
    text-align: right;
}

.cackAdvert{
    display: none;
}

</style>
@endsection

@section('content')


<div class="contents">

    @if (session('failedAdd'))
        <div class="alert alert-danger" role="alert" style="margin-top: 30px;">
            {{ session('failedAdd') }}
        </div>
    @endif

    <div class="contentBtnsUpdateList"> </div>

    <h1> Create new offer </h1>
    <hr class="hr-primary" />
    <form action="/offer/create/list" method="POST" id="form1">
      @csrf
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Advertiser</label>
        <div class="col-sm-10">
          <select class="form-control" id="advrt">
            <option>Default select</option>
            @foreach($advertisers as $advertiser)

              <option value="{{ $advertiser->name }}" >{{ $advertiser->name }}</option>
            @endforeach
          </select>
    </div>
  </div>

  <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Advertiser Account</label>
    <div class="col-sm-10">
      <select class="form-control"  name="advertiser"  id="accounts">
          <option>Default select</option>

    </select>
    </div>
  </div>

  <div class="form-group row cackAdvert">
    <label for="inputPassword" class="col-sm-2 col-form-label">Offer id</label>
    <div class="col-sm-10">

    <input list="affelt" class="form-control" type="text" id="affilieteId">
    <datalist id="affelt">
    </datalist>
    </div>
  </div>


  <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">SID</label>
    <div class="col-sm-10">

    <input list="screens" class="form-control" type="text" id="sid"  name="sid">
    <datalist id="screens">
    </datalist>

    </div>
  </div>


  <div class="form-group clearfix">
    <button type="button" id="sub" class="btn btn-primary">get offer</button>
  </div>

  <div class="formContainer">
  <hr>


    <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nameOffer" name="name"  placeholder="Name">
    </div>
  </div>


       <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Offer URL</label>
    <div class="col-sm-10">
        <div class="input-group">
            <select class="custom-select" id="offerURL" name="offerURL">
              <option selected>Choose...</option>
            </select>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('#offerURL')" id="CopyLinkBtn" >Copy Link</button>
                <a class="btn btn-outline-secondary" target="_blank" id="offerLinkBtn">Go to link</a>
            </div>
          </div>
    </div>
    </div>



      <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Preview URL</label>
    <div class="col-sm-10">
      <input type="text" class="form-control"  placeholder="Preview URL" name="previewURL" id="previewURL" required>
    </div>
    </div>

        <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Unsubscribe URL</label>
    <div class="col-sm-10">
            <div class="input-group">
                <select class="custom-select" id="unsubscribeURL"  name="unsubscribeURL">
                  <option selected>Choose...</option>
                </select>
                <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button"  id="CopyLinkBtnuns"  onclick="copyToClipboard('#unsubscribeURL')" >Copy Link</button>
                  <a class="btn btn-outline-secondary" target="_blank" id="unsubLinkBtn">Go to link</a>
                </div>
            </div>
    </div>
    </div>

    <br>
    <hr>
    <br>

    <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputEmail4">Categories
          </label>
          <input type="text" class="form-control" id="categories"  placeholder="Categories">
        </div>
        <div class="form-group col-md-6">
          <label for="inputPassword4">Classification Categories</label>
            <select class="form-control" id="Category" name="categories" >
                <option></option>
                @foreach( $categories as $category)
                    <option value="{{$category}}">{{$category}}</option>
                @endforeach
            </select>
        </div>
      </div>
      <br>
      <hr>
      <br>

    <div class="form-group row">
        <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
        <div class="col-sm-10">
            <div class="input-group">
                    <textarea class="form-control" id="description" rows="6" name="description"></textarea>
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary" id="getText" type="button">Html to Text</button>
                    </div>
                </div>
        </div>
    </div>

     <div class="form-group row">
           <label for="inputPassword" class="col-sm-2 col-form-label">Payout type </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="payoutType" name="payoutType"  placeholder="per">
            </div>
    </div>

         <div class="form-group row">
           <label for="inputPassword" class="col-sm-2 col-form-label">Default payout </label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="payout"  name="defaultPayout" placeholder="" >
            </div>
    </div>

  <hr>

                  <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">From Lines</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="fromLines" rows="6" id="fromLines"></textarea>
    </div>
    </div>

                    <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Subject Lines</label>
    <div class="col-sm-10">
      <textarea class="form-control"  name="subjectLines" rows="6" id="subjectLines"></textarea>
    </div>
    </div>

         <div class="form-group row">
           <label for="inputPassword" class="col-sm-2 col-form-label">Countries </label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  placeholder="" id="countries" name="countries">

            </div>
        </div>

        <div class="form-group row">
           <label for="inputPassword" class="col-sm-2 col-form-label">Suppression Link </label>
            <div class="col-sm-10">
              <input type="text" class="form-control"  placeholder="" id="suppressionLink" name="suppressionLink">
            </div>
         </div>
        </form>
         <div class="form-group clearfix">
            <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
         </div>

     </div>


</div>
<div class="show">

        <p class="hd"> <i class="fas fa-stroopwafel fa-spin fa-3x"></i></p>

</div>

@endsection


@section('scriptes')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>

$(document).ready(function(){

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

    $(".show").hide();
    $('.formContainer').hide();
    // $(".btns").hide();

  /* ids==>
      #sub             ==> button get offer , #advrt           ==> advertiser  , #sid             ==> SID              ,
      #description     ==> description      , #nameOffer       ==> name        , #categories      ==> category         ,
      #countries       ==> geotargeting     , #payout          ==> payout      , #payoutType      ==> unit             ,
      #offerURL        ==> Offer URL        , #previewURL      ==> Preview URL , #unsubscribeURL  ==> Unsubscribe URL  ,
      #landingPage     ==> Landing Page     , #fromLines       ==> From Lines  , #expirationDate  ==> Expiration Date  ,
      #subjectLines    ==> Subject Lines    , #level           ==> Level       , #mobileOpt       ==> Mobile optimized ,
      #suppressionLink ==> Suppression Link ,
   */



   /**
   * ajax post for getting offer from DB through 2 paramettres SID and advertiser
   **/

    $("#sub").click(function(){
    var advert = $("#accounts").val();
    var sid = $("#sid").val();
    $('.formContainer').hide();

    EmptyInputs();
        $(".show").show();
    $.post( "/advertiser/sid",{advertiser:advert,sid:sid}, function( data ) {

        console.log(data);
        $(".show").hide();

        if(data == "This Sid not exist")
            alert(data);
        else{
            $("#description").val(data['cache']["description"]);
            $("#nameOffer").val(data['cache']["name"]);
            $('#categories').val(data['cache']["category"])
                countriesSpliter(data['cache']["geotargeting"]);
            $("#payout").val(data['cache']["payout"]);
            $("#payoutType").val(data['cache']["unit"]);

            $("#suppressionLink").val(data["SuppressionLink"]);
                $("#fromLines").val('');
            $.each(data['fromlines'],function(key,val){
                    if(key == 0)
                        $("#fromLines").val(val);
                    else
                        $("#fromLines").val( $("#fromLines").val()+"\n"+val);
                        // $("#fromLines").val( $("#fromLines").val()+val);
                });

                $("#subjectLines").val('');
                $.each(data["subjects"],function(key,val){
                    if(key == 0)
                        $("#subjectLines").val(val);
                    else
                        // $("#subjectLines").val( $("#subjectLines").val()+val);
                        $("#subjectLines").val( $("#subjectLines").val()+"\n"+val);
                });

                if( $( "#accounts" ).data("advtype") == "cake")
                    cakeFill(data);
                else if ($( "#accounts" ).data("advtype") == "hitpath"){
                    hitpathFill(data);
                }else if ($( "#accounts" ).data("advtype") == "hasoffers"){
                    hasOffersFill(data);
                }else if ($( "#accounts" ).data("advtype") == "inhouse"){
                    inHouseFill(data);
                }
                else if ($( "#accounts" ).data("advtype") == "clickbooth"){
                    clickboothFill(data);
                } else if ($( "#accounts" ).data("advtype") == "everflow"){
                    evervlowFill(data);
                }


            $(".show").hide();
            $('.formContainer').show();

            }

        // end ajax.post
        }).fail(function(err) {
            $(".show").hide();
                alert("my be this offer is paused");
            });

    // sub onclick function
    });

                    // getText - description
    $("#getText").click(function(){
        var desc =  $("#description").val();
        desc = desc.replace(/<title>.*<\/title>/gi, '');
        var x = $('<div/>').html(desc).text();
        $("#description").empty();
        $("#description").val($.trim(x));
    });

    $("#sid").change(function(){
        // console.log("changed");
        EmptyInputs();
        $('.formContainer').hide();
    });
    $("#sid").dblclick(function(){
        $("#sid").val("");
        EmptyInputs();
        $('.formContainer').hide();
    });




    /**
    * get Sid when advertiser select chenged
    */

    $('#advrt').on('change', function() {
        $(".cackAdvert").css("display","none");
        EmptyInputs();
        $("#affelt").empty();
        $("#affilieteId").empty();
        $("#sid").empty();
        $("#sid").val('');
        $("#sid").prop("readonly",false);
        $('.formContainer').hide();
    $('#sid').empty();
    $('#accounts').empty();
        var advert = $("#advrt").val();
    $.post( "/advertiser/accounts",{advertiser:advert}, function( data ) {
        $( "#accounts" ).attr( "data-advType", data[0]['api_type'] );
        $("#accounts").append("<option>Default select</option>");
            $.each( data, function( i, val ) {
                $("#accounts").append("<option value='"+val["id"]+"'>"+val["affiliate_id"]+"</option>");
            });

            $(".contentBtnsUpdateList").empty();

        });
    });

    $('#accounts').on('change', function() {
        EmptyInputs();
        $('#sid').empty();
        $("#sid").val('');
        $("#affilieteId").val('');
        $('.formContainer').hide();
        $("#sid").trigger("chosen:updated");
        var advert = $("#accounts").val();
        if(advert !="Default select"){
            $.post( "/advertiser/sids",{advertiser:advert}, function( data ) {
                $("#screens").empty();
                if(document.getElementById("accounts").dataset.advtype == "cake"){
                    $("#sid").prop("readonly",true);
                    $(".cackAdvert").css("display","flex");
                    $.each(data , function(index,value){
                        $("#affelt").append("<option value='"+value.offer_id+"'></option>");
                    });
                }else{
                    $(".cackAdvert").css("display","none");
                    $.each(data , function(index,value){
                        $("#screens").append("<option value='"+value.sid+"'></option>");
                    });
                }
            });
        }

        let id = $('#accounts').val();
        let apiType = document.getElementById('accounts').dataset.advtype;

        $(".contentBtnsUpdateList").empty();
        if(id != "Default select")
            showUpdateList( id , apiType ) ;


        //else
            //$(".contentBtnsUpdateList").empty();
    });


    $("#affilieteId").change(function(){
        EmptyInputs();
        $('.formContainer').hide();
        $("#sid").empty();
        $("#sid").val("");

        $.post('/sid/cack',{advertiser:$("#accounts").val(),affiliet:$("#affilieteId").val()},function(data){
            $("#sid").val(data[0].sid);
        });

    });


    $("#offerURL").change(function(){
        var linktoAdd = "'"+$("#offerURL").val()+"'";
        $("#offerLinkBtn").attr('href',$("#offerURL").val());

        if ($( "#accounts" ).data("advtype") == "hitpath"){
            if( $("#unsubscribeURL").val() === "Choose Link..."){
            var arr = [];
            $("#unsubscribeURL option").each(function(){
                arr.push ( $(this).val());
            });

            $("#unsubscribeURL").empty();
            $.each(arr ,  function ($key,value) {
                if(value != $("#offerURL").val() )
                    $("#unsubscribeURL").append("<option value="+value+">"+value+"</option>");
            });
        }
    }
});


    $("#unsubscribeURL").change(function(){
        var linktoAdd = "'"+$("#unsubscribeURL").val()+"'";
        $("#unsubLinkBtn").attr('href',$("#unsubscribeURL").val());

        if ($( "#accounts" ).data("advtype") == "hitpath"){
            if( $("#offerURL").val() === "Choose Link..."){
            var arr = [];
            $("#offerURL option").each(function(){
                arr.push ( $(this).val());
            });

            $("#offerURL").empty();
                $.each(arr ,  function ($key,value) {
                    if( value != $("#unsubLinkBtn").val() )
                        $("#offerURL").append("<option value="+value+">"+value+"</option>");
                });
            }
        }
    });

    $("#nextBtn").click(function(){
        if( $("#previewURL").val() != ''){
            if ( $("#offerURL").val() == "Choose Link..." &&  $("#unsubscribeURL").val() == "Choose Link..." ) {alert ("May be you have to change ( Offer URL or/and Unsubscribe URL ");}
            else if ( $("#offerURL").val() == "Choose Link..." ||  $("#unsubscribeURL").val() == "Choose Link..." ) {alert("May be you have to change ( Offer URL or/and Unsubscribe URL ");}
            else $("#form1").submit();
        }else alert ("May be you must to fill Preview URL");
    });


// end jquery function
});


function showUpdateList( id , apiType){
    if(id != "Default select")
    $.post('/Advertiser/listUpdate/data',{id:id , apiType:apiType},function(data){
        if(data["api_type"] == "hitpath"){
            if(data["diffInMinutes"]>30){
                $(".contentBtnsUpdateList").append( "<button class='btn btn-primary btns'> Update List </button>" );
            }else
                $(".contentBtnsUpdateList").text('Please Wait '+(30-data["diffInMinutes"])+' minutes ');
        } else $(".contentBtnsUpdateList").append( "<button class='btn btn-primary btns'> Update List </button>" );
    });
}


$(".contentBtnsUpdateList").on("click", "button", function(){

    var id = $('#accounts').val();
    var apiType = document.getElementById('accounts').dataset.advtype;

    $(".show").show();
    $.post('/offers/listUpdate',{id:id,apiType:apiType},function(data){
        $(".show").hide();
        alert("List offers Updated");
    });
});


  /**
  * INSERT Sids in Chosen select Sid
  */

function insertToChosen(option){
    $("#sid").append("<option value='"+option+"'>"+option+"</option>");
    $("#sid").trigger("chosen:updated");
}


  /**
  * appends options to select of Countries after split string passed by @argument
  **/
function countriesSpliter(CountriesToSplite){
    var Countries;
    if(CountriesToSplite === "All traffic accepted" ){
        Countries = CountriesToSplite;
    }else{
        Countries = CountriesToSplite.replace("Only", '');
        Countries = Countries.replace("Traffic allowed from: ", '');
    }

    $('#countries').val(Countries);
}

  /**
  * empty Inputs
  */

function EmptyInputs(){
    var ids = [ "#description"    , "#nameOffer"    , "#categories"     , "#countries"       , "#payout"     , "#payoutType"     ,
                "#offerURL"       , "#previewURL"   , "#unsubscribeURL" , "#landingPage"     , "#fromLines"  , "#expirationDate" ,
                "#subjectLines"   , "#level"        , "#mobileOpt"      , "#suppressionLink"
            ];

    $.each( ids, function( key, val ) {
        $(val).val('');
    });

}



function filterUnsubLink( link , advertiser ){
    if( advertiser == "Adgenics" || advertiser == "Sphere Digital" )
        var patt = new RegExp(".*\/u");
    else if (  advertiser == "Idrive Interactive")
        var patt = new RegExp("P0g~~\/");
    else
        return false;

    var res = patt.test(link);

    return  res;
}

function hitpathFill(data){

    $("#offerURL").empty();
    $("#unsubscribeURL").empty();
    $("#offerURL").append('<option selected>Choose Link...</option>');
    $("#unsubscribeURL").append('<option selected>Choose Link...</option>');

    if(Array.isArray(data["links"]) || typeof data["links"] === 'object'){
        $.each(data["links"],function(key,value){
            var linkComplite = value+data['urlToAdd'];
            $("#offerURL").append('<option value="'+linkComplite+'" >'+linkComplite+'</option>' );
            if( filterUnsubLink ( linkComplite , $( "#advrt" ).val() ) )
                $("#unsubscribeURL").append( '<option value="'+value+'" selected>'+value+'</option>' );
                // $("#unsubscribeURL").append( '<option value="'+linkComplite+'" selected>'+linkComplite+'</option>' );
            else
                $("#unsubscribeURL").append( '<option value="'+value+'" >'+value+'</option>' );
                // $("#unsubscribeURL").append( '<option value="'+linkComplite+'" >'+linkComplite+'</option>' );
        });
    }else{
        $("#offerURL").append('<option value="'+data["links"]+data['urlToAdd']+'" >'+data["links"]+data['urlToAdd']+'</option>' );
        $("#unsubscribeURL").append( '<option value="'+data["links"]+'" >'+data["links"]+'</option>' );
                // $("#unsubscribeURL").append( '<option value="'+data["links"]+data['urlToAdd']+'" >'+data["links"]+data['urlToAdd']+'</option>' );
    }


}

function cakeFill(data){
    $("#offerURL").empty();
    $("#unsubscribeURL").empty();
    $("#offerURL").append('<option selected>Choose Link...</option>');
    $("#unsubscribeURL").append('<option selected>Choose Link...</option>');
    $.each(data['offerlnk'] , function (index , value){
            $("#offerURL").append('<option value="'+value+data['urlToAdd']+'" >'+value+data['urlToAdd']+'</option>');
    });
    $("#offerURL").append('<option value="'+data['offerlnk']+data['urlToAdd']+'" >'+data['offerlnk']+data['urlToAdd']+'</option>');
    // $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+data['urlToAdd']+'" >'+data['UnsubLink']+data['urlToAdd']+'</option>');
    $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+'" >'+data['UnsubLink']+'</option>');
    $('#previewURL').val(data["preview_link"]);
    $('#suppressionLink').val(data["SupLink"]);


}

function hasOffersFill(data){

    $("#offerURL").empty();
    $("#unsubscribeURL").empty();
    $("#offerURL").append('<option selected>Choose Link...</option>');
    $("#unsubscribeURL").append('<option selected>Choose Link...</option>');
    $("#offerURL").append('<option value="'+data['offerlnk']+data['urlToAdd']+'" >'+data['offerlnk']+data['urlToAdd']+'</option>');
    // $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+data['urlToAdd']+'" >'+data['UnsubLink']+data['urlToAdd']+'</option>');
    $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+'" >'+data['UnsubLink']+'</option>');
    $('#previewURL').val(data["preview_link"]);
    $('#suppressionLink').val(data["SupLink"]);
}


function inHouseFill(data){

    $("#offerURL").empty();
    $("#unsubscribeURL").empty();
    $("#offerURL").append('<option selected>Choose Link...</option>');
    $("#unsubscribeURL").append('<option selected>Choose Link...</option>');

    $.each(data['offerlnk'] , function (index,value){
        $("#offerURL").append('<option value="'+value.replace("&sid1=&sid2=&sid3=&sid4=", data['urlToAdd'])+'" >'+value.replace("&sid1=&sid2=&sid3=&sid4=", data['urlToAdd'])+'</option>');
    });

    // $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+data['urlToAdd']+'" >'+data['UnsubLink']+data['urlToAdd']+'</option>');
    $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+'" >'+data['UnsubLink']+'</option>');
    $('#previewURL').val(data["preview_link"]);
    $('#suppressionLink').val(data["SupLink"]);
}

function clickboothFill(data){

    $("#offerURL").empty();
    $("#unsubscribeURL").empty();
    $("#offerURL").append('<option selected>Choose Link...</option>');
    $("#unsubscribeURL").append('<option selected>Choose Link...</option>');
    $.each(data['offerlnk'] , function (index,value){
        $("#offerURL").append('<option value="'+value.replace(/subid1.*/g, data['urlToAdd'])+'" >'+value.replace(/subid1.*/g, data['urlToAdd'])+'</option>');
    });

    // $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+"&"+data['urlToAdd']+'" >'+data['UnsubLink']+"&"+data['urlToAdd']+'</option>');
    $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+'" >'+data['UnsubLink']+'</option>');
    $('#previewURL').val(data["preview_link"]);
    $('#suppressionLink').val(data["SupLink"]);
}

function evervlowFill(data){

    console.log( data )

    $("#offerURL").empty();
    $("#unsubscribeURL").empty();
    $("#offerURL").append('<option selected>Choose Link...</option>');
    $("#unsubscribeURL").append('<option selected>Choose Link...</option>');
    $.each(data['offerlnk'] , function (index,value){
        $("#offerURL").append('<option value="'+value.replace(/subid1.*/g, data['urlToAdd'])+'" >'+value.replace(/subid1.*/g, data['urlToAdd'])+'</option>');
    });

    // // $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+"&"+data['urlToAdd']+'" >'+data['UnsubLink']+"&"+data['urlToAdd']+'</option>');
    $("#unsubscribeURL").append('<option value="'+data['UnsubLink']+'" >'+data['UnsubLink']+'</option>');
    $('#previewURL').val(data["preview_link"]);
    $('#suppressionLink').val(data["SupLink"]);
}

function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).val()).select();
    document.execCommand("copy");
    $temp.remove();
}



</script>

@endsection
