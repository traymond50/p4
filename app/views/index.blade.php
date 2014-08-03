@extends('_master')

@section('title')
	Welcome

@stop

@section('content')

	@if(Auth::check())
		<h1>Welcome</h1>	
		<a href='/logout'>Log out {{ Auth::user()->email; }}</a>
		<br>
		<a href='/workout_selector'>Workout Selector</a>
		<br>
		<a href='/workouts'>My Workouts</a>
		<br>
		<a href='/delete_workouts'>Delete Workouts</a>

	@else
		<h1>Follow the links below to Access Site</h1>
		<a href='/'>Sign up</a> or <a href='/login'>Log in</a>
	@endif

	
@stop