<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>
	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">

	<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
	<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
	<!-- Scripts -->
	@vite(['resources/sass/app.scss', 'resources/js/app.js'])
	{{--	<link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">--}}

	{{--	@include('includes.style')--}}
</head>
<body data-sidebar="1colored">
@include('includes.header')
@include('includes.sidebar')

<!-- Loader -->
{{--<div id="preloader">--}}
{{--	<div id="status">--}}
{{--		<div class="spinner"></div>--}}
{{--	</div>--}}
{{--</div>--}}

<!-- Begin page -->
<div id="layout-wrapper" class="py-2">
	<div class="main-content">
		<div class="page-content">
			<div class="container-fluid">
				@yield('content')
			</div>
		</div>
		@include('includes.footer')
	</div>
</div>
@yield('modals')

{{--    <!-- JAVASCRIPT -->--}}
{{--    @include('includes.script')--}}

{{--    @yield('custom_script')--}}

</body>

</html>
