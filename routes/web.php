<?php

/** @var App\Core\Router $router */

// Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/api/auth/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Initial redirection or common access
$router->get('/', 'DashboardController@index');
$router->get('/dashboard', 'DashboardController@index');

// Role-based Routes
$router->get('/admin/dashboard', 'DashboardController@index');
$router->get('/staff/dashboard', 'DashboardController@index');

$router->get('/admin/staff', 'StaffController@index');

$router->get('/admin/projects', 'ProjectController@index');
$router->get('/staff/projects', 'ProjectController@index');

$router->get('/admin/tasks', 'TaskController@index');
$router->get('/staff/tasks', 'TaskController@index');

$router->get('/admin/kpi', 'KPIController@index');
$router->get('/admin/kpi/staff-report', 'KPIController@staffReport');

$router->get('/admin/leaves', 'LeaveController@adminIndex');
$router->get('/staff/leaves', 'LeaveController@staffIndex');

$router->get('/admin/profile', 'ProfileController@index');
$router->get('/staff/profile', 'ProfileController@index');
