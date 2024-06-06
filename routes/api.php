<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUser\AdminMessageController;
use App\Http\Controllers\AdminUser\AdminOrderAssignController;
use App\Http\Controllers\AdminUser\AdminOrderRequestController;
use App\Http\Controllers\AdminUser\AdminTaskController;
use App\Http\Controllers\AdminUser\CSServiceController;
use App\Http\Controllers\AdminUser\NotaryOrderPaymentController;
use App\Http\Controllers\AdminUser\TranslatedDocumentsController;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CsPaymentModelController;
use App\Http\Controllers\MainNotaryServiceCategoryController;
use App\Http\Controllers\NotaryDocumentsController;
use App\Http\Controllers\NotaryOrderRequestController;
use App\Http\Controllers\OrderRequests;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubNotaryServiceCategoryController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\LegalAdviceController;
use App\Http\Controllers\LGServicesController;
use App\Http\Controllers\SMSModelController;
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
Route::middleware('authToken')->post('get-cs-orders', [CSServiceController::class, 'getCSServiceOrderList']);
Route::middleware('authToken')->post('get-tr-task-list', [AdminTaskController::class, 'getAllTranslateTaskList']);
Route::middleware('authToken')->post('get-ns-task-list', [AdminTaskController::class, 'getNotaryTaskList']);
Route::middleware('authToken')->post('get-cs-task-list', [CSServiceController::class, 'getCSTaskList']);

Route::middleware('authToken')->post('get-complete-tr-task-list', [AdminTaskController::class, 'getCompleteAllTranslateTaskList']);
Route::middleware('authToken')->post('get-complete-ns-task-list', [AdminTaskController::class, 'getCompleteNotaryTaskList']);
Route::middleware('authToken')->post('get-complete-cs-task-list', [CSServiceController::class, 'getCompleteCSTaskList']);
Route::middleware('authToken')->post('update-cs-order-Adminstatus', [CSServiceController::class, 'updateCompleteCSTaskList']);

Route::middleware('authToken')->post('get-order-info-by-invoice', [AdminOrderRequestController::class, 'getOrderDetailsByInvoice']); 

Route::middleware('authToken')->post('upload-translated-docs', [TranslatedDocumentsController::class, 'submitTranslatedDocumentsForOrder']);
Route::middleware('authToken')->post('get-doc-list-by-order', [TranslatedDocumentsController::class, 'getUploadedDocumentsByOrder']);
Route::middleware('authToken')->post('get-tr-order-docs-by-oid', [AdminOrderRequestController::class, 'getTranslateOrderDocuments']);
Route::middleware('authToken')->post('send-admin-message', [AdminMessageController::class, 'sendAdminMessageToClient']);
Route::middleware('authToken')->post('get-order-message-list', [AdminMessageController::class, 'getMessageList']);

Route::middleware('authToken')->post('get-pending-order-requests', [OrderRequests::class, 'getOrderRequestList']);
Route::middleware('authToken')->post('get-service-info-by-id', [ServiceController::class, 'getServiceInfoById']);
Route::middleware('authToken')->post('update-service-by-id', [ServiceController::class, 'updateServiceInfoById']);

Route::middleware('authToken')->post('delete-doc', [TranslatedDocumentsController::class, 'removeUploadedDocumentById']);
Route::middleware('authToken')->post('get-order-info', [AdminOrderRequestController::class, 'getOrderInfo']);
Route::middleware('authToken')->post('update-payment-status', [OrderRequests::class, 'updateOrderPaymentStatus']);
Route::middleware('authToken')->post('get-clients', [ClientController::class, 'getAllClientList']);
Route::middleware('authToken')->post('update-order-status', [AdminOrderRequestController::class, 'updateOrderStatusByInvoice']);

Route::middleware('authToken')->post('get-ns-order-payment-info', [NotaryOrderPaymentController::class, 'getNotaryOrderPaymentInfo']);                                       
Route::middleware('authToken')->post('get-cs-order-payment-info', [CsPaymentModelController::class, 'getCSOrderPaymentInfo']);                                       
Route::middleware('authToken')->post('get-notary-order-by-invoice', [NotaryOrderPaymentController::class, 'getOrderInfoByInvoice']);
Route::middleware('authToken')->post('add-payment-log', [NotaryOrderPaymentController::class, 'setPaymentInforByInvoice']);
Route::middleware('authToken')->post('add-cs-pay-log', [CsPaymentModelController::class, 'addPaymentLog']);

Route::middleware('authToken')->post('submit-notary-documents', [NotaryDocumentsController::class, 'submitNotaryDocumentsForOrder']);
Route::middleware('authToken')->post('get-notary-documents', [NotaryDocumentsController::class, 'getUploadedDocumentsByOrder']);
Route::middleware('authToken')->post('update-ns-order-by-admin', [NotaryOrderPaymentController::class, 'updateOrderStatusByInvoice']);

Route::middleware('authToken')->post('get-ns-order-request-list', [NotaryOrderRequestController::class, 'getAllPendingNotaryOrderList']);
Route::middleware('authToken')->post('get-cs-order-info-by-invoice', [CSServiceController::class, 'getOrderInfoByInvoice']);
Route::middleware('authToken')->post('update-main-notary-category', [MainNotaryServiceCategoryController::class, 'updateMainNotaryCategory']);
Route::middleware('authToken')->post('update-sub-ns-category', [SubNotaryServiceCategoryController::class, 'updateSubCategoryById']);
Route::middleware('authToken')->post('get-sub-ns-category-list', [SubNotaryServiceCategoryController::class, 'getAllSubNotaryCategoryList']);
Route::middleware('authToken')->post('get-sa-dashboard-data', [SuperAdminController::class, 'getDashboardCounts']);

Route::middleware('authToken')->post('Remove-documents', [NotaryDocumentsController::class, 'RemoveNotaryDocumentsForOrder']);

Route::middleware('authToken')->post('send-order-complete-sms', SMSModelController::class, 'sendOrderCompleteNotificationSMS');

Route::middleware('authToken')->post('get-lg-orders', [LegalAdviceController::class, 'getLegalRequest']);
Route::middleware('authToken')->post('assign-lg-order', [LegalAdviceController::class, 'AssignLegalRequest']);
Route::middleware('authToken')->post('get-lg-Task', [LegalAdviceController::class, 'getLegalTask']);
Route::middleware('authToken')->post('get-admin-Lmessage', [LegalAdviceController::class, 'getadminLmessage']);
Route::middleware('authToken')->post('send-admin-Lmessage', [LegalAdviceController::class, 'sendadminLmessage']);
Route::middleware('authToken')->post('get-lgODoc-List', [LegalAdviceController::class, 'getLegalDocs']);
Route::middleware('authToken')->post('get-lgDoc-List', [LegalAdviceController::class, 'getLegalFDocs']);
Route::middleware('authToken')->post('view-lgDoc', [LegalAdviceController::class, 'viewLegalDocs']);
Route::middleware('authToken')->post('get-lg-Complete', [LegalAdviceController::class, 'getLegalComplete']);