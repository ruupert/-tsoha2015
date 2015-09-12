<?php

  class TimetableController extends BaseController{

    public static function index(){
    	   	  View::make('timetable-list.html');
    }
    public static function show(){
          View::make('timetable-show.html');

    }
    public static function edit(){
          View::make('timetable-edit.html');

    }
    public static function add(){
          View::make('timetable-new.html');

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
