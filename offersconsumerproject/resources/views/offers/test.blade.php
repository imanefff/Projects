@extends('layouts.temp')

@section('content')
    {{-- @dd($raw) --}}
    <input type="text" id="keyImage" />
    <button id="cls">click me</button>

    <img    id="img" alt="img"/>
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

        $("#cls").click(function(){
            keys = $("#keyImage").val();
           // 5937\16371
        $.post( "/test2",{ sids : '5937', emailids : '16371' , keysr : keys }, function( data ) {
          console.log(data);
          $("#img").attr( 'src','data:image/jpeg;base64,' + data );
         });
        });
    });

    </script>
@endsection
