<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\TicketPurchaseController;
use App\Services\PaystackService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ImageController;

// Test Paystack route (temporary - remove after testing)
Route::get('/test-paystack', function () {
    $paystack = new PaystackService();
    
    $paymentData = [
        'amount' => 50000,
        'email' => 'test@example.com',
        'reference' => 'TEST-' . time(),
        'callback_url' => url('/payment/callback'),
    ];
    
    $response = $paystack->initializeTransaction($paymentData);
    
    if ($response && isset($response['status']) && $response['status']) {
        return redirect($response['data']['authorization_url']);
    }
    
    return "Paystack Error: " . json_encode($response);
});

// Home page - show events
Route::get('/', [HomeController::class, 'index'])->name('home');

// Event details page
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Ticket purchase routes
Route::get('/tickets/{event}/buy', [TicketPurchaseController::class, 'showForm'])->name('tickets.buy');
Route::post('/tickets/{event}/process', [TicketPurchaseController::class, 'processPurchase'])->name('tickets.process');

// Payment routes - No auth required for callback, success, failed (Paystack redirects here)
Route::get('/payment/callback', [PaystackController::class, 'callback'])->name('payment.callback');
Route::get('/payment/success/{order}', [PaystackController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [PaystackController::class, 'failed'])->name('payment.failed');
Route::post('/payment/webhook', [PaystackController::class, 'webhook'])->name('payment.webhook');

// Payment routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/initialize', [PaystackController::class, 'initialize'])->name('payment.initialize');
});

// Dashboard redirect to Filament admin
Route::get('/dashboard', function () {
    return redirect('/admin');
})->middleware(['auth'])->name('dashboard');

// Logout route for non-admin users (redirects to home)
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/images/{path}', [ImageController::class, 'show'])->where('path', '.*');