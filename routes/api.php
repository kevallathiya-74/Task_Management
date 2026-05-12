<?php

/** @var App\Core\Router $router */

$router->post('/api/auth/login', 'AuthController@login');
$router->post('/api/auth/logout', 'AuthController@logout');

// Staff Management
$router->get('/api/staff', 'StaffController@list');
$router->post('/api/staff', 'StaffController@create');
$router->post('/api/staff/update', 'StaffController@update');
$router->post('/api/staff/delete', 'StaffController@delete');

// Project Management
$router->get('/api/projects', 'ProjectController@list');
$router->post('/api/projects', 'ProjectController@create');
$router->post('/api/projects/update', 'ProjectController@update');
$router->post('/api/projects/delete', 'ProjectController@delete');

// Task Management
$router->get('/api/tasks', 'TaskController@list');
$router->post('/api/tasks', 'TaskController@create');
$router->post('/api/tasks/update', 'TaskController@update');
$router->post('/api/tasks/update-status', 'TaskController@updateStatus');
$router->post('/api/tasks/delete', 'TaskController@delete');

// Dashboard & Analytics
$router->get('/api/dashboard/charts', 'DashboardController@getChartData');
$router->get('/api/dashboard/priority-tasks', 'DashboardController@getPriorityTasks');
$router->get('/api/dashboard/alerts', 'DashboardController@getAlerts');
$router->post('/api/dashboard/alerts/read', 'DashboardController@markAlertRead');

// Profile & Settings
$router->post('/api/profile/update', 'ProfileController@update');

// KPI Management
$router->get('/api/admin/kpi/daily-record', 'KPIController@getDailyRecord');
$router->get('/api/admin/kpi/monthly-report', 'KPIController@getMonthlyReport');
$router->get('/api/admin/kpi/staff-report-data', 'KPIController@getStaffReportData');
$router->post('/api/admin/kpi/save-daily', 'KPIController@saveDaily');
$router->post('/api/admin/kpi/log-report', 'KPIController@logReport');
