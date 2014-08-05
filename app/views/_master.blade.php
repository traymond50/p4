<!DOCTYPE html>
<html>
<head>
	<title>@yield('title','Foobooks')</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel='stylesheet' href='css/bootstrap.min.css' type='text/css'>
	<link rel='stylesheet' href='css/style.css' type='text/css'>

	@yield('head')

</head>

<body>
	
	@if(Session::get('flash_message'))
		<div class='flash_message'>{{Session::get('flash_message') }}</div>
	@endif

	@yield('content')

	@yield('body')

</body>
</html>