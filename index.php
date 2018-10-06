<?php
/**
 * Created by PhpStorm.
 * User: SahamNadeem
 * Date: 06/08/2018
 * Time: 12:30 AM
 */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
session_start();
ob_start();


require 'vendor/altorouter/altorouter/AltoRouter.php';
require './app/Controllers/ApiControllers/LoginController.php';
require './app/Controllers/ApiControllers/UserController.php';
require './app/Controllers/ApiControllers/LocationController.php';
require './app/Controllers/ApiControllers/SellerController.php';
require './app/Controllers/ApiControllers/ImgapiController.php';
require './app/Controllers/ApiControllers/CatagoryController.php';
require './app/Controllers/ApiControllers/ProductController.php';
require './app/Controllers/ApiControllers/ReviewController.php';
require './app/Controllers/ApiControllers/RatingController.php';
require './app/Controllers/HomeController.php';
require './app/Controllers/ImageController.php';
require './app/Controllers/VLoginController.php';
require './libs/connect.php';






$router = new AltoRouter();
$router->setBasePath('');
/*
*
* Front end Routers
*
*/
$router->map('GET','/', 'HomeController#index', 'home');
$router->map('GET','/dashboard/', 'HomeController#dashboard', 'dashboard');
$router->map('POST','/register/', 'VLoginController#setregister', 'set_register');
$router->map('GET','/register/', 'VLoginController#register', 'get_register');
$router->map('POST','/login/', 'VLoginController#setlogin', 'set_login');
$router->map('GET','/login/', 'VLoginController#login', 'get_login');
$router->map('GET','/logout/', 'VLoginController#logout', 'logout');

/*
*
*   API Routers for Authentocation and registration
*/

$router->map('POST','/oauth/register/', 'LoginController#register', 'register');
$router->map('POST','/oauth/', 'LoginController#login', 'login');
$router->map('POST','/oauth/profile/', 'LoginController#profile', 'profile');
$router->map('GET','/oauth/user/', 'LoginController#user', 'user');
$router->map('PUT','/oauth/user/', 'LoginController#update', 'update_current_user');
$router->map('POST','/oauth/callback/', 'LoginController#refresh', 'refresh_current_token');
$router->map('POST','/oauth/password/reset/', 'LoginController#reset', 'password_reset');

/*
*
*   API Routers for handling User Data
*/

$router->map('GET','/users/', 'UserController#users', 'get_users');
$router->map('GET','/users/[i:id]/', 'UserController#user', 'get_user');
$router->map('PUT','/users/[i:id]/', 'UserController#update', 'update_users');
$router->map('DELETE','/users/[i:id]/', 'UserController#delete', 'delete_users');

/*
*
*   API Routers for handling User Location and updating it
*/

$router->map('POST','/users/', 'UserController#create', 'create_users');//c
$router->map('PUT','/users/location/', 'LocationController#userloc', 'add_user_loc');//c
$router->map('GET','/users/location/', 'LocationController#getuserloc', 'get_user_loc');//c
$router->map('GET','/users/location/[i:id]/', 'LocationController#getuserlocById', 'user_loc');
$router->map('POST','/seller/location/', 'LocationController#sellerloc', 'seller_loc');//c

/*
*
*   API Routers for handling Seller Information
*/

$router->map('GET','/seller/', 'SellerController#index', 'get_sellers'); //c
$router->map('PUT','/seller/', 'SellerController#update', 'update_sellers');
$router->map('GET','/seller/[i:id]/', 'SellerController#getbyID', 'get_id_seller'); //c
$router->map('POST','/seller/local/', 'SellerController#location', 'get_local_sellers');
$router->map('POST','/seller/register/', 'SellerController#create', 'create_sellers');//c
$router->map('POST','/seller/images/', 'ImgapiController#sellerimg', 'seller_imgs');
$router->map('DELETE','/seller/images/[i:id]/', 'ImgapiController#deleteimg', 'delete_img');
$router->map('DELETE','/seller/[i:id]', 'SellerController#delete', 'delete_sellers');

/*
*
* Image Uploading
*
*/
$router->map('POST','/img/upload/', 'ImageController#imgupload', 'img_upload');


/*
*
* Catagories
*
*/

$router->map('GET','/catagory/', 'CatagoryController#index', 'catagories');
$router->map('POST','/seller/catagory/', 'CatagoryController#CreateSellerCats', 's_catagories');
$router->map('DELETE','/seller/catagory/[i:id]/', 'CatagoryController#deleteCatagory', 's_del_cat');


/*
*
* Catagories
*
*/

$router->map('POST','/seller/product/', 'ProductController#create', 's_product');
$router->map('GET','/seller/catagory/[i:id]/product/', 'ProductController#CatProd', 's_get_prod');
$router->map('GET','/seller/product/', 'ProductController#index', 's_prod');
$router->map('DELETE','/seller/product/[i:id]/', 'ProductController#delete', 's_del_prod');

/*
*
* Creating deleting reviws and ratings 
*
*/
$router->map('POST','/seller/[i:id]/review/', 'ReviewController#create', 'write_review');
$router->map('POST','/seller/[i:id]/rating/', 'RatingController#create', 'rating');
$router->map('GET','/output.[xml|json:format]?', 'RatingController#json', 'json');






// match current request
$match = $router->match();








if ($match === false) {
    echo "<h1>Page Not Found</h1><p>Error 404</p>";
} else {
    list( $controller, $action ) = explode( '#', $match['target'] );
    if ( is_callable(array($controller, $action)) ) {
        call_user_func_array(array($controller,$action), array($match['params']));
    } else {
        // here your routes are wrong.
        // Throw an exception in debug, send a  500 error in production
    }
}
?>
