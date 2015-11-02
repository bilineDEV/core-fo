<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']   = 'officer';
$route['officer/(:num)']       = "officer/index/$1";
$route['group/(:num)']         = "group/index/$1";
//$route['404_override']       = 'home/error404';

$route['api/officer/login']                    	         = "officer/api/login";
$route['api/login/officer']                              = "officer/api/login";
$route['api/client/get/(:num)']                          = "client/api/get_byofficer/$1";
$route['api/client/officer/(:num)']                      = "client/api/get_byofficer/$1";
$route['api/client/group/(:num)']                        = "client/api/get_bygroup/$1";
$route['api/client/detail/(:num)']                       = "client/api/detail/$1";
$route['api/group/officer/(:num)']                       = "group/api/get/$1";

$route['api/([a-zA-Z_-]+)/([a-zA-Z_-]+)']			           = "$1/api/$2";
$route['api/([a-zA-Z_-]+)/([a-zA-Z_-]+)/(:any)']	       = "$1/api/$2/$3";
$route['api/([a-zA-Z_-]+)/([a-zA-Z_-]+)/(:any)/(:any)']  = "$1/api/$2/$4";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
