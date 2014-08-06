<?php



/*-------------------------------------------------------------------------------------------------
Home page
-------------------------------------------------------------------------------------------------*/

Route::get('/homepage', array('before' => 'auth', function() {

	return View::make('index');				

}));

/*-------------------------------------------------------------------------------------------------
// !get login
-------------------------------------------------------------------------------------------------*/
Route::get('/login',
		array(
				'before' => 'guest',
				function(){
					return View::make('login');
				}
			)
		);

/*-------------------------------------------------------------------------------------------------
// !post login
-------------------------------------------------------------------------------------------------*/
Route::post('/login', array('before' => 'csrf', function() {

	$credentials = Input::only('email', 'password');
/*-----------------------------------------------------------------------
//  validation test
-----------------------------------------------------------------------*/

$data = Input::all();

$rules = array(
	
	'email' => 'required|exists:users,email|email',
	'password' => 'required'
);

$validator = Validator::make($data, $rules);

if($validator->fails()){

	return Redirect::to('/login')->withErrors($validator);	
}

/*-----------------------------------------------------------------------
//  validation test
-----------------------------------------------------------------------*/


	if (Auth::attempt($credentials, $remember = true)) {
		return Redirect::intended('/homepage')->with('flash_message', 'Welcome Back!');
	}
	else {
		return Redirect::to('/login')->with('flash_message', 'Log in failed; please try again.');
	}

	return Redirect::to('/login');

}));


/*-------------------------------------------------------------------------------------------------
// !get logout
-------------------------------------------------------------------------------------------------*/
Route::get('/logout',function(){

	# Log out
	Auth::logout();

	# Send them to the homepage
	return Redirect::to('/');
});


/*-------------------------------------------------------------------------------------------------
// !get signup
-------------------------------------------------------------------------------------------------*/
Route::get('/',
	array(
			'before' => 'guest',
			function() {
				return View::make('signup');
			}
		)
	);


