<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>EMFI</title>
		<!-- Bootstrap -->
		<link href="{{ asset('themes/frontend/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('themes/frontend/css/owl.carousel.min.css') }}" rel="stylesheet">
		<link href="{{ asset('themes/frontend/css/owl.theme.default.min.css') }}" rel="stylesheet">
		<link href="{{ asset('themes/frontend/css/fonts.css') }}" rel="stylesheet">
		<link href="{{ asset('themes/frontend/css/style.css') }}" rel="stylesheet">
		<link href="{{ asset('themes/frontend/css/responsive.css') }}" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700" rel="stylesheet">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
            @include('includes.header')
            @yield('content')
            @include('includes.footer')
	</body>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="{{ asset('themes/frontend/js/jquery.min.js') }}"></script>
        <!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="{{ asset('themes/frontend/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('themes/frontend/js/owl.carousel.min.js') }}"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="{{ asset('themes/frontend/js/parallax.min.js') }}"></script>
        <script src="{{ asset('themes/frontend/js/home.js') }}"></script>
        <script src="{{ asset('themes/frontend/js/app.js') }}"></script>
        @yield('scripts')
</html>	