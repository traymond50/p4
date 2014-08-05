@extends('_master')

@section('title')
	Workout Selector

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
            		<li><a class="active" href="/workout_selector">Workout Selector</a></li>
            		<li><a href="/workouts">Workouts</a></li>
            		<li><a href="/delete_workouts">Delete Workout</a></li>
            		<li><a href="/logout">Logout</a></li>
          		</ul>
          	</div>
        	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          		<div class="jumbotron workout_selector_jumbotron">
            		<h1 class="page-header">Workout Selector</h1>
	
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
				</div>
			</div>
		</div>
    </div>

	@else
		<h1>Follow the links below to Access Site</h1>
		<a href='/'>Sign up</a> or <a href='/login'>Log in</a>
	@endif

	
@stop