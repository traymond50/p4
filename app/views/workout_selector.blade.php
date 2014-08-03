@extends('_master')

@section('title')
	Workout Selector

@stop

@section('content')

	@if(Auth::check())
		<h1>Workout Selector</h1>
		<a href='/homepage'>Home</a>	
		<br>
		<a href='/workouts'>My Workouts</a>
		<br>
		<a href='/delete_workouts'>Delete Workouts</a>
		<br>
		<a href='/logout'>Log out {{ Auth::user()->email; }}</a>	
		<br><br>

	<?php $exercises = Exercise::all();?>


	{{ Form::open(array('url' => '/workout_selector')) }}
		{{ Form::label('Workout Name', 'Workout Name:') }}
		{{ Form::text('workout') }}

		<br><br>
		@foreach($exercises as $excercise)
			{{ Form::checkbox($excercise->name) }}
			{{ Form::label($excercise->name, $excercise->name) }}
			<br>
		@endforeach 
		<br>
		{{ Form::submit('Submit') }}

	{{ Form::close() }}
	

	

	@else
		<h1>Follow the links below to Access Site</h1>
		<a href='/'>Sign up</a> or <a href='/login'>Log in</a>
	@endif

	
@stop



