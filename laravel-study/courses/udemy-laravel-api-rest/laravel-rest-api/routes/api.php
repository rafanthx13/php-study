<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Pelo Laravel o acesso de tudo aqui é com /api/ na frente da url

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function(Request $request){

	$response = new \Illuminate\Http\Response(json_encode(['msg' => 'Minha primeira resposta de API']));
	$response->header('Content-Type', 'application/json');

	return $response;
});


Route::namespace('App\\Http\\Controllers\\Api')->group(function(){

    // Product Routes
    Route::prefix('products')->group(function(){
        Route::get('/', 'ProductController@index');
        Route::get('/{id}', 'ProductController@show');
        Route::put('/{id}', 'ProductController@update');
        Route::post('/', 'ProductController@save');
        Route::patch('/', 'ProductController@update');
        Route::delete('/{id}', 'ProductController@delete');
    });

    // User Routes : CRUD laravel
    Route::resource('/users', 'UserController');

});


/*
OBS:
// o prefix poe para começar com 'producst a url ; 'namespace é já para ir par ao namesspace API
// No laravel 8 (versâo superior, é necessiar por todo o namesape)


Route::get('/products', function() {
    return \App\Models\Product::all();
});

Route::namespace('Api')->group(function(){
    Route::get('/productss', 'ProductController@index');
});
*/
