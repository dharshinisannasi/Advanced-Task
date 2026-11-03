<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

//registration page 
$routes->get('registrationpage', 'registercontroller::index');
$routes->post('insertregistereddata','registercontroller::insertregistereddata');
// $routes->post('checkavailability','Registercontroller::checkavailability');

//loginpage
$routes->get('loginpage', 'logincontroller::index');
$routes->post('getloginuser', 'logincontroller::getloginuser');

//admin registrationpage
$routes->get('adminregistrationpage', 'Adminregistercontroller::index');
$routes->post('insertadmindata', 'Adminregistercontroller::insertadmindata');

//employee managament 
$routes->get('employeemanagementpage', 'employeemanagementcontroller::index');
$routes->post('insertemployeedata','employeemanagementcontroller::insertemployeedata');
$routes->post('selectemployeedata','employeemanagementcontroller::selectemployeedata');
$routes->post('updateemployeedata','employeemanagementcontroller::updateemployeedata');
$routes->post('deleteemployeedata','employeemanagementcontroller::deleteemployeedata');
$routes->post('clearemployeedata','employeemanagementcontroller::clearemployeedata');
$routes->get('viewpage', 'Viewpage::index');
$routes->get('viewpage/(:num)', 'Viewpage::index/$1');
$routes->post('updatephoto/(:num)', 'Viewpage::updatephoto/$1');
$routes->post('changestatus', 'employeemanagementcontroller::changestatus');
$routes->get('getusernames', 'employeemanagementcontroller::getusernames');
$routes->get('getstatusnames', 'employeemanagementcontroller::getstatusnames');
$routes->get('getdate', 'employeemanagementcontroller::getdate');

//product -employee
$routes->get('employeeproductpage','Productemployeecontroller::index');
$routes->post('insertemployeeproductdata','Productemployeecontroller::insertemployeeproductdata');
$routes->post('selectemployeeproductdata','Productemployeecontroller::selectemployeeproductdata');
$routes->get('getproduct', 'Productemployeecontroller::getproduct');
$routes->get('getstatus', 'Productemployeecontroller::getstatus');
$routes->get('getdates', 'Productemployeecontroller::getdates');
$routes->get('getproductcodes', 'Productemployeecontroller::getproductcodes');
$routes->get('getcategory', 'Productemployeecontroller::getcategory');
$routes->post('updateproductdata', 'Productemployeecontroller::updateproductdata');
$routes->post('deleteproductdata', 'Productemployeecontroller::deleteproductdata');
$routes->post('clearproductdata', 'Productemployeecontroller::clearproductdata');
$routes->post('changeandupdatestatus', 'Productemployeecontroller::changeandupdatestatus');

//product - admin

$routes->get('adminproductpage','Productadmincontroller::index');
$routes->post('insertadminproductdata','Productadmincontroller::insertadminproductdata');
$routes->post('selectadminproductdata','Productadmincontroller::selectadminproductdata');
$routes->get('getallproduct', 'Productadmincontroller::getallproduct');
$routes->get('getallstatus', 'Productadmincontroller::getallstatus');
$routes->get('getalldates', 'Productadmincontroller::getalldates');
$routes->get('getallproductcodes', 'Productadmincontroller::getallproductcodes');
$routes->get('getallusers', 'Productadmincontroller::getallusers');
$routes->get('getallcategory', 'Productadmincontroller::getallcategory');
$routes->post('updateallproductdata', 'Productadmincontroller::updateallproductdata');
$routes->post('deleteallproductdata', 'Productadmincontroller::deleteallproductdata');
$routes->post('clearallproductdata', 'Productadmincontroller::clearallproductdata');
$routes->post('changeandupdateallstatus', 'Productadmincontroller::changeandupdateallstatus');
//viewproductpage
$routes->get('viewproductpage', 'Viewproduct::index');
$routes->get('viewproductpage/(:num)', 'Viewproduct::index/$1');
$routes->post('updateproduct/(:num)', 'Viewproduct::updateproduct/$1');

//dashboard
$routes->get('dashboardpage','Dashboard::index');

//logout
$routes->get('logoutpage', 'logout::index');
//userspage
$routes->get('userspage', 'Users::index');
$routes->post('getallusers', 'Users::getallusers');
$routes->post('selectuserdata', 'Users::selectuserdata');

// REST API - Products
$routes->group('api', function ($routes) {
    $routes->get('products', 'Productapi::index');
    $routes->get('products/filter-options', 'Productapi::filterOptions');
    $routes->get('products/(:num)', 'Productapi::singledata/$1');
    $routes->post('products', 'Productapi::insert');

    $routes->post('products/(:num)', 'Productapi::update/$1');

    $routes->put('products/(:num)', 'Productapi::update/$1');
    $routes->delete('products/(:num)', 'Productapi::delete/$1');
});

$routes->options('api/products', static function () {
    return service('response')->setStatusCode(204);
});

$routes->options('api/products/(:num)', static function () {
    return service('response')->setStatusCode(204);
});
$routes->options('api/products/filter-options', static function () {
    return service('response')->setStatusCode(204);
});
//exports
$routes->get(
    'exportemployeeproducts',
    'Productemployeecontroller::exportemployeeproducts'
);

$routes->get(
    'exportadminproducts',
    'Productadmincontroller::exportadminproducts'
);

$routes->get('exportemployee', 'Employeemanagementcontroller::exportemployee');


//profilepage
$routes->get('profilepage', 'Profile::index');
$routes->post('updateprofile', 'Profile::updateprofile');