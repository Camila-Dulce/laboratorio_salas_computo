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
    // Rutas para Ingresos
    $router->get('/ingresos', 'IngresoController@index');
    $router->get('/ingresos/{id}', 'IngresoController@show');
    $router->post('/ingresos', 'IngresoController@store');
    $router->put('/ingresos/{id}', 'IngresoController@update');
    $router->delete('/ingresos/{id}', 'IngresoController@destroy');
    $router->get('/ingresos/rango-fechas', 'IngresoController@getByDateRange');
    $router->get('/ingresos/filtrar', 'IngresoController@filter');

    // Nuevas rutas para Horario de Salas
    $router->get('/horario-salas', 'HorarioSalaController@index');
    $router->post('/horario-salas', 'HorarioSalaController@store');
});
