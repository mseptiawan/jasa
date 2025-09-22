<?php

use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderApplicationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubcategoryController;
use Illuminate\Support\Facades\Route;

// ===== Landing & Dashboard =====
Route::get('/', function () {
    return view('welcome');
});
Route::post('/notifications/read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.read');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.myOrders');
Route::get('/services/nearby', [ServiceController::class, 'nearby'])->name('services.nearby');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');      // customer cancel
Route::patch('/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');      // seller accept
Route::patch('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');      // seller reject
Route::patch('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete'); // customer complete
Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/conversations', [ChatController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ChatController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/send', [ChatController::class, 'send'])->name('conversations.send');
    Route::post('/conversations/start', [ChatController::class, 'start'])->name('conversations.start');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/service/apply', [ProviderApplicationController::class, 'create'])->name('service.apply');
    Route::post('/service/apply', [ProviderApplicationController::class, 'store'])->name('service.apply.submit');
    Route::resource('services', ServiceController::class);
    Route::get('/provider/applications', [ProviderApplicationController::class, 'index'])->name('provider.applications');
    Route::get('/provider/applications/{slug}', [ProviderApplicationController::class, 'show'])->name('provider.applications.show');
    // web.php
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');
});

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/admin/provider/applications', [ProviderApplicationController::class, 'adminIndex'])->name('admin.provider.applications');
    Route::get('/admin/provider/applications/{slug}', [ProviderApplicationController::class, 'adminShow'])
        ->name('admin.provider.applications.show');
    Route::post('/admin/provider/applications/{id}/approve', [ProviderApplicationController::class, 'approve'])->name('admin.provider.applications.approve');
    Route::post('/admin/provider/applications/{id}/reject', [ProviderApplicationController::class, 'reject'])->name('admin.provider.applications.reject');
});

require __DIR__ . '/auth.php';
