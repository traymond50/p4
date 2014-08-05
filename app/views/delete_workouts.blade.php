@extends('_master')

@section('title')
	Delete Workouts

@stop

@section('content')

	@if(Auth::check())
	<nav class="navbar navbar-default" role="navigation">
      		<a class="navbar-brand" href="#">Workout Builder</a>
	</nav>

   <div class="container-fluid">
   		<div class="row">
        	<div class="col-sm-3 col-md-2 sidebar">
          		<ul class="nav nav-sidebar">
            		<li><a href="/homepage">Home</a></li>
            		<li><a href="/workout_selector">Workout Selector</a></li>
            		<li><a href="/workouts">Workouts</a></li>
            		<li><a class="active" href="/delete_workouts">Delete Workout</a></li>
            		<li><a href="/logout">Logout</a></li>
          		</ul>
          	</div>
        	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          		<div class="jumbotron delete_workout_jumbotron">
            		<h1 class="page-header">Delete Workouts</h1>
          		
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
	
				</div>
			</div>
		</div>
    </div>
	

	@else
		<h1>Follow the links below to Access Site</h1>
		<a href='/'>Sign up</a> or <a href='/login'>Log in</a>
	@endif

	
@stop



