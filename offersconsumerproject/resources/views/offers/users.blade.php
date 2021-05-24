{{-- <!DOCTYPE html>
<html>
<head>

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>List Offers</title>
<link rel="shortcut icon" href="{{ asset('icon/stroopwafel-solid.ico') }}">
<link rel="stylesheet" href="">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Raleway|Roboto" rel="stylesheet">
<link href="{{ asset('css/datatables.bootstrap.css') }}" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet"> --}}

@extends('layouts.temp')

@section('css')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">



<style>
    /* body{
    font-family:  'Roboto','Raleway Thin',"Segoe UI","Helvetica Neue",Arial,sans-serif,
    "Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
    } */
/* .container-fluid {
    max-width: 95%;
    margin: 0 auto;
} */
/* .navs {
    width: 100%;
    background-color: #343a40;
    height: 70px;
    margin-bottom: 30px;
    color: aliceblue;
}

.navs>ul{
    list-style: none;
    padding-left: 0px;
}

.navs>ul>li{
   display: inline;
   line-height: 70px;
   padding: 27px 15px;
}

.navs>ul> li:last-child{
    float: right;
    line-height: normal;
    padding: 18px 20px;
    margin-right: 40px;
} */

#users-table>tbody > tr> td:nth-child(7),td:nth-child(8),td:nth-child(9){
    text-align: center;
}


.addOffer{
    margin:  40px 5px 5px 5px;
}

.thead-dark th {
    color: #fff;
    background-color: #212529 !important;
    border-color: #32383e;
}

.SetTime{
    background: #403f3e94;
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 1000000;
    color: #fff;
    font-size: -webkit-xxx-large;
    display: none;
}

 .SetTime>p{
    color: #FFFFFF;
    text-align: center;
    vertical-align: middle;
    position: relative;
    top: 50%;
 }


</style>
@endsection
{{-- </head>
<body> --}}

{{-- <div class="container-fluid" style="padding:0 15px !important;">

<div class="row">
    <div class = "navs">
        <ul class="clearfix">
            <li><a href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a></li>
            <li>--</li>
            <li>

                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ Auth::user()->name }}
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">

                        <li><a href="{{url('/users/list')}}"> Entity : {{ Auth::user()->entity->name }}</a></li>
                      @if(Auth::user()->permission =="admin" ) <li> <a href="{{url('/users/list')}}"> Users management </a>    </li>@endif
                      <li><a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                         {{ __('Logout') }}
                     </a>
                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form></li>
                    </ul>
                  </div></li>
        </ul>
    </div>


</div> --}}

@section('contentSever')

<div class="clearfix addOffer">
    @if( Auth::user()->permission == "admin" || Auth::user()->permission == "manager" )
        <a href="/register" class="btn btn-primary float-right">Add User</a>
    @endif
    <h1>Users </h1>

    </div>
<hr>
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">

        <table id="users-table" class="table  table-striped table-hover">
            <thead class="thead-dark" >
                <tr>
                    <th> Id</th>
                    <th> name </th>
                    <th> email </th>
                    <th> Permession </th>
                    <th> Entity </th>
                    {{-- <th> update</th>
                    <th> delete</th> --}}
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-1"></div>
</div>



{{-- </div> --}}

<div class="SetTime" id="set">
    <p><i class="fas fa-stroopwafel fa-spin"></i></p>
</div>


@endsection

@section('scriptes')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>


{{-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> --}}

 <!--
<script src="https://datatables.yajrabox.com/js/jquery.min.js"></script>
<script src="https://datatables.yajrabox.com/js/bootstrap.min.js"></script>
<script src="https://datatables.yajrabox.com/js/jquery.dataTables.min.js"></script>
<script src="https://datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
-->
    <!-- Scripts -->
{{-- <script src="{{ asset('js/jquery.min.js') }}" defer></script> --}}
{{-- <script src="https://datatables.yajrabox.com/js/jquery.min.js"></script> --}}


{{-- <script src="{{ asset('js/bootstrap.min.js') }}" defer></script> --}}
{{-- <script src="https://datatables.yajrabox.com/js/bootstrap.min.js"></script> --}}


{{-- <script src="{{ asset('js/jquery.dataTables.min.js') }}" defer></script> --}}
{{-- <script src="https://datatables.yajrabox.com/js/jquery.dataTables.min.js"></script> --}}


{{-- <script src="{{ asset('js/datatables.bootstrap.js') }}" defer></script> --}}
{{-- <script src="https://datatables.yajrabox.com/js/datatables.bootstrap.js"></script> --}}


{{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" --}}
{{-- integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> --}}

@php
    echo '<script>';
   echo "var url = '" . url('/') ."';";

    echo '</script>';
@endphp

<script>

    $(function() {
        $('#users-table').DataTable({
            dom:"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-md-12'tr>>",
            processing: true,
            serverSide: true,

            ajax: url+'/users/list/data',
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'email'},
            {data: 'permission'},
            {data: 'entity.name',name: 'entity.name'},
            // {data: 'edit', name: 'edit', orderable: false, searchable: false},
            // {data: 'delete', name: 'delete', orderable: false, searchable: false},
        ],
        order: [[0, 'desc']]
        });
    });


$(document).ready(function(){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }

   //console.log( $(".SetTime"));

});

    // func =function (id,elem) {

    //     $('.SetTime').show();
    //     $.post('http://127.0.0.1:78/',{offer_id:id,url:url},function(data){
    //         // console.log(data);
    //        location.reload();
    //     });

    // }

    // deleteOffer = function (id,elem){
    //     $.post('/offer/delete',{id:id},function(data){
    //     // console.log(data);
    //         location.reload();
    //     });
    // }
});




</script>

@endsection
{{--
</body>
</html> --}}
