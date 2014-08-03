<?php

class Exercise extends Eloquent {

	protected $fillable = array('name');

	#Relationship method...
	public function workouts(){

		#Exercise belongs to many Workouts
		return $this->belongsToMany('Workout');
	}

}