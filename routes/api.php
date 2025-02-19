<?php

use App\Http\Controllers\IntegrationGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/integration/gateway', [IntegrationGatewayController::class, 'gateway']);
Route::post('/webhooks/wema/callback', function (Request $request){
    return [
        'url' => $request->fullUrl(), // Gets the complete URL including query parameters
        'parameters' => $request->all() // Gets all request parameters
    ];
});

Route::post('/webhooks/globus/callback', function (Request $request){
    return [
        'url' => $request->fullUrl(), // Gets the complete URL including query parameters
        'parameters' => $request->all() // Gets all request parameters
    ];
});

Route::post('/webhooks/charges/bank-transfer', function (Request $request){
    return [
        'url' => $request->fullUrl(), // Gets the complete URL including query parameters
        'parameters' => $request->all() // Gets all request parameters
    ];
});
