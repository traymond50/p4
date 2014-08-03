@extends ('_master')

@section('title')
	Log in
@stop

@section('content')

	<h1>Log in</h1>
	<p>Not a member.  <a href='/'>Click here to Sign up</a>

	{{ Form::open(array('url' => '/login')) }}

		Email<br>
		{{ Form::email('email') }}
		<span class="errors">{{ $errors->first('email')}}</span>
		<br><br>

		Password<br>
		{{ Form::password('password')}}
		<span class="errors">{{ $errors->first('password')}}</span>
		<br><br>

		{{ Form::submit('Submit') }}

	{{ Form::close() }}

@stop