@extends('layouts.temp')

    {{-- style  --}}
@section('css')
<style>
.contents {

/*background-color: coral;*/
margin-top: 100px;
margin-bottom: 100px;

}



ul.imagesls {
list-style-type: none;
width: 500px;
}

ul.imagesls>li>h3 {
font: bold 20px/1.5 Helvetica, Verdana, sans-serif;
}

ul.imagesls>li img {
float: left;
margin: 0 15px 0 0;
}

ul.imagesls>li p {
font: 200 12px/1.5 Georgia, Times New Roman, serif;
}

ul.imagesls>li {
padding: 10px;
overflow: auto;
}

ul.imagesls>li:hover {
background: #eee;
cursor: pointer;
}

.imageShow {
    float: left;
}

.imageShow>h1 {
    width: 500px;
}

.imageGLr {
    position: fixed;
    top: 0;
    left: 1px;
    height: 501px;
    width: 100%;
    background: #0a0a0adb;
    z-index: 10000;
}
.ps{
    display: none;
}

#modalImage {
    display: table;
    margin: 10px auto;
}
</style>
@endsection


    {{-- content  --}}
@section('content')

@if (session('success'))
<div class="alert alert-success" role="alert" style="margin-top: 30px;">
    {{ session('success') }}
</div>
@endif

@if (session('failed'))
<div class="alert alert-danger" role="alert" style="margin-top: 30px;">
    {{ session('failed') }}
</div>
@endif



<h1> Create new offer </h1>
<hr class="hr-primary" />



        <div class="row">
        <div class="col-md-12">
          <span>Name : </span>
          <hr>

    @foreach ($images as  $image)
        <div class="imageShow">
        <h1>{{$image["emailID"]}}</h1>
        <ul class="imagesls">

            <li>
                @php
                $count = 0;
                    $result = File::exists(storage_path('app/public/offers/images/'.$image["sid"].'/'.$image["emailID"].'/'.$image["name"]));
                   if($result){ $content = File::get(storage_path('app/public/offers/images/'.$image["sid"].'/'.$image["emailID"].'/'.$image["name"]));
                    $count = strlen($content);}

                @endphp
            <img src={{url('storage/offers/images/'.$image["sid"].'/'.$image["emailID"].'/'.$image["name"])}}   style="width: 100px; height: 100px;" />
                <h3>{{$image["name"]}}</h3>
                <p style=" margin-bottom: 10px; "> {{$image["case"]}}....{{ $count}}</p>
                @if($count ==0 ) <button class="image" data-sid="{{$image["sid"]}}" data-emailId="{{$image["emailID"]}}" data-name="{{$image["name"]}}" style=" border: none; background: chocolate; border-radius: 5px; width: 100px;">clic</button>

                    @else <button type="button" class="btn btn-primary shows"  data-sid="{{$image["sid"]}}" data-emailId="{{$image["emailID"]}}" data-name="{{$image["name"]}}" data-toggle="modal" data-target=".bd-example-modal-lg">
                        show image
                      </button>
                 @endif
            </li>

        </ul>
        </div>
    @endforeach

    <hr>


</div>

</div>




  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <img id="modalImage" alt="">
            ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>



@endsection

@section('scriptes')
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script>
$(document).ready(function(){

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".image").click(function(){

       var sid     =  $(this).data('sid');
       var emailid =  $(this).data('emailid');
       var name    =  $(this).data('name');

        $.post( "/image/reste",{ sids : sid, emailids : emailid , names : name}, function( data ) {
            console.log(data);
        });
    });

    $(".shows").click(function(){
         var sid     =  $(this).data('sid');
         var emailid =  $(this).data('emailid');
         var name    =  $(this).data('name');
         var link = "http://127.0.0.1:8000/storage/offers/images/"+sid+"/"+emailid+"/"+name
        $("#modalImage").attr("src",link);


    });


});




</script>
@endsection