/*-------------------------------------------------------------------------------------------------
// !post signup
-------------------------------------------------------------------------------------------------*/
Route::post('/', array('before' => 'csrf', function() {

	$user = new User;
	$user->email = Input::get('email');
	$user->password = Hash::make(Input::get('password'));

/*-----------------------------------------------------------------------
//  validation test
-----------------------------------------------------------------------*/

$data = Input::all();

$rules = array(
	
	'email' => 'required|unique:users,email|email',
	'password' => 'required|min:5'
);

$validator = Validator::make($data, $rules);

if($validator->fails()){

	return Redirect::to('/')->withErrors($validator);	
}

/*-----------------------------------------------------------------------
//  validation test
-----------------------------------------------------------------------*/
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
// !get workout selector
-------------------------------------------------------------------------------------------------*/
Route::get('/workout_selector', 
	array('before' => 'auth', 
	function() {
	return View::make('/workout_selector');				

}));

/*-------------------------------------------------------------------------------------------------
// !post workout selector
-------------------------------------------------------------------------------------------------*/
Route::post('/workout_selector', array('before' => 'csrf', function() {

	$workout = new Workout;
	$workout->workout_name = Input::get('workout');
	$workout->user_id = Auth::user()->id;
 	$workout->save();
 	$workout->workout_name_id = $workout->getWorkoutId();
 	$workout->save();

 	$currentUser = Auth::user()->id;
 	$check = Workout::where('user_id','=',$currentUser)->get();
 	$count = $check->count();

	$idCount = 0;
	for ($i=0; $i < $count ; $i++) { 
		if ($check->shift()->workout_name_id == $workout->workout_name_id)
			$idCount++;
	}
	

 	if($idCount > 1){
		$workout->delete();
		return Redirect::to('/workout_selector')->with('flash_message_selector', 'Workout Name Must Be Unique');	
 	}

	$exercises = array('push_up','sit_up','bench_press','lunge','jumping_jacks',
				  'squats','jump_rope','pull_up','flutter_kicks','handstand_pushups');

	for($i = 0; $i <10; $i++){
		if (Input::get($exercises[$i]) == 1)
		$workout->exercises()->attach(Exercise::find($i + 1));
	}

	return Redirect::to('/workout_selector')->with('flash_message_selector', 'Workout Added');
}));


/*-------------------------------------------------------------------------------------------------
// !get workouts
-------------------------------------------------------------------------------------------------*/
Route::get('/workouts', array('before' => 'auth', function() 
{

	return View::make('/workouts');
}));

/*-------------------------------------------------------------------------------------------------
// !get delete workouts
-------------------------------------------------------------------------------------------------*/
Route::get('/delete_workouts', array('before' => 'auth', function() 
{

	return View::make('/delete_workouts');
}));

/*-------------------------------------------------------------------------------------------------
// !post delete workouts
-------------------------------------------------------------------------------------------------*/

Route::post('/delete_workouts', array('before' => 'auth', function() 
{
	DB::statement('SET FOREIGN_KEY_CHECKS=0');
	
	$id = Auth::user()->id;
	$selection = Workout::where('user_id','=',$id)->get();
	
	
	// create array for names of all workouts
	$workout_names_db = array();
	$count = $selection->count();

	// populate array
	for ($i=0; $i < $count ; $i++) { 
		$workout_names_db[$i] = $selection->shift()->workout_name;
	}


	$deletion_count = 0;
	for ($i=0; $i < $count ; $i++) 
	{
		if (isset($_POST[$workout_names_db[$i]]))
		{
			$temp = $workout_names_db[$i];
			$delete_workout = Workout::where('workout_name','=', $temp)
							->where('user_id','=',$id)
							->get();
			$delete_workout2 = $delete_workout->first();
			$delete_workout2->exercises()->detach();
			$delete_workout2->delete(); 
			$deletion_count++;
		}
	}
	
	if ($deletion_count == 0) 
	{
		return Redirect::to('/delete_workouts')->with('flash_message_delete', 'No Workout Selected');
	}
	elseif ($deletion_count == 1)
	{
		return Redirect::to('/delete_workouts')->with('flash_message_delete', 'Workout Deleted');
	}
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


$push_up  =Exercise::create(array('name' => 'push_up'));
$sit_up  =Exercise::create(array('name' => 'sit_up'));
$bench_press  =Exercise::create(array('name' => 'bench_press'));
$lunge  =Exercise::create(array('name' => 'lunge'));
$jumping_jacks  =Exercise::create(array('name' => 'jumping_jacks'));
$squats  =Exercise::create(array('name' => 'squats'));
$jump_rope  =Exercise::create(array('name' => 'jump_rope'));
$pull_up  =Exercise::create(array('name' => 'pull_up'));
$flutter_kicks  =Exercise::create(array('name' => 'flutter_kicks'));
$handstand_pushups  =Exercise::create(array('name' => 'handstand_pushups'));



	echo "done";
});

/*-------------------------------------------------------------------------------------------------
// !test
-------------------------------------------------------------------------------------------------*/
Route::get('/test',function(){

	//$customer_options = Customer::select(DB::raw('concat (first_name," ",last_name) as full_name,id'))
	//$test = Workout::update(DB::raw('concat (workout_name, "_", id) as workout_name_id'));
	
	//$test = DB::table('workouts')->where('workout_name','test')->first();
	//var_dump($test->workout_name);


	$temp = Workout::first();
	$test = $temp->getWorkoutId();
	$temp->workout_name_id = $test;
	$temp->save();


	/*
	$temp = Workout::first()->getWorkoutId();
	print_r($temp);
	
	$temp2 = Workout::first();
	$temp2->workout_name_id = $temp;
	$temp2->save();
	
	$test = Workout::orderBy('workout_name')->get();
	$temp = $test->first()->getWorkoutId();
	$test->first()->workout_name_id = $temp;
	print_r($test->first()->workout_name_id);

	
	DB::table('workouts')
		->join('workout_name','id','=','workout_name.id')
		->select('workout_name','id')
		->get();
	*/
		//->where('id', '=', 10)
		//->get();
	echo "<br>run";
});