<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Offers Consumer</title>
  <link rel="shortcut icon" href="{{ asset('icon/stroopwafel-solid.ico') }}">
  {{-- {{ asset('front/images/index/logo.jpg'); }} --}}
  <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" >
  {{-- <link rel="stylesheet" href="{{asset('plugins/chosen/chosen.css')}}"> --}}

   <style>
     body{
      font-family:  'Roboto','Raleway Thin',"Segoe UI","Helvetica Neue",Arial,sans-serif,
      "Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
     }
    .contents {
       margin-top: 50px;
       margin-bottom: 80px;}
    .this{
        padding: 0 !important;
    }
    .one{
      height: 70px;
    }
    .nopadding{
      padding-right: 0;
      padding-left: 0;
    }
    .colorNav{
      background-color: #272c30;
      width: 16.7%;
    }
    .sidebar-sticky>ul>li>a{
      color :#fff;
      padding: 10px 16px;
    }
    .sidebar-sticky>ul{
       margin-top: 20px;
    }

    .myNav{
      width: 100%;
      padding-left: 0;
      padding-right: 20px;
      margin : auto 0 ;
    }
    ul.myNav > li{
      display:inline !important;
      float: right;
      padding-left: 5px;
      padding-right: 5px;
    }
    ul.myNav > li:last-child{
      float: left;
      padding-top: 5px;
    }
    ul.myNav > li>a{
      color: #fff;
    }

    i{
      margin-right: 5px;
    }
    #name{
      padding: 0 15px ;
      width: 100%;
    }
    .fixNav{
      position: fixed;
    }
    .sidebare{
      /* width: 16.67%; */
      width: 15.8%;
      height: 100%;
      position: fixed;
      z-index: 1000000;
    }

    .cont{
      padding-left: 16.67%;
    }
    .container-fluid{
      padding-left: 0;
      /* padding-right: 0; */
    }

    #myBtn {
  display: none;
  position: fixed;
  bottom: 18px;
  right: 17px;
  z-index: 99;
  border: none;
  outline: none;
  color: #fff;
  cursor: pointer;
  font-size: 18px;
  padding: 12px 15px;
  background-color: #272c30;
  border-radius: 30px;
}
#myBtn>i{
  margin-right: 0;
}

#myBtn:hover {
  background-color: #17a2b8 ;
}


.chosen-single{
    padding: 6px 12px !important;
    height: 38px !important;
}
.chosen-single>div{
  padding: 6px 0 !important;
}



  </style>

@yield('css')


</head>
<body>
<div class="container-fluid">



<!--   <div class="sidebare bg-dark">
      <div class="sides">
             <div class="row">
            <div class="col-md-12 nopadding ">


                <nav class="navbar navbar-dark colorNav one fixNav">
                      <a href="{{ url('/') }}" id="name">
                         <i class="fas fa-stroopwafel fa-lg"></i>
                         {{ config('app.name', 'Laravel') }}</a>
                </nav>

              </div>

            </div>

        <div class="col-md-12 nopadding ">
        <nav class="d-none d-md-block bg-dark sidebar fixNav" style="margin-top: 70px;">

          <div class="sidebar-sticky">
              @include('templates.sidebare')
          </div>
        </nav>

        </div>
      </div>

  </div>
 -->

  <!-- <div class="cont"> -->

  <div class="row">
  <div class="col-md-12">


        <div class="row">
    <div class="col-md-12 nopadding">

        @include('templates.navBar')

    </div>
  </div>



  @yield('contentSever');


	<div class="container">

        @yield('content')
     {{-- @include('templates.content') --}}
      {{-- @include('templates.ss') --}}
    </div>

</div>



</div>
<!-- </div> -->
<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fas fa-arrow-up fa-2x"></i></button>
</div>
{{-- <i class="fas fa-arrow-circle-up fa-3x"></i> --}}

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

@yield('scriptes')
<script>
  // When the user scrolls down 20px from the top of the document, show the button
  window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
          document.getElementById("myBtn").style.display = "block";
      } else {
          document.getElementById("myBtn").style.display = "none";
      }
  }

  // When the user clicks on the button, scroll to the top of the document
  function topFunction() {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
  }
  </script>
  <script>
    $(document).ready(function(){
    $(".dropContent").hide();
    var position = $("#btnDrop").position();
   // console.log($("#btnDrop").outerHeight());

     $(".dropContent").css('top', position.top + $("#btnDrop").outerHeight()+2);

     $(".dropContent").css('left', position.left-$(".dropContent").width()+$("#btnDrop").outerWidth());

         $("#btnDrop").click(function(){
               $(".dropContent").toggle();
              $("#btnDrop i").toggleClass( "fa-chevron-down" ).toggleClass( "fa-chevron-up" );
       });
    });
    </script>
</body>
</html>


