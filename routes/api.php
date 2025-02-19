<?php

use App\Http\Controllers\IntegrationGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/integration/gateway', [IntegrationGatewayController::class, 'gateway']);
