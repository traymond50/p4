@extends('_master')

@section('title')
	Delete Workouts

@stop

@section('content')

	@if(Auth::check())
		<h1>Delete Workouts</h1>
		<a href='/homepage'>Home</a>	
		<br>
		<a href='/workouts'>My Workouts</a>	
		<br>
		<a href='/logout'>Log out {{ Auth::user()->email; }}</a>	
		<br><br>

	<?php 
		$id = Auth::user()->id;
		$workouts = Workout::orderBy('workout_name')->where('user_id','=',$id)->get();
	?>


	{{ Form::open(array('url' => '/delete_workouts')) }}
		@foreach($workouts as $workout)
			{{ Form::checkbox($workout->workout_name,'1') }}
			{{ Form::label($workout->workout_name, $workout->workout_name) }}
			<br>
		@endforeach 
		<br>
		{{ Form::submit('Delete') }}

	{{ Form::close() }}
	

	

	@else
		<h1>Follow the links below to Access Site</h1>
		<a href='/'>Sign up</a> or <a href='/login'>Log in</a>
	@endif

	
@stop



