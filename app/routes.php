<?php

/*-------------------------------------------------------------------------------------------------
// get homepage
-------------------------------------------------------------------------------------------------*/

Route::get('/homepage', array('before' => 'auth', function() {

	return View::make('index');				

}));

/*-------------------------------------------------------------------------------------------------
// get login
-------------------------------------------------------------------------------------------------*/
Route::get('/login', array('before' => 'guest',function(){
					
	return View::make('login');
				
}));

/*-------------------------------------------------------------------------------------------------
// post login
-------------------------------------------------------------------------------------------------*/
Route::post('/login', array('before' => 'csrf', function() {

	$credentials = Input::only('email', 'password');


	/*validation test---------------------------*/
	$data = Input::all();

	$rules = array(
	
		'email' => 'required|exists:users,email|email',
		'password' => 'required'
	);

	$validator = Validator::make($data, $rules);

	if($validator->fails()){

		return Redirect::to('/login')->withErrors($validator);	
	}
	/*validation test---------------------------*/


	if (Auth::attempt($credentials, $remember = true)) {
		return Redirect::intended('/homepage')->with('flash_message', 'Welcome Back!');
	}
	else {
		return Redirect::to('/login')->with('flash_message', 'Log in failed; please try again.');
	}

	return Redirect::to('/login');

}));


/*-------------------------------------------------------------------------------------------------
// get logout
-------------------------------------------------------------------------------------------------*/
Route::get('/logout',function(){

	# Log out
	Auth::logout();

	# Send them to the homepage
	return Redirect::to('/');
});


/*-------------------------------------------------------------------------------------------------
// get signup
-------------------------------------------------------------------------------------------------*/
Route::get('/',array('before' => 'guest',function() {
				
	return View::make('signup');
			
}));


/*-------------------------------------------------------------------------------------------------
// post signup
-------------------------------------------------------------------------------------------------*/
Route::post('/', array('before' => 'csrf', function() {

	$user = new User;
	$user->email = Input::get('email');
	$user->password = Hash::make(Input::get('password'));

	/*validation test---------------------------*/
	$data = Input::all();

	$rules = array(
	
		'email' => 'required|unique:users,email|email',
		'password' => 'required|min:5'
	);

	$validator = Validator::make($data, $rules);

	if($validator->fails()){

		return Redirect::to('/')->withErrors($validator);	
	}
	/*validation test---------------------------*/
	
	try{
		$user->save();
	}
	catch(Exception $e) {
		return Redirect::to('/')
		->with('flash_message','Sign up failed; please try again.')
		->withInput();
	}

	#Log in
	Auth::login($user);

	return Redirect::to('/login')->with('flash_message', 'Welcome');


}));

/*-------------------------------------------------------------------------------------------------
// get workout selector
-------------------------------------------------------------------------------------------------*/
Route::get('/workout_selector', array('before' => 'auth', function() {
	
	return View::make('/workout_selector');				

}));

/*-------------------------------------------------------------------------------------------------
// post workout selector
-------------------------------------------------------------------------------------------------*/
Route::post('/workout_selector', array('before' => 'csrf', function() {

	# create new workout and assign id and name
	$workout = new Workout;
	$workout->workout_name = Input::get('workout');
	$workout->user_id = Auth::user()->id;
 	$workout->save();

 	# concatnate id and name to create unique identifier for workout
 	$workout->workout_name_id = $workout->getWorkoutId();
 	$workout->save();

 	# retrieve all current workouts for user
 	$currentUser = Auth::user()->id;
 	$check = Workout::where('user_id','=',$currentUser)->get();
 	
 	# check to see if new name already exists in existing workouts for user
	$count = $check->count();
	$idCount = 0;
	for ($i=0; $i < $count ; $i++) { 
		if ($check->shift()->workout_name_id == $workout->workout_name_id)
			$idCount++;
	}
	
	# send error message if workout name already exists for user
 	if($idCount > 1){
		$workout->delete();
		return Redirect::to('/workout_selector')->with('flash_message_selector', 'Workout Name Must Be Unique');	
 	}

 	# array of exercise choices
	$exercises = array('Push_Up','Sit_Up','Bench_Press','Lunge','Jumping_Jacks',
				  'Squats','Jump_Rope','Pull_Up','Flutter_Kicks','Handstand_Pushups');

	# determine which excercises have been selected, and save then to the workout
	for($i = 0; $i <10; $i++){
		if (Input::get($exercises[$i]) == 1)
		$workout->exercises()->attach(Exercise::find($i + 1));
	}

	return Redirect::to('/workout_selector')->with('flash_message_selector', 'Workout Added');
}));


