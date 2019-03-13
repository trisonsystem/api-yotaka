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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] 	= 'StockController';
$route['test'] 					= 'StockController/testapi';

$route['infolang']              = 'LanguageController/infoLanguage';
$route['lang/(:any)']              = 'LanguageController/$1';

{
	$route['product/read_product']    	= 'ProductController/readProduct';
	$route['product/save_product']     	= 'ProductController/saveProduct';
	$route['product/readedit_product']	= 'ProductController/readEditProduct';
	$route['product/del_product']		= 'ProductController/delProduct';
}

{
	$route['stock/read_stock']    	= 'StockController/readStock';
	$route['stock/save_stock']    	= 'StockController/saveStock';
	$route['stock/readedit_stock']	= 'StockController/readEditStock';
	$route['stock/del_stock']		= 'StockController/delStock';
}

{
	$route['producttype/read_producttype']  		= 'ProducttypeController/read_producttype';
}

$route['master/(:any)']  			= 'MasterController/$1';
$route['division/(:any)']  			= 'Division/DivisionController/$1';
$route['department/(:any)']  		= 'Department/DepartmentController/$1';
$route['position/(:any)']  			= 'Position/PositionController/$1';
$route['employee/(:any)']  			= 'Employee/EmployeeController/$1';
$route['employeestatus/(:any)']  	= 'Employee/EmployeestatusController/$1';
$route['hotel/(:any)']  			= 'Hotel/HotelController/$1';
$route['hotelstatus/(:any)']  		= 'Hotel/HotelstatusController/$1';
$route['language/(:any)']  			= 'Language/LanguageController/$1';
$route['login/(:any)']  			= 'LoginController/$1';
$route['room/(:any)']  				= 'Room/RoomController/$1';
$route['roomtype/(:any)']  			= 'Room/RoomtypeController/$1';
$route['roomitem/(:any)']  			= 'Room/RoomitemController/$1';
$route['customer/(:any)']  			= 'Customer/CustomerController/$1';
$route['promotion/(:any)']  		= 'Promotion/PromotionController/$1';
$route['book/(:any)']  				= 'Book/BookController/$1';


// $route['producttype/(:any)']  		= 'Product/ProducttypeController/$1';

$route['404_override'] = '';
$route[] = FALSE;
