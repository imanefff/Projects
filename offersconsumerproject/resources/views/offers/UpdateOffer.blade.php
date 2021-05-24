@extends('layouts.temp')

@section('css')
<style>
    #sub {
    float: right;
    margin: 5px 15px;
}

.showInfo {
    margin: 5px 0;
    padding: 10px 0;
    font-size: 18px;
}

</style>
@endsection


@section('content')
<div class="contents">

<h1> Update offer </h1>
<hr class="hr-primary" />
{{-- {{$offer["offer"]->id}} --}}
<div class="row showInfo"><div class="col-md-3">Advertiser</div><div class="col-md-1">:</div>  <div class="col-md-8">{{$offer["offer"]->advertiser->name}}</div></div>
<div class="row showInfo"><div class="col-md-3">Advertiser Account</div> <div class="col-md-1">:</div> <div class="col-md-8">{{$offer["offer"]->advertiser->affiliate_id}}</div></div>
<div class="row showInfo"><div class="col-md-3">Sid	</div> <div class="col-md-1">:</div> <div class="col-md-8">{{$offer["offer"]->sid}}</div></div>

<form action="/offer/Update/{{$offer["id"]}}" method="POST" id="form1">
    @csrf

<hr>

<input type="hidden" name="sid" value="{{$offer["offer"]->sid}}">
<input type="hidden" name="offerId" value="{{$offer["id"]}}">
<input type="hidden" name="advertiserId" value="{{$offer["offer"]->advertiser->id}}">


<div class="form-group row">
<label for="inputPassword" class="col-sm-2 col-form-label">Name</label>
<div class="col-sm-10">
    <input type="text" class="form-control" id="nameOffer" name="name"  placeholder="Name" value="{{$offer["offer"]->name}}">
</div>
</div>
{{--
$offer["advertiser"]->url_to_add
$offer["advertiser"]->api_type --}}

<div class="form-group row">
<label for="inputPassword" class="col-sm-2 col-form-label">Offer URL</label>
<div class="col-sm-10">
    <div class="input-group">
        <select class="custom-select" id="offerURL" name="offerURL">
            <option selected>Choose...</option>
            @if($offer["links"] != null)
                @foreach ($offer["links"] as $link)

                    @if( $offer["advertiser"]->api_type == "inhouse" )
                        <option
                            value="{{str_replace("&sid1=&sid2=&sid3=&sid4=",$offer["advertiser"]->url_to_add,$link) }}">
                            {{str_replace("&sid1=&sid2=&sid3=&sid4=",$offer["advertiser"]->url_to_add,$link) }}</option>
                    @elseif($offer["advertiser"]->api_type == "clickbooth")
                        <option
                            value="{{preg_replace('/subid1(.*)/', $offer["advertiser"]->url_to_add,$link)}}">
                            {{preg_replace('/subid1.*/', $offer["advertiser"]->url_to_add,$link)}}</option>
                    @else
                        <option
                            value="{{$link.$offer["advertiser"]->url_to_add}}">
                            {{$link.$offer["advertiser"]->url_to_add}}</option>
                    @endif
                    {{-- <option value="{{$link.$offer['urlToAdd']}}">{{$link.$offer['urlToAdd']}}</option> --}}
                @endforeach
            @else
                <option value="{{$offer["offlnk"].$offer['urlToAdd']}}">{{$offer["offlnk"].$offer['urlToAdd']}}</option>
            @endif
        </select>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="CopyLinkBtn"  onclick="copyToClipboard('#offerURL')" >Copy Link</button>
            <a class="btn btn-outline-secondary" target="_blank" id="offerLinkBtn">Go to link</a>
        </div>
    </div>
</div>
</div>



  <div class="form-group row">
<label for="inputPassword" class="col-sm-2 col-form-label">Preview URL</label>
<div class="col-sm-10">
    @if( $offer['preview_link'] != null )
        <input type="text" class="form-control"  placeholder="Preview URL" name="previewURL" id="previewURL" value ={{$offer['preview_link']}} >
    @else
        <input type="text" class="form-control"  placeholder="Preview URL" name="previewURL" id="previewURL" required>
    @endif
</div>
</div>

    <div class="form-group row">
