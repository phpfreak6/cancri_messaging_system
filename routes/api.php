<?php

## Facades
use Illuminate\Support\Facades\Route;

## Controllers
use App\Http\Controllers\DeliveryClickController;
use App\Http\Controllers\WebHookController;

Route::post('setDeliveryReport', [DeliveryClickController::class, 'setDeliveryReport']);

Route::post('webhooks/smsMessageStatusHook', [WebHookController::class, 'smsMessageStatusHook']);
Route::post('webhooks/whatsappMessageStatusHook', [WebHookController::class, 'whatsappMessageStatusHook']);
Route::post('webhooks/receiveMessageWebhook', [WebHookController::class, 'receiveMessageWebhook']);
