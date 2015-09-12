<?php

  class TheaterController extends BaseController{

    public static function index(){
    	   	  View::make('theater-list.html');
    }

    public static function show(){
          View::make('theater-show.html');

    }
    public static function edit(){
          View::make('theater-edit.html');

    }
    public static function add(){
          View::make('theater-new.html');

    }

    public static function create(){

    }
        public static function update(){

    }
        public static function destroy(){

    }
    



    public static function sandbox(){
      // Testaa koodiasi täällä
      echo 'Hello World!';
    }
  }
