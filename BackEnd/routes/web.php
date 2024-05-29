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
    
    $router->get('/ingresos/rango-fechas', 'IngresoController@getByDateRange'); // Rango de fechas
    $router->get('/ingresos/filtrar', 'IngresoController@filter'); // Filtrar ingresos

    // Rutas para HorariosSalas
    $router->get('/horarios_salas', 'HorarioSalaController@index');
    $router->get('/horarios_salas/{id}', 'HorarioSalaController@show');
    $router->post('/horarios_salas', 'HorarioSalaController@store');
    $router->put('/horarios_salas/{id}', 'HorarioSalaController@update');
    $router->delete('/horarios_salas/{id}', 'HorarioSalaController@destroy');

    // Rutas para Programas
    $router->get('/programas', 'ProgramaController@index');
    $router->get('/programas/{id}', 'ProgramaController@show');
    $router->post('/programas', 'ProgramaController@store');
    $router->put('/programas/{id}', 'ProgramaController@update');
    $router->delete('/programas/{id}', 'ProgramaController@destroy');

    // Rutas para Responsables
    $router->get('/responsables', 'ResponsableController@index');
    $router->get('/responsables/{id}', 'ResponsableController@show');
    $router->post('/responsables', 'ResponsableController@store');
    $router->put('/responsables/{id}', 'ResponsableController@update');
    $router->delete('/responsables/{id}', 'ResponsableController@destroy');

    // Rutas para Salas
    $router->get('/salas', 'SalaController@index');
    $router->get('/salas/{id}', 'SalaController@show');
    $router->post('/salas', 'SalaController@store');
    $router->put('/salas/{id}', 'SalaController@update');
    $router->delete('/salas/{id}', 'SalaController@destroy');
});
