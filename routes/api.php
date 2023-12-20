<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUser\AdminOrderAssignController;
use App\Http\Controllers\AdminUser\AdminOrderRequestController;
use App\Http\Controllers\AdminUser\AdminTaskController;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\MainNotaryServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubNotaryServiceCategoryController;
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

Route::middleware('authToken')->post('add-notary-main-category', [MainNotaryServiceCategoryController::class, 'addNewMainCategory']);
Route::middleware('authToken')->post('get-all-main-notary-categories', [MainNotaryServiceCategoryController::class, 'getAllMainNotaryServiceCategoryList']);
Route::middleware('authToken')->post('add-notary-sub-category', [SubNotaryServiceCategoryController::class, 'addNewSubNotaryServiceCategory']);

Route::middleware('authToken')->post('create-admin-user', [AdminController::class, 'createAdminUser']);
Route::middleware('authToken')->post('get-admin-user-list', [AdminController::class, 'getAdminUserList']);
Route::middleware('authToken')->post('get-tr-orders', [AdminOrderRequestController::class, 'getAllTranslationOrderList']);
Route::middleware('authToken')->post('assign-order', [AdminOrderAssignController::class, 'assignOrder']);
Route::middleware('authToken')->post('get-ns-orders', [AdminOrderRequestController::class, 'getNotaruyServiceOrderList']);
Route::middleware('authToken')->post('get-tr-task-list', [AdminTaskController::class, 'getAllTaskList']);
Route::middleware('authToken')->post('get-order-info-by-invoice', [AdminOrderRequestController::class, 'getOrderDetailsByInvoice']);