<?php

use App\Http\Controllers\BranchCardPaymentMethodController;
use App\Http\Controllers\BranchCreditsController;
use App\Http\Controllers\BranchStripeController;
use App\Http\Controllers\BranchSubscriptionController;
use App\Http\Controllers\CreditBundlesController;
use App\Http\Controllers\CurrentBranchController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\OnMarketSaleAudiencesController;
use App\Http\Controllers\PasswordResetLinkController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'authenticate']);

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);

Route::post('/reset-password', [NewPasswordController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'show']);

    Route::get('/current-branch', [CurrentBranchController::class, 'show']);
    Route::put('/current-branch', [CurrentBranchController::class, 'update']);

    Route::get('/branches/{branch}/stripe-token', [BranchStripeController::class, 'getToken']);
    Route::put('/branches/{branch}/card-payment-method', [BranchCardPaymentMethodController::class, 'update']);
    Route::delete('/branches/{branch}/card-payment-method', [BranchCardPaymentMethodController::class, 'destroy']);

    Route::post('/branches/{branch}/subscription', [BranchSubscriptionController::class, 'store']);
    Route::put('/branches/{branch}/subscription', [BranchSubscriptionController::class, 'update']);
    Route::delete('/branches/{branch}/subscription', [BranchSubscriptionController::class, 'destroy']);

    Route::post('/branches/{branch}/credits', [BranchCreditsController::class, 'store']);

    // Credit Bundles
    Route::get('/credit-bundles', [CreditBundlesController::class, 'index']);

    // Audiences
    Route::get('/audiences/on-market-sale', [OnMarketSaleAudiencesController::class, 'index']);
    // Route::get('/audiences/on-market-rent/{trigger}', [OnMarketRentAudiencesController::class, 'index']);
    // Route::get('/audiences/off-market/{trigger}', [OffMarketAudiencesController::class, 'index']);
});
