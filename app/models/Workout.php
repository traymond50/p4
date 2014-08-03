<?php

class Workout extends Eloquent {

	# Relationship method...
    public function users() {

    	# Workout belongs to User
	    return $this->belongsTo('User');
    }

    # Relationship method...
    public function exercises() {

    	# Workout belong to many exercises
        return $this->belongsToMany('Exercise');
    }

    public function getWorkoutId()
    {
    	return $this->attributes['workout_name'].'_'.$this->attributes['user_id'];
    }
}