<label for="inputPassword" class="col-sm-2 col-form-label">Unsubscribe URL</label>
<div class="col-sm-10">
        <div class="input-group">
                {{-- $offer['apiType'] --}}
            <select class="custom-select" id="unsubscribeURL"  name="unsubscribeURL">
              <option selected>Choose...</option>
                @if($offer['apiType'] == "hitpath")
                    @if($offer["links"] != null)
                        @foreach ($offer["links"] as $link)
                            <option value="{{$link.$offer['urlToAdd']}}">{{$link.$offer['urlToAdd']}}</option>
                        @endforeach
                    @else
                        <option value="{{$offer["UnsubLink"].$offer['urlToAdd']}}">{{$offer["UnsubLink"].$offer['urlToAdd']}}</option>
                    @endif
                @else
                    <option value="{{$offer["UnsubLink"].$offer['urlToAdd']}}">{{$offer["UnsubLink"].$offer['urlToAdd']}}</option>
                @endif
            </select>
            <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="CopyLinkBtnuns" onclick="copyToClipboard('#unsubscribeURL')" >Copy Link</button>
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
          <input type="text" class="form-control" id="categories"  placeholder="Categories" value="{{$offer["offer"]->category}}">
        </div>
        <div class="form-group col-md-6">
          <label for="inputPassword4">Classification Categories</label>
            <select class="form-control" id="Category" name="categories" >
                @foreach( $categories as $category)
                    <option value="{{$category}}">{{$category}}</option>
                @endforeach
            </select>
        </div>
      </div>
      {{-- $categories --}}
      <br>
      <hr>
      <br>

    <div class="form-group row">
        <label for="inputPassword" class="col-sm-2 col-form-label">Description</label>
        <div class="col-sm-10">
            <div class="input-group">
                <textarea class="form-control" id="description" rows="6" name="description">{{$offer["offer"]->description}}</textarea>
                <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary" id="getText" type="button">Html to Text</button>
                </div>
            </div>
        </div>
    </div>


 <div class="form-group row">
       <label for="inputPassword" class="col-sm-2 col-form-label">Payout type </label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="payoutType" name="payoutType"  placeholder="per" value="{{$offer["offer"]->unit}}">
        </div>
</div>

     <div class="form-group row">
       <label for="inputPassword" class="col-sm-2 col-form-label">Default payout </label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="payout"  name="defaultPayout" placeholder="" value="{{$offer["offer"]->payout}}">
        </div>
</div>

<hr>

              <div class="form-group row">
<label for="inputPassword" class="col-sm-2 col-form-label">From Lines</label>
<div class="col-sm-10">
  <textarea class="form-control" name="fromLines" rows="6" id="fromLines">
    @if( $offer["fromlines"] != null)
    @foreach ($offer["fromlines"] as $line)
        {{$line}}
    @endforeach
    @endif
</textarea>
</div>
</div>

                <div class="form-group row">
<label for="inputPassword" class="col-sm-2 col-form-label">Subject Lines</label>
<div class="col-sm-10">
  <textarea class="form-control"  name="subjectLines" rows="6" id="subjectLines">
    @if( $offer["subjects"] != null)
        @foreach ($offer["subjects"] as $line)
            {{$line}}
        @endforeach
    @endif
</textarea>
</div>
</div>


     <div class="form-group row">
       <label for="inputPassword" class="col-sm-2 col-form-label">Countries </label>
        <div class="col-sm-10">
                @php
                    $Countries;
                    if($offer["offer"]->geotargeting === "All traffic accepted" ){
                        $Countries = $offer["offer"]->geotargeting;
                    }else{
                        $Countries =  str_replace("Only", "", $offer["offer"]->geotargeting);
                        $Countries =  str_replace("Traffic allowed from: ", "", $Countries);
                    }
                @endphp
            <input type="text" class="form-control"  placeholder="" id="countries" name="countries" value='{{$Countries}}'>
        </div>
    </div>

    <div class="form-group row">
       <label for="inputPassword" class="col-sm-2 col-form-label">Suppression Link </label>
        <div class="col-sm-10">
            <input type="text" class="form-control"  placeholder="" id="suppressionLink" name="suppressionLink" value="{{$offer["SupLink"]}}">
        </div>
     </div>
     <div class="form-group clearfix">
        <button type="submit" id="sub" class="btn btn-primary">Update offer</button>
     </div>
</form>




</div>
@endsection

@section('scriptes')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>

$(document).ready(function(){

  $("#offerURL").change(function(){
        var linktoAdd = "'"+$("#offerURL").val()+"'";
        $("#offerLinkBtn").attr('href',$("#offerURL").val());

    });


    $("#unsubscribeURL").change(function(){
        var linktoAdd = "'"+$("#unsubscribeURL").val()+"'";
        $("#unsubLinkBtn").attr('href',$("#unsubscribeURL").val());
    });

});


 $("#getText").click(function(){
        var desc =  $("#description").val();
        desc = desc.replace(/<title>.*<\/title>/gi, '');
        var x = $('<div/>').html(desc).text();
        $("#description").empty();
        $("#description").val($.trim(x));


 });

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).val()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>


@endsection
