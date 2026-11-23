<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GuestAuthController;
use App\Http\Controllers\GiftPaymentController;
use App\Http\Controllers\PresencaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/informacoes', [HomeController::class, 'informacoes'])->name('informacoes')->middleware(['guest.auth', 'doNotCacheResponse']);
Route::get('/gift-list', [HomeController::class, 'giftList'])->name('gift-list')->middleware('doNotCacheResponse');

// Resultado de pagamento (MP redireciona via back_urls)
Route::get('/gift-payment/success', [GiftPaymentController::class, 'success'])->name('gift-payment.success');
Route::get('/gift-payment/failure', [GiftPaymentController::class, 'failure'])->name('gift-payment.failure');
Route::get('/gift-payment/pending', [GiftPaymentController::class, 'pending'])->name('gift-payment.pending');

Route::post('/guest/login', [GuestAuthController::class, 'login'])->name('guest.login');
Route::post('/guest/logout', [GuestAuthController::class, 'logout'])->name('guest.logout');

Route::middleware(['guest.auth', 'doNotCacheResponse'])->group(function () {
    Route::get('/confirmar-presenca', [PresencaController::class, 'index'])->name('confirmar-presenca');
    Route::post('/confirmar-presenca', [PresencaController::class, 'confirmar'])->name('confirmar-presenca.confirmar');
    Route::post('/confirmar-presenca/van', [PresencaController::class, 'confirmarVan'])->name('confirmar-presenca.van');
});
