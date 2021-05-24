{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('icon/server-solid.ico') }}">
    <title> Manage PMTAs </title> --}}

@extends('layouts.temp')


@section('title')
<title> Manage PMTAs </title>
<link rel="shortcut icon" href="{{ asset('icon/server-solid.ico') }}">
@endsection

@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <style>

        body{
            background-color: #f1f1f1;
        }

        .listServers{
            max-height: 600px;
            overflow: auto;
        }
        .ActionBtns{
        }

        button.btns-list {
            float: right;
            padding: 0 5px;
            margin: 0 3px;
            width: 25px;
        }

        .ActionBtns ul{
            list-style: none;
            padding: 0;
        }

        .ActionBtns ul li {
            margin-top: 15px;
        }

        .ActionBtns ul li button{
            width:100%;
        }

        .entait {
            background: #fff;
            height: 60px;
            border: 2px solid #c1c1c1c2;
            box-shadow: 0 0px 5px #00000047;
            border-radius: 5px;
        }

        ul.listNav {
            list-style: none;
            padding: 0;
            margin-left: 10px;
        }

        ul.listNav li {
            display: initial;
            max-width:250px;
        }

        .iputGrp {
            width: 300px;
            display: table-row-group;
        }
        .btnss {
            padding-top :10px;
        }
        .btnss>button{
            /* width: 19%; */
                margin-bottom: 3px;}

        .divRes {
            padding: 15px 30px;
        }
        div.divRes h6 {
            margin-bottom: 20px;}

        div.divRes h6 span {
            font-size: 2rem;
            font-weight: 700;
        }

        div.entait ul{
            list-style: none;
            margin: 0;
            padding: 0;
        }

        div.entait ul li{
            float: right;
            padding-left: 7px;
            padding-right: 7px;
            padding-top: 5px;
            color:#0069d9;
        }

        div.entait ul li:last-child{
            float: left;
            color:#111;
        }
        div.servers-choosed{
            border: 1px solid;
            padding: 5px 3px;
            border-radius: 0 0 5px 5px;
        }

        .top {
            background: #343a40;;
            color: #fff;
            min-width: 100%;
            padding: 5px 10px 2px 10px;
            border-radius: 5px 5px 0 0;
          }

        .top i {
            float: right;
            padding: 5px 10px
        }

        .serv{
            margin-right: 3px;
            margin-bottom: 3px;
        }


    .red-tooltip + .tooltip > .tooltip-inner {
      background-color: #73AD21;
      color: #FFFFFF;
      border: 1px solid green;
      padding: 15px;
      font-size: 20px;
    }

    button.remove{
    /* float: right; */
    padding: 0 5px;
    margin: 0 3px;
    width: 25px;
    }

    .hedr{
        padding: 0;
    }

    .bdy{
        padding:5px 10px;
    }

    </style>

