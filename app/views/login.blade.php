@extends ('_master')

@section('title')
	Log in
@stop

@section('content')


<div class="navbar">
	<div class="top">
		<p class="member">Not a member?</p>
		<a id="login-btn" class='btn btn-toolbar btn-md' role="button" href='/'>Sign Up</a>
	</div>
</div>

<div class="jumbotron">
	<h1>Workout Builder</h1>

	{{ Form::open(array('url' => '/login')) }}

		Email<br>
		{{ Form::email('email') }}
		<span class="errors">{{ $errors->first('email')}}</span>
		<br><br>

		Password<br>
		{{ Form::password('password')}}
		<span class="errors">{{ $errors->first('password')}}</span>
		<br><br>

		<input a class='btn btn-primary btn-lg' role="button" type="submit" value="Login">

	{{ Form::close() }}

</div>

@stop