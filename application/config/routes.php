<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// ======================== DEFAULT ROUTE ========================
$route['default_controller'] = 'loan_system';
$route['index'] = 'loan_system';

// ======================== USER AUTH ========================
$route['request_login']       = 'loan_system/request_login';
$route['post_login']          = 'loan_system/post_login';
$route['request_signup']      = 'loan_system/request_signup';
$route['post_signup']         = 'loan_system/post_signup';
$route['request_otp']         = 'loan_system/request_otp';
$route['post_otp']            = 'loan_system/post_otp';
$route['request_logout']      = 'loan_system/request_logout';

// ======================== LOAN ========================
$route['request_loan']        = 'loan_system/request_loan'; // ❌ Not defined in controller, consider removing or creating
$route['post_loan']           = 'loan_system/post_loan';
$route['refresh_modal1']      = 'loan_system/refresh_modal1';
$route['refresh_modal2']      = 'loan_system/refresh_modal2';
$route['repayment']           = 'loan_system/repayment';

// ======================== ADMIN ========================
$route['admin']               = 'loan_system/admin';
$route['admin_login']         = 'loan_system/admin_login';
$route['post_admin_login']    = 'loan_system/post_admin_login';
$route['post_admin']          = 'loan_system/post_admin';
$route['request_admin_logout'] = 'loan_system/request_admin_logout';
$route['update_loan_status']  = 'loan_system/update_loan_status'; // ✅ cleaner alias than using controller path

// ======================== SYSTEM ========================
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

