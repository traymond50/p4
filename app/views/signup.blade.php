@extends('_master')

@section('title')
	Sign Up
@stop

@section('content')


	<h1>Sign up</h1>
	<p>Already a member.  <a href='/login'>Click here to Login</a>

	{{ Form::open(array('url' => '/')) }}

		Email<br>
		{{ Form::email('email') }}
		<span class="errors">{{ $errors->first('email')}}</span>
		<br><br>

		Password<br>
		{{ Form::password('password') }}
		<span class="errors">{{ $errors->first('password')}}</span>
		<br><br>

		{{ Form::submit('Submit') }}

	{{ Form::close() }}

@stop