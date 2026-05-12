<?php

/** @var App\Core\Router $router */

$router->get('/', 'DashboardController@index');
$router->get('/dashboard', 'DashboardController@index');
$router->get('/staff', 'StaffController@index');
$router->get('/projects', 'ProjectController@index');
$router->get('/tasks', 'TaskController@index');
$router->get('/profile', 'ProfileController@index');
$router->get('/login', 'AuthController@showLogin');
$router->get('/logout', 'AuthController@logout');
