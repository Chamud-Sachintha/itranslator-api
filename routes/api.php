<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\ServiceController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('authenticate-user', [Authcontroller::class, 'authenticateUser']);
Route::post('add-client', [Authcontroller::class, 'createNewClient']);

Route::middleware('authToken')->post('get-menu-perm', [Authcontroller::class, 'checkMenuPermission']);
Route::middleware('authToken')->post('add-service', [ServiceController::class, 'addNewService']);
Route::middleware('authToken')->post('get-tr-service-list', [ServiceController::class, 'getTranslateServiceList']);