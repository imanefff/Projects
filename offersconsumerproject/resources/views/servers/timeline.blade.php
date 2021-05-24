{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"> --}}

@extends('layouts.temp')

@section('title')
<title>Server Timeline</title>
<link rel="shortcut icon" href="{{ asset('icon/server-solid.ico') }}">
@endsection


@section('css')


    <style>

/*-------tima line Css------*/
.tracking-detail {
 padding:3rem 0
}
#tracking {
 margin-bottom:1rem
}
[class*=tracking-status-] p {
 margin:0;
 font-size:1.1rem;
 color:#fff;
 text-transform:uppercase;
 text-align:center
}
[class*=tracking-status-] {
 padding:1.6rem 0
}
.tracking-status-intransit {
 background-color:#65aee0
}
.tracking-status-outfordelivery {
 background-color:#f5a551
}
.tracking-status-deliveryoffice {
 background-color:#f7dc6f
}
.tracking-status-delivered {
 background-color:#4cbb87
}
.tracking-status-attemptfail {
 background-color:#b789c7
}
.tracking-status-error,.tracking-status-exception {
 background-color:#d26759
}
.tracking-status-expired {
 background-color:#616e7d
}
.tracking-status-pending {
 background-color:#ccc
}
.tracking-status-inforeceived {
 background-color:#214977
}

.tracking-item {
 border-left:2px solid #fd0000;
 position:relative;
 padding:2rem 1.5rem .5rem 2.5rem;
 font-size:.9rem;
 margin-left:3rem;
 min-height:5rem
}
.tracking-item2 {
 border-left:2px dashed #9c27b0 !important;
}


.btnEnd {
    padding: 4px;
    position: relative;
    /* right: -10%; */
    margin-left: 110px;
    width: 100px;
    text-align: center;
    background: #DA727E;
    border: 1px solid #d43f50;
    border-radius: 3px;
    color: #fff;
    font-weight: 700;
}

.tracking-item:last-child {
 padding-bottom:4rem
}
.tracking-item .tracking-date {
 margin-bottom:.5rem
}
.tracking-item .tracking-date span {
 color:#888;
 font-size:85%;
 padding-left:.4rem
}
.tracking-item .tracking-content {
 padding:.5rem .8rem;
 background-color:#f4f4f4;
 border-radius:.5rem
}
.tracking-item .tracking-content span {
 display:block;
 color:#888;
 font-size:85%
}
.tracking-item .tracking-icon {
 line-height:2.6rem;
 position:absolute;
 left:-1.3rem;
 width:2.6rem;
 height:2.6rem;
 text-align:center;
 border-radius:50%;
 font-size:1.1rem;
 background-color:#fff;
 color:#fff;

}
.tracking-item .tracking-icon.status-sponsored {
 background-color:#f68
}
.tracking-item .tracking-icon.status-delivered {
 background-color:#4cbb87
}
.tracking-item .tracking-icon.status-outfordelivery {
 background-color:#f5a551
}
.tracking-item .tracking-icon.status-deliveryoffice {
 background-color:#f7dc6f
}
.tracking-item .tracking-icon.status-attemptfail {
 background-color:#b789c7
}
.tracking-item .tracking-icon.status-exception {
 background-color:#d26759
}
.tracking-item .tracking-icon.status-inforeceived {
 background-color:#214977
}
.tracking-item .tracking-icon.status-intransit {
 color:#e5e5e5;
 border:1px solid #e5e5e5;
 font-size:.6rem
}
@media(min-width:992px) {
 .tracking-item {
  margin-left:10rem
 }
 .tracking-item .tracking-date {
  position:absolute;
  left:-10rem;
  width:7.5rem;
  text-align:right
 }
 .tracking-item .tracking-date span {
  display:block
 }
 .tracking-item .tracking-content {
  padding:0;
  background-color:transparent
 }




}

@media(max-width:992px) {
   .btnEnd {
      margin: 0;
   }
}


   .tracking-content{
      background: #72a0de3d  !important;
      padding: 10px 10px !important;
   }
   div.tracking-content > p > button {
      float: left;
   }

   div.tracking-content > p > a {
      float: right;
   }

  div.tracking-content > p  {
    margin-bottom: 5px;
   }
   .addClassColor{
      background-color: #214977;
      border: 1px solid #214977;
      color: #fff;
   }
   /*---------- Timeline Css --------*/

   .TimeLineClass {
    max-height: 610px !important;
    /* background: azure; */
    overflow: auto;
}
    </style>

@endsection