/*-------------------------------------------------------------------------------------------------
// get workouts
-------------------------------------------------------------------------------------------------*/
Route::get('/workouts', array('before' => 'auth', function() 
{

	return View::make('/workouts');
}));

/*-------------------------------------------------------------------------------------------------
// get delete workouts
-------------------------------------------------------------------------------------------------*/
Route::get('/delete_workouts', array('before' => 'auth', function() 
{

	return View::make('/delete_workouts');
}));

/*-------------------------------------------------------------------------------------------------
// post delete workouts
-------------------------------------------------------------------------------------------------*/

Route::post('/delete_workouts', array('before' => 'auth', function() 
{
	DB::statement('SET FOREIGN_KEY_CHECKS=0');
	
	# get all workouts for current user
	$id = Auth::user()->id;
	$selection = Workout::where('user_id','=',$id)->get();
	
	# create array to store names of all workouts for current user
	$workout_names_db = array();

	# populate array
	$count = $selection->count();
	for ($i=0; $i < $count ; $i++) { 
		$workout_names_db[$i] = $selection->shift()->workout_name;
	}


	$deletion_count = 0;
	# loop through all of users workouts
	for ($i=0; $i < $count ; $i++) 
	{
		# delete workout if user has selected it for deletion
		if (isset($_POST[$workout_names_db[$i]]))
		{
			$delete_workout = Workout::where('workout_name','=', $workout_names_db[$i])
							->where('user_id','=',$id)
							->get();
			$delete_workout = $delete_workout->first();
			$delete_workout->exercises()->detach();
			$delete_workout->delete(); 
			$deletion_count++;
		}
	}
	
	# if user click delete without selecting any workouts
	if ($deletion_count == 0) 
	{
		return Redirect::to('/delete_workouts')->with('flash_message_delete', 'No Workout Selected');
	}

	# if user click delete with 1 workout selected
	elseif ($deletion_count == 1)
	{
		return Redirect::to('/delete_workouts')->with('flash_message_delete', 'Workout Deleted');
	}

	# if user click delete with more thatn 1 workout selected
	else
	{
		return Redirect::to('/delete_workouts')->with('flash_message_delete', 'Workouts Deleted');
	} 
}));

/*-------------------------------------------------------------------------------------------------
// !seed exercises
-------------------------------------------------------------------------------------------------*/
Route::get('/seed-orm', function(){
	DB::statement('SET FOREIGN_KEY_CHECKS=0');
	DB::statement('TRUNCATE exercises');
	DB::statement('TRUNCATE workouts');
	DB::statement('TRUNCATE exercise_workout');


$push_up  =Exercise::create(array('name' => 'Push_Up'));
$sit_up  =Exercise::create(array('name' => 'Sit_Up'));
$bench_press  =Exercise::create(array('name' => 'Bench_Press'));
$lunge  =Exercise::create(array('name' => 'Lunge'));
$jumping_jacks  =Exercise::create(array('name' => 'Jumping_Jacks'));
$squats  =Exercise::create(array('name' => 'Squats'));
$jump_rope  =Exercise::create(array('name' => 'Jump_Rope'));
$pull_up  =Exercise::create(array('name' => 'Pull_Up'));
$flutter_kicks  =Exercise::create(array('name' => 'Flutter_Kicks'));
$handstand_pushups  =Exercise::create(array('name' => 'Handstand_Pushups'));



	echo "done";
});






# /app/routes.php
Route::get('/debug', function() {

    echo '<pre>';

    echo '<h1>environment.php</h1>';
    $path   = base_path().'/environment.php';

    try {
        $contents = 'Contents: '.File::getRequire($path);
        $exists = 'Yes';
    }
    catch (Exception $e) {
        $exists = 'No. Defaulting to `production`';
        $contents = '';
    }

    echo "Checking for: ".$path.'<br>';
    echo 'Exists: '.$exists.'<br>';
    echo $contents;
    echo '<br>';

    echo '<h1>Environment</h1>';
    echo App::environment().'</h1>';

    echo '<h1>Debugging?</h1>';
    if(Config::get('app.debug')) echo "Yes"; else echo "No";

    echo '<h1>Database Config</h1>';
    print_r(Config::get('database.connections.mysql'));

    echo '<h1>Test Database Connection</h1>';
    try {
        $results = DB::select('SHOW DATABASES;');
        echo '<strong style="background-color:green; padding:5px;">Connection confirmed</strong>';
        echo "<br><br>Your Databases:<br><br>";
        print_r($results);
    } 
    catch (Exception $e) {
        echo '<strong style="background-color:crimson; padding:5px;">Caught exception: ', $e->getMessage(), "</strong>\n";
    }

    echo '</pre>';

});

Route::get('/trigger-error',function() {

    # Class Foobar should not exist, so this should create an error
    $foo = new Foobar;

});