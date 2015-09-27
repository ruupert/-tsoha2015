<?php

$routes->get('/',                          function()        {   HelloWorldController::index();         });
$routes->get('/hiekkalaatikko',            function()        {   HelloWorldController::sandbox();       });
//
//
// MovieController
$routes->get('/movie',                     function()        {   MovieController::index();              });
$routes->get('/movie/new',                 function()        {   MovieController::add();                });
$routes->get('/movie/:id/show',            function($id)     {   MovieController::show($id);            });
$routes->get('/movie/:id/edit',            function($id)     {   MovieController::edit($id);            });
$routes->get('/movie/sandbox',             function()        {   MovieController::sandbox();            });

//$routes->post('/movie/:id/destroy')        function($id)     {   MovieController::destroy($id);         });
//$routes->post('/movier/create')            function($id)     {   MovieController::create();             });
// 
// TheaterController
$routes->get('/theater',                   function()        {   TheaterController::index();            });
$routes->get('/theater/new',               function()        {   TheaterController::add();              });
$routes->get('/theater/:id/show',          function($id)     {   TheaterController::show($id);          });
$routes->get('/theater/:id/edit',          function($id)     {   TheaterController::edit($id);          });
//$routes->post('/theater/:id/destroy')      function($id)     {   TheaterController::destroy($id);       });
//$routes->post('/theater/create')           function($id)     {   TheaterController::create();           });
//
// TimetableController
$routes->get('/timetable',                 function()        {   TimetableController::index();          });
$routes->get('/timetable/new',             function()        {   TimetableController::add();            });
$routes->get('/timetable/:id/show',        function($id)     {   TimetableController::show($id);        });
$routes->get('/timetable/:id/edit',        function($id)     {   TimetableController::edit($id);        });
//$routes->post('/timetable/:id/destroy')    function($id)     {   TimetableController::destroy($id);     });
//$routes->post('/timetable/create')         function($id)     {   TimetableController::create();         });
//
// UserController
$routes->get('/user',                      function()        {   UserController::index();              });
$routes->get('/login',                     function()        {   UserController::login();              });
$routes->post('/logincheck',               function()        {   UserController::login_check();        });
$routes->get('/logout',                    function()        {   UserController::logout();             });
$routes->get('/register',                  function()        {   UserController::register();           });
//
// ReservationController
$routes->get('/reservation',               function()        {   ReservationController::index();        });
$routes->get('/reservation/new',           function()        {   ReservationController::add();          });
$routes->get('/reservation/:id/show',      function($id)     {   ReservationController::show($id);      });
$routes->get('/reservation/:id/edit',      function($id)     {   ReservationController::edit($id);      });
//$routes->post('/reservation/:id/destroy')  function($id)     {   ReservationController::destroy($id);   });
//$routes->post('/reservation/create')       function($id)     {   ReservationController::create();       });


