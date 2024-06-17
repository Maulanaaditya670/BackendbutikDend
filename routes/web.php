<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // Read (no auth required)
    $router->get('barangs', 'BarangController@index');
    $router->delete('barangs/{id}', 'BarangController@destroy');
    // Create, Update, Delete (auth required)
    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->post('barangs', 'BarangController@store');
        $router->put('barangs/{id}', 'BarangController@update');

    });
});

$router->post('/api/barangs', 'BarangController@store');

$router->group(['prefix' => 'api'], function () use ($router) {
    // Read (no auth required)
    $router->get('kokos', 'KokoController@index');
    $router->delete('kokos/{id}', 'KokoController@destroy');
    // Create, Update, Delete (auth required)
    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->post('kokos', 'KokoController@store');
        $router->put('kokos/{id}', 'KokoController@update');
    });
});

$router->post('/api/kokos', 'KokoController@store');

$router->group(['prefix' => 'api'], function () use ($router) {
    // Read (no auth required)
    $router->get('hijabs', 'HijabController@index');
    $router->delete('hijabs/{id}', 'HijabController@destroy');
    // Create, Update, Delete (auth required)
    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->post('hijabs', 'HijabController@store');
        $router->put('hijabs/{id}', 'HijabController@update');
        
    });
});

$router->post('/api/hijabs', 'HijabController@store');


$router->options('/{any:.*}', function () {
    return response('', 200);
});