<?php
/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // Rutas estáticas primero
    $router->get('/ingresos/rango-fechas', 'IngresoController@getByDateRange');
    $router->get('/ingresos/filtrar', 'IngresoController@filter');

    // Rutas dinámicas después
    $router->get('/ingresos', 'IngresoController@index');
    $router->get('/ingresos/{id}', 'IngresoController@show');
    $router->post('/ingresos', 'IngresoController@store');
    $router->put('/ingresos/{id}', 'IngresoController@update');
    $router->delete('/ingresos/{id}', 'IngresoController@destroy');

    // Rutas para Horario de Salas
    $router->get('/horario-salas', 'HorarioSalaController@index');
    $router->post('/horario-salas', 'HorarioSalaController@store');
});

