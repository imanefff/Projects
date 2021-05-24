<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KN3PJ5V');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-140896614-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){ dataLayer.push(arguments); }
    gtag('js', new Date());

    </script>
    <title>Best Online Deals</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700" >
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" crossorigin="anonymous">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('frontEnd') }}/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Material Design Bootstrap -->
    <link href="{{ asset('frontEnd') }}/css/mdb.min.css" rel="stylesheet"crossorigin="anonymous">
    <!-- Your custom styles (optional) -->
    <link href="{{ asset('frontEnd') }}/css/style.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/fonts/icomoon/style.css">
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/magnific-popup.css">
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('frontEnd') }}/css/aos.css">


    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('frontEnd') }}/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('frontEnd') }}/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('frontEnd') }}/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('frontEnd') }}/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('frontEnd') }}/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('frontEnd') }}/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('frontEnd') }}/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('frontEnd') }}/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontEnd') }}/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('frontEnd') }}/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontEnd') }}/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('frontEnd') }}/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontEnd') }}/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('frontEnd') }}/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    @yield('extra-css')

</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KN3PJ5V"
        height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="site-wrap">


@include('mainLayoutpartitions.navbar')
@include('partitions.feedback')
@include('partitions.newsletter')
@include('partitions.registerpopup')

@yield('content')


@include('mainLayoutpartitions.footer')

</div>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="{{ asset('frontEnd') }}/js/jquery-3.3.1.min.js"></script>
<script src="{{ asset('frontEnd') }}/js/jquery-ui.js"></script>
<script src="{{ asset('frontEnd') }}/js/popper.min.js"></script>
<script src="{{ asset('frontEnd') }}/js/bootstrap.min.js"></script>
<script src="{{ asset('frontEnd') }}/js/owl.carousel.min.js"></script>
<script src="{{ asset('frontEnd') }}/js/jquery.magnific-popup.min.js"></script>
<script src="{{ asset('frontEnd') }}/js/aos.js"></script>
<!-- MDB core JavaScript -->
<script src="{{ asset('frontEnd') }}/js/mdb.min.js" type="text/javascript" ></script>
<script src="{{ asset('frontEnd') }}/js/main.js"></script>

@yield('js')



</body>
</html>
