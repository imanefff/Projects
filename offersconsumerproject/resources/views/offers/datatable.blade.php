
{{-- <!DOCTYPE html>
<html>
<head>

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>List Offers</title>
<link rel="shortcut icon" href="{{ asset('icon/stroopwafel-solid.ico') }}">
<link rel="stylesheet" href="">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
<link href="https://fonts.googleapis.com/css?family=Raleway|Roboto" rel="stylesheet"> --}}


{{-- <link href="{{ asset('css/datatables.bootstrap.css') }}" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet"> --}}

@extends('layouts.temp')


@section('title')
<title>{{ config('app.name') }}</title>
<link rel="shortcut icon" href="{{ asset('icon/server-solid.ico') }}">
@endsection


@section('css')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">



<style>
    /* body{
    font-family:  'Roboto','Raleway Thin',"Segoe UI","Helvetica Neue",Arial,sans-serif,
    "Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
    } */
.navs {
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
}

#users-table>tbody > tr> td:nth-child(7),td:nth-child(8),td:nth-child(9){
    text-align: center;
}

.addOffer{
    margin-top: 25px;
}
.addOffer>a{
    float: right;
    margin: 6px;
}

.thead-dark th {
    color: #fff;
    background-color: #212529 !important;
    border-color: #32383e;
}

.SetTime{
    background: #403f3e94;
    position: fixed;
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
/* #users-table tbody tr td *{
    font-size: 14px;
}
#users-table thead tr td *{
    font-size: 14px;
} */

</style>

@endsection



@section('contentSever')

<div class="clearfix addOffer">
    <a href="/offer/create" class="btn btn-primary" >Add Offer</a>
    <h1>Offers </h1>

    </div>
<hr style=" margin-top: 10px; margin-bottom: 30px;">
{{-- <table id="users-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%"> --}}
<table id="users-table" class="table table-responsive table-striped table-hover">
{{-- <table id="users-table" class="table table-responsive table-striped table-hover"> --}}
    <thead class="thead-dark">
        <tr>
            <th> Id</th>
            <th> Advertiser</th>
            <th> Sid </th>
            <th> Name</th>
            <th> Affiliate</th>
            <th> Payout</th>
            <th> Type</th>
            <th> Created</th>
            <th> user</th>
            <th> Entity</th>
            <th> Status </th>
            {{-- <th> Delete</th> --}}
            <th> edit</th>
            <th> update</th>
            <th> Show</th>
            <th> Delete</th>
        </tr>
    </thead>


</table>


{{-- </div> --}}

<div class="SetTime" id="set" style="left: 0px;">
    <p><i class="fas fa-stroopwafel fa-spin"></i></p>
</div>

<br>
<br>
<br>
<br>

@endsection

@section('scriptes')

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>







@php
    echo '<script>';
    echo "var url = '" . url('/') ."';";

    echo '</script>';
@endphp


<script>

$(document).ready(function(){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }

});


    $(function() {
        // console.log( url )
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: url+'/offers/getdataTable',
        columns: [
            {data: 'id'},
            // {data: 'id', orderable: true, searchable: false,visible : false},
            {data: 'advertiser' , name: 'advertiser'},
            {data: 'sid'},
            {data: 'name'},
            {data: 'affiliate', name: 'affiliate'},
            {data: 'type'},
            {data: 'default_payout'},
            {data: 'created_at'},
            {data: 'user', name: 'user'},
            {data: 'entity', name: 'entity'},
            {data: 'status', name: 'status', orderable: true, searchable: false},
            // {data: 'delete', name: 'delete', orderable: true, searchable: false},
            {data: 'edit', name: 'edit', orderable: false, searchable: false},
            {data: 'update', name: 'update', orderable: false, searchable: false},
            {data: 'show', name: 'show', orderable: false, searchable: false},
            {data: 'delete', name: 'delete', orderable: false, searchable: false},
           // edit
            //,
          //  {data: 'updated_at'}
        ],
        order: [[0, 'desc']]
        });
    });




    func =function (id,elem) {

        $('.SetTime').show();
        $.post('http://hawk.syspim.com/mL33szRGT2DRnS3hfSHa/hawkScript.php/',{offer_id:id,url:url},function(data){
        // $.post('http://127.0.0.1:78/',{offer_id:id,url:url},function(data){
        // $.post('http://127.0.0.1:12/hawkScript.php',{offer_id:id,url:url},function(data){
            console.log(data);
            location.reload();
        });

    }

    deleteOffer = function (id,elem){
        $.post('/offer/delete',{id:id},function(data){
        // console.log(data);
            location.reload();
        });
    }
});


</script>

@endsection
{{-- </body>
</html> --}}
