<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });


  $routes->get('/movie', function() {
    MovieController::index();
  });

  $routes->get('/movie/:id/show', function($id) {
    MovieController::show($id);
  });

  $routes->get('/movie/:id/edit', function($id) {
    MovieController::edit($id);
  });


 
  $routes->get('/theater', function() {
    TheaterController::index();
  });

  $routes->get('/theater/:id/show', function($id) {
    TheaterController::show($id);
  });

  $routes->get('/theater/:id/edit', function($id) {
    TheaterController::edit($id);
  });

  

  $routes->get('/timetable', function() {
    TimetableController::index();
  });

  $routes->get('/timetable/:id/show', function($id) {
    TimetableController::show($id);
  });
  
/* ei tarvii tai ehkä halutaan kieltää tää muokkaaminen
  $routes->get('/timetable/:id/edit', function($id) {
    TimetableController::edit($id);
  });
*/

 $routes->get('/user', function() {
    UserController::index();
  });


  $routes->get('/reservation', function() {
    ReservationController::index();
  });
  $routes->get('/reservation/:id/show', function($id) {
    ReservationController::show($id);
  });

  $routes->get('/reservation/:id/edit', function($id) {
    ReservationController::edit($id);
  });


