<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>@section('title') Dust @show</title>
	<script src="{{ asset('js/app.js') }}" defer></script>
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="">
	<div id="app">
		<div class="d-flex min-vw-100 min-vh-100 justify-content-center align-items-center">
			@yield('content')
		</div>
	</div>
</body>
</html>