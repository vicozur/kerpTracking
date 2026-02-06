<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'LoginController::index');
$routes->post('login/authentication', 'LoginController::authentication');
$routes->get('login/googleAuth', 'LoginController::googleAuth');
$routes->post('logout', 'LoginController::logout');    // Cierra sesión
$routes->post('changePass', 'LoginController::updatePassword');    // Cambio Password
$routes->get('home', 'InicioController::index');         // Página después del login
// Paginas de configuracion tracking

$routes->get('tracking', 'Administration\TrackingController::index');

$routes->get('tracking/procedureForm/(:num)', 'Administration\ProcedureController::index/$1');
$routes->get('tracking/procedureForm', 'Administration\ProcedureController::index');
$routes->post('tracking/procedureForm/create', 'Administration\ProcedureController::create');
$routes->post('tracking/procedureForm/update/(:num)', 'Administration\ProcedureController::update/$1');



// Registro de user en inicio de session
$routes->get('user', 'User\UserController::index');
$routes->post('user/save', 'User\UserController::save');

$routes->get('procedure', 'Administration\ProcedureController::index');