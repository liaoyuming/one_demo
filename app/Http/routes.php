    <?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {

});

Route::get('home/send_captcha/{type}/{mobile}', 'HomeController@send_captcha');


Route::get('home', 'HomeController@index');
Route::post('home/register', 'HomeController@register');

// Route::controllers([
//     'auth' => 'Auth\AuthController',
//     'password' => 'Auth\PasswordController',
// ]);
