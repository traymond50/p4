@extends('_master')

@section('title')
	Welcome

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
            		<li><a class="active" href="/homepage">Home</a></li>
            		<li><a href="/workout_selector">Workout Selector</a></li>
            		<li><a href="/workouts">Workouts</a></li>
            		<li><a href="/delete_workouts">Delete Workout</a></li>
            		<li><a href="/logout">Logout</a></li>
          		</ul>
          	</div>
        	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          		<div class="jumbotron homepage_jumbotron">
            		<h1 class="page-header">Welcome</h1>
            		<p>blah blah blah</p>
          		</div>
			</div>
		</div>
    </div>

	<!--<script src="js/jquery-2.1.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>-->




	@else
		<h1>Follow the links below to Access Site</h1>
		<a href='/'>Sign up</a> or <a href='/login'>Log in</a>
	@endif

	
@stop
