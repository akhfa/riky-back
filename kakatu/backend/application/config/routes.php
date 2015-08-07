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
|	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['faq/faq_item'] = 'faq_controller/get_faq_item';
$route['faq/recommendation_faq_list'] = 'faq_controller/get_recommendation_faq_list';
$route['faq/top_10_faq_list'] = 'faq_controller/get_top_10_faq_list';
$route['faq/related_faq'] = 'faq_controller/get_related_faq';
$route['faq/add_faq'] = 'faq_controller/add_faq';
$route['faq/add_feedback_detail'] = 'faq_controller/add_feedback_detail';
$route['faq/add_tag'] = 'faq_controller/add_tag';

$route['article/article_item'] = 'article_controller/get_article_item';
$route['article/article_list'] = 'article_controller/get_article_list';
$route['article/related_article'] = 'article_controller/get_related_article';
$route['article/add_article'] = 'article_controller/add_article';
$route['article/add_tag'] = 'article_controller/add_tag';

$route['category/get_category'] = 'category_controller/get_category';
$route['category/category_item'] = 'category_controller/get_category_item';

$route['tag'] = 'tag_controller/get_tag';

$route['search'] = 'search_controller/search_faq';

$route['mining'] = 'PagesController/view';