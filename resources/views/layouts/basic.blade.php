<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>@section('title') Dust @show</title>
	<script src="{{ asset('js/app.js') }}" defer></script>
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
	<div id="app">
		@yield('content')
	</div>
</body>
</html>