@endsection

    @section('contentSever')
            <div class="row" style="padding-top:30px">
                <div class="col-md-4">
                        <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Server Name" aria-label="Server Name" id="inputServers">
                                <div class="input-group-append">
                                  <button class="btn btn-outline-secondary" type="button" id="BtnAddToList">Add To list</button>
                                </div>
                        </div>

                </div>
                <div class="col-md-8" style="padding-left: 100px;">
                {{-- @if( Auth::user()->permission == "admin" || Auth::user()->permission == "manager" )
                    <button  class="btn btn-primary float-right" id="idRefreshList">Mettre ajour List</button>
                @endif --}}
                    <h4 style="padding-left:30px">Manage PMTAs</h4>

                </div>

            </div>

            <div class="row" style="padding-top:5px">
            {{-- <div class="row" style="padding-top:20px"> --}}
                <div class="col-md-2 listServers">

                    <div class="list-group">

                        {{-- @foreach ( $servers as $key => $item )
                            <a class="list-group-item" data-id="{{$item->id}}" data-name="{{$item->name}}" >
                                {{$item->name}}
                            </a>
                        @endforeach --}}

                    </div>
                </div>
                <div class="col-md-7">
                        <div class="entait" style="height:600px;padding:10px; overflow: auto; overflow-x: hidden;">
                            <ul class="clearfix">
                                <li class="toggerDetail" id="btnsTog" data-toggle="opened">  <i class="fas fa-folder-open fa-lg"></i> </li>
                                <li id='removeItems'> <i class="fas fa-trash fa-lg"></i> </li>
                                <li> <h5 >Resultat</h5></li>

                            </ul>

                            <hr style=" margin-top: 10px;  margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-12 ResultDiv">
                                <div class="accordion" id="accordionExample">
                                </div>
                            </div>
                        </div>
                        </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="top">

                        List Choosed <i class="fas fa-folder-minus myTooltips" data-toggle="tooltip" data-placement="top" title="Remove all servers choosed"></i>
                        </div>
                    </div>
                    <div class="row servers-choosed">


                    </div>
                    <div class="row">
                        <div class="col-md-12 btnss" style="padding-left: 0px;" >
                                {{-- id="ipsSelect" --}}
                                <select class="form-control btnAction"  style="margin-top:5px;">
                                    <option >Choose One ...</option>

                                    <optgroup label=" Queue "  >
                                        <option value=1 > resume queue </option>
                                        <option value=2 > pause queue</option>
                                        <option value=3 > Delete </option>
                                    </optgroup>

                                    <optgroup label="Services PMTAs"  >
                                        {{-- <option value=4 > Stop </option>
                                        <option value=5 > Start </option> --}}
                                        <option value=6 > Restart </option>
                                        <option value=7 > Reload </option>
                                        <option value=8 > schedule </option>
                                    </optgroup>

                                    <optgroup  label="Others" >
                                        <option value=9 > reset counters </option>
                                    </optgroup>

                                </select>
                                <div class="input-group mb-3" style="margin-top:5px;" >
                                    <select class="form-control" id="ipsSelect"  disabled>
                                    </select>
                                    <div class="input-group-append">
                                      <button class="btn btn-outline-secondary" type="button" id="idAddIsp" data-toggle="modal" data-target="#psdf" disabled>Add ISP</button>
                                    </div>
                                  </div>


                        </div>
                    </div>

                    {{-- <div class="row" style="padding-top:30px"> --}}
                    <div class="row" style="padding-top:10px">
                        <div class="col-md-12">

                                {{-- <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Reason: </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control"placeholder="Reason ..." name="reason" rows="3" id='reason'></textarea>
                                    </div>
                                </div> --}}

                                {{-- <div class="form-group row">
                                    <label for="exampleInputEmail1"> Reason : </label>
                                    {{-- <textarea class="form-control" placeholder="Reason ..." name="reason" rows="3" id='reason'></textarea> (--)}}
                                    <input type="text" class="form-control" placeholder="Reason ..."  name="reason" id='reason' >
                                    <small id="emailHelp" class="form-text text-muted">Put your Reason up for more clarification !!</small>
                                  </div> --}}
                                {{--
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Commend Line" name="commend" id='cmdLine' >
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary executeCmdLine" > Execute </button>
                                    </div>
                                 </div> --}}

                                <div class="form-group">
                                    <button type="button" class="btn btn-primary float-right" style="margin:0 auto;font-size:12px;padding:3px 5px;" data-toggle="modal" data-target=".bd-example-modal-lg" title=" Available commands"> <i class="fas fa-terminal"></i> </button>
                                    <label for="exampleInputEmail1"> Command : </label>
                                    <textarea class="form-control" placeholder="Command ... " name="commend" id='cmdLine' rows="3"></textarea>
                                    <small id="emailHelp" class="form-text text-muted">Put your Command up !!</small>
                                </div>

                                <button type="button" class="btn btn-secondary executeCmdLine">Execute</button>
                        </div>
                    </div>
                </div>
            </div>

    {{-- </div> --}}

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 1000px;">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">   Available commands: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
<pre style="font-size: 11px;">
clear dnscache domainname
check mfrom [--tcp] [--dumppackets] mailfrom ip
check pra [--tcp] [--dumppackets] pra ip
delete [--dsn] [--accounting] [--queue=domain[/vmta]] [--orig=addr] [--rcpt=addr] [--jobId=id] [--envId=id]
deregister [--user=name] [--local-only] [--retain-unloaded]
disable source [--reenable-after=interval] ip domain[/vmta]
enable source ip domain[/vmta]
pause queue domain[/vmta]
register [--user=name] [--label=name] [--webmon-ip=ip] [--webmon-port=number] [--pmtamc-port=number] [--reuse-label] pmtamc-hostname
reload
reset counters
reset status
resolve [--tcp] [--connect] [--dumppackets] [--interactive] [--source=[host,]ip] domainname
resume queue domain[/vmta]
rotate acct [file]
rotate log
schedule domain[/vmta]
set queue --mode={normal|backoff} domain[/vmta]
show disabled sources [domain[/vmta]]
show domains [--vmta=name] [--connected={yes|no}] [--maxitems=n] [--errors] [--sort={name|rcpt|size}] [name]
show jobs [--maxitems=n]
show license
show queues [--connected={yes|no}] [--paused={yes|no}] [--mode={normal|backoff}] [--maxitems=n] [--errors] [--sort={name|rcpt|size}] [domain[/vmta]]
show registration
show status
show settings domain[/vmta]
show topdomains [--vmta=name] [--connected={yes|no}] [--maxitems=n] [--errors]
show topqueues [--connected={yes|no}] [--paused={yes|no}] [--mode={normal|backoff}] [--maxitems=n] [--errors] [domain[/vmta]]
show version
show vmtas [--maxitems=n]
trace [--log-data] [--log-resolution] [--to-log] domain[/vmta]
</pre>

