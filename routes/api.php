<?php

use App\Http\Controllers\IntegrationGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/integration/gateway', [IntegrationGatewayController::class, 'gateway']);
Route::post('/webhooks/wema/callback', function (Request $request){
    return $request->all();
});

Route::post('/webhooks/globus/callback', function (Request $request){
    return $request->all();
});

Route::post('/webhooks/charges/bank-transfer', function (Request $request){
    return $request->all();
});
