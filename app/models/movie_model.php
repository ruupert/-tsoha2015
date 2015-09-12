<?php

  class MovieModel extends BaseModel{

    public static function getMovies(){
    	   $res = self::$connection->prepare("select name, description, duration, image from movie;");
    	   $res->execute();
    }
  }