{{-- list [--queue=domain[/vmta]] [--orig=addr] [--rcpt=addr] [--jobId=id] [--envId=id] [--maxitems=n] [--pause] --}}

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
              </div>
          </div>
        </div>
      </div>

      {{-- <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#psdf">
    Launch demo modal
  </button> --}}

  <!-- Modal -->
  <div class="modal fade" id="psdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add ISP</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body apsModal">

            <form>
                <div class="form-group">
                  <label for="exampleInputEmail1">Asp :</label>
                  <input type="email" class="form-control" id="nameIsp" aria-describedby="emailHelp" placeholder="Enter Isp">
                  <small id="emailHelp" class="form-text text-muted">Please put Isp Name !!</small>
                </div>

                <button type="button" class="btn btn-primary" id='saveIsp'>Add ISP</button>

            </form>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        </div>
      </div>
    </div>
  </div>



@endsection

@section('scriptes');
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>



<script>


    $( document ).ready(function() {
        func();
        getAsps();
    });


    function func(){

        $.post( '/server/user/all' , {} , function(data){
            $('div.listServers div.list-group').empty();
            $.each(data , function (index , value) {
                $('div.listServers div.list-group').append('<a class="list-group-item" data-id="'+value.id+'" data-name="'
                    +value.name+'" > '+value.name+' <button class="btn btn-danger float-right remove" type="button" data-toggle="tooltip"'
                    +' title="Remove Server from My list" data-id="'+value.id+'" >x</button> </a>');
            })
        } );
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("div.listServers div.list-group").on('click' , "button.remove" , function(){

        $.post( '/server/user/desasosiete' , {id :$(this).data("id") } , function(data){
            func();
        });
    })



    $("div.listServers div.list-group").on("click" , "a.list-group-item" , function(){

        if( !$(this).hasClass("list-group-item-info") ) {
            $('.servers-choosed').append("<kbd class='serv'  data-id='"+$(this).data("id")+"' data-name='"+$(this).data("name")+"'>"+$(this).data("name")+" <a style='color:red;' onclick='deselectServer(this , this.parentElement)'>x</a></kbd>");
            $(this).addClass("list-group-item-info");
        }else{
            let id =  $(this).data("id") ;
            $.each( $('.servers-choosed').children() , function(index , value){
                if( id == value.dataset.id ){
                    value.remove();
                }
            } );
            $(this).removeClass("list-group-item-info");
        }

    });


    function deselectServer ( elmnt , prnt ){
        $.each( $('div.listServers div.list-group').children() , function(index , value){
            if(value.dataset.id == prnt.dataset.id ){
                value.classList.remove("list-group-item-info");
                prnt.remove();
            }
        });
    }

    $(".executeCmdLine").click(function(){
        var r = confirm("Are you sure!!");
        if (r == true) {

            let arr = [];
            $.each( $("div.servers-choosed").children() , function(index , value){
                arr.push( {"name":value.dataset.name , "id" : value.dataset.id});
            })

            // let reason  = $('#reason').val();
            let reason  = "";
            let cmdline = $('#cmdLine').val();
            if (cmdline.match(/list/gm) != null ){
                alert(" We can't execute this command !! ")
            }else{
                if( arr.length != 0)
                    // console.log(arr)

                    $.each( arr , function( index , value ){
                        // console.log( { name : value , reason : reason , cmdline : cmdline })
                        $.post( '/server/execute' , { name : value.name , id:value.id ,  reason : reason , cmdline : cmdline } , function( data ){
                            // console.log(data)
                            printResult(data)
                            $('#cmdLine').val("");
                            $('#reason').val("");
                        });
                    })
                else
                    alert("There no server selected to execute command . please select servers !!")
        }

        }
    })


     $(".toggerDetail").click(function() {

         var defs = document.getElementById("btnsTog") ;

        if( defs.dataset.toggle== "opened" ){

            $(this).attr( "data-toggle" , "closed" )
            $(this).find("i").removeClass("fa-folder-open").addClass("fa-folder");

            $("div.ResultDiv .accordion").children().find("div.collapse").addClass("show", 1000);



        } else {

            $(this).attr( "data-toggle" , "opened")
            $(this).find("i").removeClass("fa-folder").addClass("fa-folder-open");
            $("div.ResultDiv .accordion").children().find("div.collapse").removeClass("show" , 1000);

        }

        });


    $(".myTooltips").click(function(){
        removeTrunch()
    });

    $("#removeItems").click(function(){
        $('div.ResultDiv div.accordion').empty();
    });

    function removeTrunch(){
        $.each($("div.listServers div.list-group").children() , function(index,value){
            $(this).removeClass("list-group-item-info");
        });

        $('div.ResultDiv div.accordion').empty();
        $('div.servers-choosed').empty();
    }


    function executeCmd(action){
        let arr = [];
        let reason  = "";
        $.each( $("div.servers-choosed").children() , function(index , value){
            arr.push( value.dataset.name);
        })

        if( arr.length != 0){
            $(".accordion").empty();

        $.each( arr , function (index , value){
            // console.log({ name : value , reason : reason ,  action : action })
            $.post( '/server/action' ,{ name : value , reason : reason ,  action : action } , function(data){
                console.log(data)
                    printResult(data)
                $('#reason').val("");
             });

        } );
    }

    }



function printResult( data ){

    // console.log(data)

    let index =  $("div.ResultDiv .accordion").children().length + 1;
    if(data.CommendResult != "ErrServer"){
        let afch = data.CommendResult.replace(/<(?:.|\n)*?>/gm, '');
                $("div.ResultDiv .accordion").append(`<div class="card">
                    <div class="card-header" id="heading`+index+`"  style="padding-bottom: 0px; padding-top: 0px;">
                        <span class='float-right' style="height: 32px;padding: 5px 0;">`+afch.substring( 0, 50 )+` ... </span>
                        <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse`+index+`" aria-expanded="false" aria-controls="collapse`+index+`">
                         `+data.serverName+`
                        </button>
                    </h5>


                 </div>
                <div id="collapse`+index+`" class="collapse" aria-labelledby="heading`+index+`" data-parent="#accordionExample">
                    <div class="card-body">
                        `+data.CommendResult+`
                    </div>
                 </div>
                 </div>`)
                }else{
                    let afch = data.CommendResult.replace(/<(?:.|\n)*?>/gm, '');
                 $("div.ResultDiv .accordion").append(`<div class="card">
                    <div class="card-header  bg-warning" id="heading`+index+`" style="padding-bottom: 0px; padding-top: 0px;">
                        <span class='float-right' style="height: 32px;padding: 5px 0;">`+afch.substring( 0, 50 )+` ... </span>
                        <h5 class="mb-0">
                        <button class="btn btn-link collapsed text-dark" type="button" data-toggle="collapse" data-target="#collapse`+index+`" aria-expanded="false" aria-controls="collapse`+index+`">
                         `+data.serverName+`
                        </button>
                    </h5>
                 </div>
                <div id="collapse`+index+`" class="collapse" aria-labelledby="heading`+index+`" data-parent="#accordionExample">
                    <div class="card-body">
                            <div class="alert alert-warning" role="alert">
                                 check server !
                            </div>
                    </div>
                 </div>
                 </div>`)

                }
        }

    $('#idRefreshList').click(function(){


        $.post( '/update/list/server' , function(data){
            console.log(data)
        });
    })



    $("#inputServers").keyup(function () {
            let filter = $(this).val().toUpperCase();
            // console.log(filter)
            let childes = $(".listServers div.list-group").children();

            for (let i = 0; i < childes.length; i++) {
                if (childes[i].innerText.toUpperCase().indexOf(filter) > -1)
                    childes[i].style.display = "";
                else
                    childes[i].style.display = "none";
            }

    });

    $("#BtnAddToList").click(function(){

        var filter = $("#inputServers").val();
        if(filter == '') alert('Add server name please!!');
        else{

            $.post('/server/add' , { serverName:filter } , function(data){
            if( data.erreur )
                alert(data.msg)
            else
                func();
         })
        }
    })

    $(".btnAction").change(function(){

        if ( $(this).val() > 0 &&  $(this).val() < 4 ){
            $("#ipsSelect").removeAttr("disabled");
            $("#idAddIsp").removeAttr("disabled");
        }else{
            $("#ipsSelect").attr("disabled", true);
            $("#idAddIsp").attr("disabled", true);
        }

        $('#cmdLine').val( cmds() );

    })

    $("#ipsSelect").change(function(){
        $('#cmdLine').val( cmds() );
    })


    function cmds(){

        var command ;

        var pert = '';

        if (  $(".btnAction").val() > 0 &&  $(".btnAction").val() < 4 ){
            pert = $("#ipsSelect").val()
        }


        switch ( parseInt( $(".btnAction").val() ) ){

            case 1 :
                // resume queue
                command = "resume queue";
                break;
            case 2 :
                // pause queue
                command = "pause queue";
                break;
            case 3 :
                // Delete
                command = "delete --queue=";
                break;
            case 4 :
                command = "stop";
                // Stop
                break;
            case 5 :
                // Start
                command = "start";
                break;
            case 6 :
                // Restart
                command = "restart";
                break;
            case 7 :
                // Reload
                command = "reload";
                break;
            case 8 :
                // schedule
                command = "schedule */*";
                break;
            case 9 :
                command = "reset counters";
                // reset counters
                break;
            default:
                alert("Please choose one command !! ");
                break;
        }


        return command +" "+ pert;

    }

    $("#saveIsp").click(function(){
        $.post('/isp/add'  ,  {name : $("#nameIsp").val() } , function(data){
            $("#nameIsp").val("")
            if( !data.erreur ){
                $('.apsModal').append("<div class='alert alert-success' role='alert' style='margin-top:10px'> "+data.msg+"</div>")
                getAsps();
            }
            else
                $('.apsModal').append("<div class='alert alert-danger' role='alert' style='margin-top:10px'> "+data.msg+"</div>")
        });

    });

    function getAsps(){
        $.post('/isp/list'   , function(data){
            $('#ipsSelect').empty();
            $('#ipsSelect').append("<option>*/*</option>")
            $.each(data , function ( index , value){
                $('#ipsSelect').append("<option>"+ value.name +"</option>")
            })
        });
    }

    </script>
@endsection

