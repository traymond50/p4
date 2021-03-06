@extends('_master')

@section('title')
	Workouts

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
            		<li><a class="active" href="/workouts">Workouts</a></li>
            		<li><a href="/delete_workouts">Delete Workout</a></li>
            		<li><a href="/logout">Logout</a></li>
          		</ul>
          	</div>
        	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          		<div class="jumbotron workouts_jumbotron">
            		<h1 class="page-header">Workouts</h1>

		<?php
		$id = Auth::user()->id;
		$workouts = Workout::orderBy('workout_name')->where('user_id','=',$id)->get();
	
		echo "<div class='container-fluid'>
    		<div class='row'>
      			<div class='col-md-12'>
        			<div class='panel-group' id='accordion'>";
		
        $count = 1;
		foreach($workouts as $workout)
		{
		echo "<div class='panel panel-default'>	
				<div class='panel-heading'>
					<h4 class='panel-title'>
					<a data-toggle='collapse' data-parent='#accordion' href='#collapse".$count."'>".$workout->workout_name."</a>
					</h4><br>
				</div>";
				
				$selection = $workout->exercises->count();

				echo "<div id='collapse".$count."' class='panel-collapse collapse in'>
					<div class='panel-body'>";
				for($i = $selection; $i > 0 ; $i--)
				{
					echo $workout->exercises->shift()->name."<br>";
				}
				echo "</div></div>";
				echo "<br></div>";
				$count++;
		}
		echo "</div></div></div></div>";
		?>
          		</div>
			</div>
		</div>
    </div>
	
	<script src="js/jquery-2.1.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/collapse.min.js"></script>

	@else
		<h1>Follow the links below to Access Site</h1>
		<a href='/'>Sign up</a> or <a href='/login'>Log in</a>
	@endif

	
@stop