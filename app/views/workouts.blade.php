@extends('_master')

@section('title')
	Workouts

@stop

@section('content')

	@if(Auth::check())
		<h1>Workouts</h1>	
		<a href='/homepage'>Home</a>	
		<br>
		<a href='/workout_selector'>Workout Selector</a>
		<br>
		<a href='/delete_workouts'>Delete Workouts</a>
		<br>
		<a href='/logout'>Log out {{ Auth::user()->email; }}</a>	
		<br><br>

		<?php
		$id = Auth::user()->id;
		$workouts = Workout::orderBy('workout_name')->where('user_id','=',$id)->get();
		foreach($workouts as $workout)
		{
			echo $workout->workout_name."<br>";
			$selection = $workout->exercises->count();
			for($i = $selection; $i > 0 ; $i--)
			{
				echo $workout->exercises->shift()->name."<br>";
			}
			echo "<br>";
		}
		?>

	

	@else
		<h1>Follow the links below to Access Site</h1>
		<a href='/'>Sign up</a> or <a href='/login'>Log in</a>
	@endif

	
@stop