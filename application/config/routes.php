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

//Officer and Her Clients
$route['api/officer/login']                    	         = "officer/api/login";
$route['api/login/officer']                              = "officer/api/login";
$route['api/client/get/(:num)']                          = "client/api/get_byofficer/$1";
$route['api/client/officer/(:num)']                      = "client/api/get_byofficer/$1";
$route['api/client_pembiayaan/officer/(:num)']           = "client/api/get_pembiayaan_byofficer/$1";
//Client List based on group ID
$route['api/client/group/(:num)']                        = "client/api/get_bygroup/$1";
$route['api/client/financing_proposal/group/(:num)']     = "client/api/get_bygroup_proposal/$1";
$route['api/client/financing/proposal/(:num)']           = "client/api/get_bygroup_proposal/$1";
$route['api/clientdetailed/group/(:num)']                = "client/api/get_bygroup_in_detail/$1";
$route['api/client/financing/(:num)']                    = "client/api/financing/$1";
//Client Profile based on Her Account
$route['api/client/detail/(:num)']                       = "client/api/detail/$1"; //For all active clients with pembiayaan status = 1
$route['api/client/simpledetail/(:num)']                 = "client/api/simpledetail/$1"; //For all active clients with any pembiayaan status
$route['api/client/attendance/(:num)']                   = "client/api/attendance/$1";
$route['api/client/balance/(:num)']                      = "client/api/balance/$1";
$route['api/pembiayaan/sector']                          = "pembiayaan/api/sector";
//Client Family & Residential Profile based on Her ID
$route['api/client/residence/(:num)']                    = "client/api/residence/$1";
//$route['api/client/family/(:num)']                     = "client/api/family/$1";
//Clients based on Their Respective Group
$route['api/group/officer/(:num)']                       = "group/api/get/$1";
$route['api/group/all']                                  = "group/api/all";

//DEFAULT API ROUTES
$route['api/([a-zA-Z_-]+)/([a-zA-Z_-]+)']			           = "$1/api/$2";
$route['api/([a-zA-Z_-]+)/([a-zA-Z_-]+)/(:any)']	       = "$1/api/$2/$3";
$route['api/([a-zA-Z_-]+)/([a-zA-Z_-]+)/(:any)/(:any)']  = "$1/api/$2/$4";

//POST API URI
$route['topsheet/save_topsheet']                         = "topsheet/save_topsheet";
$route['pembiayaan/register']                            = "pembiayaan/register";
$route['pembiayaan/entry']                               = "pembiayaan/entry";
//$route['api/topsheet/entry/']                            = "topsheet/api/save_topsheet/";
$route['api/pembiayaan/register']                        = "pembiayaan/api/register";
$route['api/pembiayaan/survey']                          = "pembiayaan/api/survey";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
