<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>{{ $page_title or 'EMFI'}}</title>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <!-- Bootstrap -->
        <link href="{{ asset('themes/emfi/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/emfi/css/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/emfi/css/owl.theme.default.min.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/emfi/css/fonts.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/emfi/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset("/themes/admin/assets")}}/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset("/themes/admin/assets")}}/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('themes/emfi/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/emfi/css/responsive.css') }}" rel="stylesheet">
        <?php
            // echo '<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700" rel="stylesheet">';
        ?>
        @yield('styles')
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
              <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
    </head>
    <body>                        
        @include('includes.header_new')
        @yield("content")
        @include('includes.footer_new')        
        <script src="{{ asset('themes/emfi/js/jquery.min.js')}}"></script>
        <script src="{{ asset('themes/emfi/js/bootstrap.min.js')}}"></script>
        <script src="{{ asset('themes/emfi/js/owl.carousel.min.js')}}"></script>
        <script type="text/javascript" src="{{ asset('/js/jquery.bootstrap-growl.min.js') }}"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="{{ asset("/themes/admin/assets")}}/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="{{ asset('themes/emfi/js/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>