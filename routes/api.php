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

// Rotas para o CRUD do usuário consumidor
Route::post('users/create', 'UserController@create');

// Rotas para o CRUD do usuário lojista
Route::post('company/create', 'CompanyController@create');