<?php
/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // Rutas estáticas primero
    $router->get('/ingresos/rango-fechas', 'IngresoController@getByDateRange');
    $router->get('/ingresos/filtrar', 'IngresoController@filter');

    // Rutas dinámicas con patrones específicos
    $router->get('/ingresos', 'IngresoController@index');
    $router->get('/ingresos/{id:[0-9]+}', 'IngresoController@show'); // Solo coincide con IDs numéricos
    $router->post('/ingresos', 'IngresoController@store');
    $router->put('/ingresos/{id:[0-9]+}', 'IngresoController@update'); // Solo coincide con IDs numéricos
    $router->delete('/ingresos/{id:[0-9]+}', 'IngresoController@destroy'); // Solo coincide con IDs numéricos

    // Nuevas rutas para Horario de Salas
    $router->get('/horario-salas', 'HorarioSalaController@index');
    $router->post('/horario-salas', 'HorarioSalaController@store');
});
