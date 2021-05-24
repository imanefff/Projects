<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('title')
  <title>Offers Consumer</title>
  <link rel="shortcut icon" href="{{ asset('icon/stroopwafel-solid.ico') }}">


  <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto" rel="stylesheet">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" >

  <style>
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
     font-size: 10px;
     padding: 13px 15px;
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


   .dropContent{
     position: absolute;
     z-index: 1000000;
     border: 1px solid rgba(0,0,0,.15);
     border-radius: .25rem;
     background-color: #fff;
   }

   .dropContent> ul {
     margin:0;
     list-style: none;
     padding:5px 0;
     min-width: 171px;
    }

   .dropContent > ul >li {
       /* border-bottom: 1px solid #ddd; */
       /* padding : 6px 10px; */
       padding : 0;
   }
   .dropContent > ul >li:hover {
       /* background-color: blueviolet; */
       background-color: #edeeef;
   }
   .dropContent > ul >li>a{
       text-decoration: none;
       color: #212529;
   }

   div#navbarSupportedContent ul li a{
       color: rgba(255, 254, 253, 0.91);
   }

   div#navbarSupportedContent ul li a:hover{
       color : #DA727E !important;
   }

   div#navbarSupportedContent ul li.active a{
       color: #1da6bb;
   }

   .dropContent ul li a {
       color : #111 !important;
   }
   .navbar-nav li {
        border-left: 1px solid #fff;
   }


     </style>


@yield('css')


</head>
<body>
<div class="container-fluid" style="padding:0 15px !important;">

    <div class="row">
        <div class="col-md-12" style="padding:0;">
            @include('templates.navBar')
        </div>
    </div>
    @yield('contentSever')

</div>




	<div class="container">
        @yield('content')
    </div>

    <button  id="myBtn" title="Go to top"><i class="fas fa-arrow-up fa-2x"></i></button>

</div>





<script src="https://code.jquery.com/jquery-3.3.1.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> --}}


@yield('scriptes')

<script>

    $(document).ready(function(){


        $(window).scroll( function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20)
                $("#myBtn").show()
            else
                $("#myBtn").hide()
        });

        $(".dropContent").hide();
        $(".dropContent").css( "top" , 50 );

            $(".btnDrop").click(function(){
                let p =  $(".dropContent").index($(this).parents("div.drop_do").find("div.dropContent") );
                $.each( $(".dropContent") , function(index , value){
                    if( index != p ) {
                        $(this).hide();
                        if ( $(this).parents('div.drop_do').find("button.btnDrop").find('i').hasClass("fa-sort-up") )
                            $(this).parents('div.drop_do').find("button.btnDrop").find('i').toggleClass( "fa-sort-down" ).toggleClass( "fa-sort-up" );
                    }
                })

                $(this).parents("div.drop_do").find("div.dropContent").toggle();

                if( this.dataset.pes == "true")
                    $(this).parents("div.drop_do").find("div.dropContent").css( "left" , ($(this).position().left)-70.58  );

                $(this).find('i').toggleClass( "fa-sort-down" ).toggleClass( "fa-sort-up" );
            });
    });

    $("#myBtn").click( function() {
        $('html, body').animate({scrollTop : 0},700);
    });


    </script>
</body>
</html>


