<?php

## Facades
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

## Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DeliveryClickController;
use App\Http\Controllers\IncomingMessageController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

## Admin Controllers
use App\Http\Controllers\admin\UserController as AdminUserController;
use App\Http\Controllers\admin\CronRequestController as AdminCronRequestController;
use App\Http\Controllers\admin\BrandController as AdminBrandController;
use App\Http\Controllers\admin\SettingController as AdminSettingController;

// Route::get('test_message', [UserController::class, 'test_message']);
// Route::get('test', [CampaignController::class, 'test']);

Route::get('run-command', function () {
    Artisan::call('pinpoint:sendsmsmessages');
    exit("Command executed successfully");
});

## Admin Routes
Route::match(['get', 'post'], 'admin', [AdminUserController::class, 'login']);
Route::get('admin', [AdminUserController::class, 'login']);
Route::post('admin', [AdminUserController::class, 'checkUserlogin']);

Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {

    ## Manage Cron Requests
    Route::get('cron-requests/index', [AdminCronRequestController::class, 'index']);
    Route::post('cron-requests/getCronRequestsDatatable', [AdminCronRequestController::class, 'getCronRequestsDatatable']);

    ## Manage Brands
    Route::get('brands/index', [AdminBrandController::class, 'index']);
    Route::post('brands/getBrandsDatatable', [AdminBrandController::class, 'getBrandsDatatable']);
    Route::match(['get', 'post'], 'brands/brand/{brand_id?}', [AdminBrandController::class, 'brand']);
    Route::post('brands/deleteBrand', [AdminBrandController::class, 'deleteBrand']);
    Route::post('brands/checkBrandTitleExists', [AdminBrandController::class, 'checkBrandTitleExists']);
    Route::get('brands/brand-details/{brand_id}', [AdminBrandController::class, 'brandDetails']);

    ## Manage Users
    Route::get('users/index', [AdminUserController::class, 'index']);
    Route::match(['get', 'post'], 'users/user/{user_id?}', [AdminUserController::class, 'user']);
    Route::post('users/deleteUser', [AdminUserController::class, 'deleteUser']);
    Route::post('users/getUsersDatatable', [AdminUserController::class, 'getUsersDatatable']);
    Route::post('users/checkEmailExists', [AdminUserController::class, 'checkEmailExists']);
    Route::get('dashboard', [AdminUserController::class, 'dashboard']);
    Route::get('users/logout', [AdminUserController::class, 'logout']);
    Route::match(['get', 'post'], 'users/change_password', [AdminUserController::class, 'changePassword']);

    ## Manage Settings
    Route::match(['get', 'post'], 'settings/index', [AdminSettingController::class, 'index']);
    ## Important Instructions
    Route::get('important-instructions', [AdminSettingController::class, 'importantInstructions']);
});

## User Routes
Route::match(['get', 'post'], [UserController::class, 'login']);
Route::group(['middleware' => 'user'], function () {

    Route::get('/', [UserController::class, 'dashboard']);
    Route::get('dashboard', [UserController::class, 'dashboard']);
    Route::get('logout', [UserController::class, 'logout']);
    Route::match(['get', 'post'], 'change_password', [UserController::class, 'changePassword']);
    Route::post('users/changeBrand', [UserController::class, 'changeBrand']);

    Route::group(['prefix' => 'lists'], function () {
        ## Manage Lists
        Route::get('index', [ListController::class, 'index']);
        Route::post('getListsDatatable', [ListController::class, 'getListsDatatable']);
        Route::post('checkListNameExists', [ListController::class, 'checkListNameExists']);
        Route::match(['get', 'post'], 'list/{list_id?}', [ListController::class, 'modify_list']);
        Route::post('deleteList', [ListController::class, 'deleteList']);
        ## Manage List Numbers
        Route::get('manage_list_numbers/{encrypted_list_id}', [ListController::class, 'manage_list_numbers']);
        Route::post('getListNumbersDatatable',  [ListController::class, 'getListNumbersDatatable']);
        Route::post('checkPhoneNumberExistsInList', [ListController::class, 'checkPhoneNumberExistsInList']);
        Route::post('deleteListPhoneNumber', [ListController::class, 'deleteListPhoneNumber']);
        Route::post('uploadExcelFile', [ListController::class, 'uploadExcelFile']);
        Route::match(['get', 'post'], 'list_number/{list_hash}/{list_number_hash?}', [ListController::class, 'list_number']);
    });

    ## Manage Campaigns
    Route::group(['prefix' => 'campaigns'], function () {
        Route::get('index', [CampaignController::class, 'index']);
        Route::get('duplicate_campaign/{campaign_hash}', [CampaignController::class, 'duplicateCampaign']);
        Route::post('getCampaignsDatatable', [CampaignController::class, 'getCampaignsDatatable']);
        Route::post('deleteCampaign', [CampaignController::class, 'deleteCampaign']);
        Route::post('checkCampaignNameExists', [CampaignController::class, 'checkCampaignNameExists']);
        Route::match(['get', 'post'], 'campaign/{campaign_hash?}', [CampaignController::class, 'campaign']);
        Route::match(['get', 'post'], 'test_campaign/{campaign_hash}', [CampaignController::class, 'test_campaign']);
        Route::match(['get', 'post'], 'live_campaign/{campaign_hash}', [CampaignController::class, 'live_campaign']);
    });

    ## Manage Deliveries
    Route::group(['prefix' => 'deliveries'], function () {
        Route::get('index', [DeliveryController::class, 'index']);
        Route::post('getDeliveriesDatatable',  [DeliveryController::class, 'getDeliveriesDatatable']);
        Route::get('delivery_details/{delivery_hash}', [DeliveryController::class, 'delivery_details']);
        Route::post('deleteDelivery', [DeliveryController::class, 'deleteDelivery']);
    });

    ## Manage Delivery Clicks
    Route::group(['prefix' => 'delivery_clicks'], function () {
        Route::get('index', [DeliveryClickController::class, 'index']);
        Route::post('getDeliveryClicksDatatable', [DeliveryClickController::class, 'getDeliveryClicksDatatable']);
        Route::post('deleteDeliveryClick', [DeliveryClickController::class, 'deleteDeliveryClick']);
    });

    ## Incoming Messages Listing
    Route::group(['prefix' => 'incoming-messages'], function () {
        Route::get('index', [IncomingMessageController::class, 'index']);
        Route::post('getIncomingMessagesDatatable', [IncomingMessageController::class, 'getIncomingMessagesDatatable']);
        Route::get('incoming-message-detail/{incoming_message_id}', [IncomingMessageController::class, 'viewMessageDetail']);
    });
});

Route::get('testing_whatsapp', [CampaignController::class, 'testing_whatsapp']);
Route::get('logs', [LogViewerController::class, 'index']);
