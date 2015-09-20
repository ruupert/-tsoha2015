<?php

class MovieController extends BaseController{

// 	Plaah!
//	Function __construct() {
//		require_once "app/models/movie_model.php";
//	}

	
	public static function index(){

		require_once "app/models/movie_model.php";
 		$model = new MovieModel();
		
		
   		View::make('movie-list.html', $model->all());
	}
	
	public static function show($id){
		
		require_once "app/models/movie_model.php";
		$model = new MovieModel();

		
		
#		Kint::dump($model->find($id));

		
		parent::generate_links();
		View::make('movie-show.html',$model->find($id));
		
    		
	}	  
	
	public static function edit(){

		// get_user_logged_in()
		// check_logged_in()
		// 
   		View::make('movie-edit.html');
		
	}
	public static function add(){
   		View::make('movie-new.html');
		
	}
	
	public static function create(){

	}
	public static function update(){
		
	}
	public static function destroy(){
		
	}
	
	public static function sandbox(){
		// Testaa koodiasi täällä

		require_once "app/models/movie_model.php";
 		$model = new MovieModel();
		Kint::dump($model);
		
#		View::make('movie-list.html', MovieModel::all());
		
		
	}
}
