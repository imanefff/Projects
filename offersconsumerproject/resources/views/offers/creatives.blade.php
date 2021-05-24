@extends('layouts.temp')

@section('css')
<style>
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

 #iframe {
	min-height:700px;
    height: -webkit-fill-available;
	display:inline-block;
	width:100%;
    border-radius: 5px;
}

form#myForm {
    padding: 50px 50px 20px 0;
}

button#sub {
    float: right;
}

#example > tbody > tr > td:nth-child(5),td:nth-child(6){
    text-align: center;
}

</style>
 <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">

@endsection

@section('content')

<div class="contents">


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
    {{-- @dd($creatives); --}}
    <h1> Create Creatives of Sid : {{ $creatives[0]["sid"] }}</h1>
    <hr>
    <table id="example" class="display" width="100%"></table>

        <form id="myForm"  action="/creativesChoosen" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" id="sub" disabled>Next</button>
        </form>
</div>



<div class="show hideFrame">
        <p class="hd"> <i class="fas fa-stroopwafel fa-spin fa-3x"></i></p>
        <div class = "b hide">
                <iframe id='iframe' frameBorder="0" scrolling="no" onload="resizeIframe(this)"></iframe>
        </div>


    </div>
@endsection


@section('scriptes')
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

@isset($creatives)


        @php

        echo '<script>';
            echo "var offerId = $offerId ;" ;
            echo "var sid = ".App\Offer::find($offerId)->sid.";\n ";
            echo "var apiType = '".App\Offer::find($offerId)->advertiser->api_type."';\n ";
            $js_array = json_encode($creatives);
            echo "var dataSets = ". $js_array . ";\n";
            echo '</script>';
        @endphp
@endisset

<script>

$(document).ready(function(){
    var creativesChoosed = [];
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
    // console.log(dataSets);
    // console.log(offerId);
      var dataSet = [] ;
    $.each( dataSets , function( index, value ) {
        dataSet[index] = [ index+1 , value["creativeID"],value["description"] ,value['inside'] ];
    });

//    console.log(dataSet);

   var table = $('#example').DataTable( {
        data: dataSet,
        columns: [

            { title: "ID" },
            { title: "creativeID" },
            { title: "Name" },
            { title: "show" , visible : false },
            { title: "Show Creative",
            'defaultContent': '<button class="btn btn-info toShow">Show !</button>'
             },/*
             { title: "Choose Creative",
                "defaultContent": '<input type="button" value="Choose" class="btn btn-secondary toChoose" />'
             },*/
             {  title : "Choose Creative" ,
                "data": null,
                render:function(data, type, row)
                {
                    if(data[3] == "in" || data[3] == "send")
                        return 'Choosed';
                    else if(data[3] == "out")
                        return '<input type="button" value="Choose" class="btn btn-secondary toChoose" data-case="choosable" />';
                },
                "targets": -1
            },
             {
            title : "Delete" ,
            "data": null,
            render:function(data, type, row)
            {
                if(data[3] == "in")
                return '<a  class="btn btn-danger todelete">delete !</button>';
                else if(data[3] == "out")
                return '-----';
                else if(data[3] == "send")
                return 'Sent';
            },
            "targets": -1
        }
        ]
    } );

// #example  tbody  tr  > td:nth-child(5)

// $('#example tbody').on( 'click', 'button', function () {
//         var data = table.row( $(this).parents('tr') ).data();
// });

 $('#example tbody').on( 'click', 'button', function () {
    var data = table.row( $(this).parents('tr') ).data();
        $(".show").removeClass("hideFrame");
        console.log( { offerId : offerId , creativeId : data[1] } )
        $.post("/showCreative",{offerId:offerId,creativeId:data[1]},function(result){
            // console.log(result);
            if(result!=null)
                result = result.replace(/<img%20/gi, '<img ');
            update(result);
            $('.hd').hide();
            $(".b").removeClass("hide");
        }).fail(function(err) {
            $('.hd').hide();
               alert("Can't receive this creative ");
        });


   });

    $('#example tbody').on( 'click', 'input',function(){

        var data = table.row( $(this).parents('tr') ).data();
        // console.log(data);
        $(this).toggleClass("btn-success").toggleClass("btn-secondary");


        var exst = findInArrat(creativesChoosed,data[0]);

        if(exst == -1)
            creativesChoosed[ creativesChoosed.length ] = [ data[0] , data[1] ] ;
        else{
            delete  creativesChoosed[ exst ];
            creativesChoosed = creativesChoosed.filter(function(e){return e});
        }

        $( "#myForm" ).empty();
        $( "#myForm" ).append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
        $( "#myForm" ).append('<input type="hidden" name="sid" value="'+sid+'">');
        $( "#myForm" ).append('<input type="hidden" name="offerId" value="'+offerId+'">');

        $.each(creativesChoosed , function(index,value){
            $( "#myForm" ).append( '<input type="hidden" name="creativeIds[]" value="'+value[1]+'" />');
        });

        if(creativesChoosed.length == 0)
            $( "#myForm" ).append( '<button type="submit" class="btn btn-primary" id="sub" disabled>Next</button>');
        else
            $( "#myForm" ).append( '<button type="submit" class="btn btn-primary" id="sub">Next</button>');
        });

        $('#example tbody').on( 'click', 'a',function(){
            var data = table.row( $(this).parents('tr') ).data();

            $.post('/offer/creative/delete',{id:data[1],sid:sid,offerId:offerId},function(data){
                location.reload();
            });
        });


    $(".show").click(function(){
        $('.hd').show();
        $(".b").addClass("hide");
        $(".show").addClass("hideFrame");
    });

});

function showCreative(element){
    // console.log(element);
    var data = element.parentElement.parentElement;
    // console.log(data);
}




    function  update(str){
		var idoc = document.getElementById('iframe').contentWindow.document;
			idoc.open();
			idoc.write(str);
			idoc.close();
	}



    function resizeIframe(obj){
     obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }

    function findInArrat(array,indexToFind){
        for(var i = 0 ; i < array.length ; i++)
            if(array[i][0] == indexToFind) return i;

        return -1;
    }



</script>

@endsection