{{-- </head>
<body> --}}

      <!-- <i class="fas fa-tags"></i>        tag      -->
      <!-- <i class="fas fa-marker"></i>      update   -->
      <!-- <i class="fas fa-hockey-puck"></i> change   -->
      <!-- <i class="fas fa-user-plus"></i>   add user -->



      {{-- <div class="container-fluid"> --}}
 @section('contentSever')
         {{-- <div class="row">
            <div class="col-md-12">
                  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                        <a class="navbar-brand" href="#">Navbar</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                          <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#">Link</a>
                            </li>
                            <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Dropdown
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                              </div>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link disabled" href="#">Disabled</a>
                            </li>
                          </ul>
                          <form class="form-inline my-2 my-lg-0">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                          </form>
                        </div>
                      </nav>
            </div>
         </div> --}}

         <div class="row">
            <div class="col-md-3"> <h4 style="margin-top:40px;margin-left:30px">Server Timeline</h4> </div>
            <div class="col-md-6">

                  <div class="btn-group btn-group-toggle" data-toggle="buttons" style="margin-left:50px;margin-top:40px;margin-left:30px">
                        <label class="btn btn-dark active">
                          <input type="radio" name="options" id="option1" autocomplete="off" checked> <span id="optionID"> ID </span>
                        </label>

                        <label class="btn btn-info">
                          <input type="radio" name="options" id="option2" autocomplete="off"> <span id="optionServer">Server Name</span>
                        </label>
                      </div>
            </div>
         </div>

 <br>

        <div class="row">
           <div class="col-md-3">
            <div class="row">

               <div class="col-md-12">

                  <h5 align="center" style="margin-bottom:20px">List servers</h5>
                  {{ $servers->links('vendor.pagination.bootstrap-4') }}
                    <div class="list-group">

                        @foreach ($servers as $key => $server)
                            <a class="list-group-item list-group-item-action servers" data-id="{{$server->id}}"> {{$server->name}} </a>
                        @endforeach
                   </div>
                </div>
            </div>




           </div>

            <div class = "col-md-6 col-lg-6" >
                <!-- --------------------------- began time line ------------------------------>

                <div id="tracking" class="TimeLineClass">
                <div class="btnEnd" style="margin-top: 10px;">End</div>

                <!-------------------->
                    <div class="tracking-list">

                       <div class="tracking-item remVss"></div>
                    </div>
                    <div class="btnEnd b2" style="margin-bottom: 10px;" >Start</div>
                 </div>
                 <!-- -----------------   fin timeline ----------------------- -->
              </div>

           </div>
        </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel"> Detail Action </h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="container-fluid">
               <div class="row">
                  <div class="col-md-12">
                     <h5>Date :</h5>
                        <div class="alert alert-info dateModal" role="alert">
                           {{-- 2018-11-27 16:41:54 --}}
                         </div>
                     <br>
                     <h5>User :</h5>
                        <div class="alert alert-info" role="alert">
                              <i class="fas fa-user-tie userModal"></i>
                               {{-- Abdelali laatamli --}}
                        </div>
                     <br>
                     <h5>Commend :</h5>
                        <div class="alert alert-info commendModal" role="alert">
                           {{-- Action [serverName][serverID] with IP [IP] user --}}
                        </div>
                        <br>
                     <h5>Reason :</h5>
                        <div class="alert alert-info reasonModal" role="alert">
                          {{-- reason --}}
                        </div>
                        <br>
                        {{-- resultatModal -- reasonModal -- commendModal -- userModal -- dateModal --}}
                     <h5>Resultat :</h5>
                        <div class="alert alert-info resultatModal" role="alert">
                          {{-- Resultat --}}
                        </div>
                     <!-- <br> -->
                  </div>
               </div>
            {{-- <div class="container-fluid">


          </div> --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
             </div>
            </div>
        </div>
        </div>
        </div>
    </div>
@endsection

@section('scriptes');

    <script src="http://momentjs.com/downloads/moment.js" ></script>

    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".servers").click(function(){

            $('#optionID').empty();
            $('#optionID').text($(this).data('id'));
            $('#optionServer').empty();
            $('#optionServer').text( $(this).text() );

            $.post('/server/history' , { id : $(this).data('id') } , function(data){
                  $(".tracking-list").empty();
                $.each(data , function(index , value) {
                    // console.log( value , value.hestory );

                    $(".tracking-list").append(`<div class="tracking-item">
                          <div class="tracking-icon status-intransit addClassColor" style="background-color: #214977;border:1px solid #214977 ; color:#fff">
                              <i class="fas fa-tags"></i>
                          </div>
                          <div class="tracking-date">`+ moment( value.hestory.created_at ).format("MMM DD, YYYY")  +`<span>`+moment( value.hestory.created_at ).format('LT')+`</span></div>
                          <div class="tracking-content">
                            <p class="clearfix">
                                <a class="btn btn-primary float-left" style="color:#fff" >`+value.hestory.commend+`</a>
                                <a href="#"  class="btn btn-link" onclick='func(this)' data-id="`+value.hestory.id+`">more detail</a>
                            </p>
                           User : `+ firstUpperCas(value.user.name)+`<span>`+value.hestory.resultat.substring(0, 50)+`</span></div>
                       </div> `
                    );

                });


                });
        });


    function firstUpperCas(str){
        str = str.trim();
        str = str.replace( str[0] , str[0].toUpperCase() )
        return str;
    }

    function func(elem){

        let id = elem.dataset.id;

        $.post('/server/getHestory' , { id : id } ,function (data){
            let hestory = data["hestory"];
            let user = data["user"];
            $('.resultatModal').html(hestory.resultat)
            $('.reasonModal').text(hestory.reason)
            $('.commendModal').text(hestory.commend)
            $('.userModal').text(" " +user.name)
            $('.dateModal').text(hestory.created_at)

        });

        $('#exampleModal').modal('toggle')
    }

    </script>
@endsection
{{-- </body>
</html> --}}
