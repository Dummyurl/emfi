<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>{{ $page_title or 'EMFI'}}</title>
        <!-- Bootstrap -->
        <link href="{{ asset('themes/frontend/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/frontend/css/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/frontend/css/owl.theme.default.min.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/frontend/css/fonts.css') }}" rel="stylesheet">
        <link href="{{ asset("/themes/admin/assets")}}/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset("/themes/admin/assets")}}/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('themes/frontend/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('themes/frontend/css/responsive.css') }}" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700" rel="stylesheet">
		@yield('styles')
		<style media="screen">
			#tweetText{
				margin-top: 12px;
                padding-right: 95px;
			}
		</style>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
              <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body data-spy='scroll' data-target='.navbar' >
        @include('includes.header')
        @yield('content')
        @include('includes.footer')
    </body>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="{{ asset('themes/frontend/js/jquery.min.js') }}"></script>
    <!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="{{ asset('themes/frontend/js/bootstrap.min.js') }}"></script>

    @if(\Request::is("analyzer","analyzer/*"))
        <script src="https://www.google.com/jsapi"></script>
    @endif
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="{{ asset('themes/frontend/js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/jquery.bootstrap-growl.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('themes/frontend/js/parallax.min.js') }}"></script>
    <script src="{{ asset("/themes/admin/assets")}}/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('themes/frontend/js/app.js') }}"></script>
    @yield('scripts')
</html>
