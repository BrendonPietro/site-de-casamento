<?php

use Illuminate\Http\Request;
use App\Http\Controllers\GiftCheckoutController;
use App\Http\Controllers\MercadoPagoWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/gifts/{gift}/reserve', [GiftCheckoutController::class, 'reserve']);
Route::post('/mercadopago/webhook', [MercadoPagoWebhookController::class, 'handle']);
