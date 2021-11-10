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

$router->group(['prefix' => ''], function () use ($router) {
    // Rotas para realizar transações.
    Route::post('transaction', 'TransactionController@made');
});

$router->group(['prefix' => 'users'], function () use ($router) {
    // Rotas para o CRUD do usuário consumidor
    Route::post('create', 'UserController@create');
});

$router->group(['prefix' => 'company'], function () use ($router) {
    // Rotas para o CRUD do usuário lojista
    Route::post('create', 'CompanyController@create');
});