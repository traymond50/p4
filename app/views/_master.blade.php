<!DOCTYPE html>
<html>
<head>
	<title>@yield('title','Foobooks')</title>

	<link rel='stylesheet' href='css/bootstrap.min.css' type='text/css'>
	<link rel='stylesheet' href='css/foobooks.css' type='text/css'>

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