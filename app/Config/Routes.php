<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

# Route Login Logout
$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
// $routes->get('login/cek', 'Login::cek');
$routes->get('logout', 'Login::logout');

#Route Summary
$routes->get('summary', 'Summary::index');

#Route Temuan Patrol
$routes->get('temuan_patrol/auditor', 'Temuan_patrol::index');
$routes->get('temuan_patrol/auditee', 'Temuan_patrol::auditee');
$routes->get('temuan_patrol/daftar_hadir/(:num)', 'Temuan_patrol::sign/$1');
#Route Schedule Audit Patrol
$routes->get('schedule', 'Schedule::index');
#Route Master data
$routes->get('admin/mdata_user', 'Admin::mdata_user');
$routes->get('admin/mdata_department', 'Admin::mdata_department');

#Route Semua CRUD disini 
$routes->post('sendData', 'CrudController::sendData');
$routes->post('CrudController/setActiveRole', 'CrudController::setActiveRole');

$routes->get('admin/test_upload', 'Admin::test_upload');

#route download file 
$routes->get('/download/file/(:any)', 'DownloadController::file/$1');

#route untuk pdf preview
$routes->get('temuan_patrol/pdf/preview/(:any)', 'Temuan_patrol::preview/$1');


#Route Auth / CrudController
$routes->post('CrudController/authLogin', 'CrudController::authLogin');
$routes->get('testdb', 'testDB::index');


$routes->get('test-api', 'ApiTest::test_api');
$routes->get('send-email', 'EmailController::sendEmail');
$routes->get('ssl', 'EmailController::checkSSL');
$routes->get('cct', 'EmailController::testConnection');



$routes->post('attendance/sign-digital', 'AttendanceController::signDigital');
