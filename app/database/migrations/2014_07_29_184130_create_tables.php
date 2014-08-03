<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration {

/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		#create users table
		Schema::create('users',function($table){
			$table->increments('id');
			$table->string('email')->unique();
			$table->boolean('remember_token');
			$table->string('password');
			$table->timestamps();
		});

		Schema::create('workouts', function($table){
			$table->increments('id');
			$table->timestamps();
			$table->string('workout_name');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::create('exercises', function($table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 64);
		});

		Schema::create('exercise_workout', function($table){
			$table->integer('exercise_id')->unsigned();
			$table->integer('workout_id')->unsigned();
			$table->foreign('exercise_id')->references('id')->on('exercises');
			$table->foreign('workout_id')->references('id')->on('workouts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
		Schema::drop('workouts');
		Schema::drop('tags');
		Schema::drop('book_tag');
	}

}
