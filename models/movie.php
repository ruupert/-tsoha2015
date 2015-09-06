<?php 

##### LUONNOSTELUA ###########################
##############################################
## Liian sekava ja liian yksityiskohtainen. ##
## Pitäis olla paljon geneerisempi :(       ##
##############################################

class MovieModel{
   protected $db;
   protected $selectedMovie;
   protected $movieName;
   protected $movieDesc;
   protected $movieImage;
   protected $movieDuration;
   
   protected $movieObj;

   public function __construct() {
   	# init $db;
   }

   public function __destruct() {
   	 # close $db connection   
   }
   
   public function getMovies() {
   # listaus ei tarvitse mitaan konstruktoria.
   # select * from movies
   # return $object

   }

   private function save() {
   	   if (!is_null($selectedMovie)) {
	       # try 
	       # insert into tietokantapossupankkiin
	       # catch stuff
	       # kerro käyttäjälle huonoja uutisia
	   }
   }
   
   private function select($id) {
     $selectedMovie=$id;
     
     # $movieObj = select * from movie where id=$id
     # setMovieFields();     
   }

   private function setMovieFields($name, $desc, $image, $duration) {
   	   setMovieName($name);
	   setMovieDescription($desc);
	   setImage($image);
	   setDuration($duration);

   }
    
   protected function setMovieName($name) {
   	 if (isset(selectedMovie)) {
   	   $movieName = $name;
	 }
   }
   protected function setMovieDescription($desc) {
   	 if (isset(selectedMovie)) {
   	   $movieDesc = $desc;
	 }
   }
   protected function setImage($image) {
   	 if (isset(selectedMovie)) {
   	   $movieImage = $image;
         }
   }
   protected function setDuration($duration) {
   	 if (isset(selectedMovie)) {
   	   $movieDuration = $duration;
	 }
   }
   
   private function getMovieName() {
   	   return $movieName;
   }   
   private function getMovieDesc() {
   	   return $movieDesc
   }   
   private function getMovieImage() {
   	   return $movieImage;
   }   
   private function getMovieDuration() {
   	   return $movieDuration;
   }   
   

}