<?php

use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderApplicationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\UserBankAccountController;
use Illuminate\Support\Facades\Route;

// landing
Route::get('/', [ServiceController::class, 'guestIndex'])->name('home');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

Route::get('services/create', [ServiceController::class, 'create'])
    ->name('services.create');

Route::get('/services/highlight', [App\Http\Controllers\ServiceController::class, 'highlight'])
    ->name('services.highlight');

Route::get('/services/{service:slug}/highlight/pay', [App\Http\Controllers\ServiceController::class, 'showPayHighlight'])
    ->name('services.highlight.showPay');

Route::post('/services/{service:slug}/highlight/pay', [App\Http\Controllers\ServiceController::class, 'payHighlight'])
    ->name('services.highlight.pay');
Route::get('/services/favorites', [ServiceController::class, 'favorites'])
    ->name('services.favorites');
Route::get('/services/nearby', [ServiceController::class, 'nearby'])->name('services.nearby');

Route::get('services/{slug}', [ServiceController::class, 'show'])
    ->name('services.show');
Route::patch('services/{slug}/toggle-status', [AdminServiceController::class, 'toggleStatus'])
    ->name('admin.services.toggleStatus');

Route::prefix('admin')->middleware(['auth', 'can:admin-access'])->group(function () {

    Route::get('services', [AdminServiceController::class, 'index'])->name('admin.services.index');

    Route::get('services/create', [AdminServiceController::class, 'create'])->name('admin.services.create');

    Route::post('services', [AdminServiceController::class, 'store'])->name('admin.services.store');

    Route::get('services/{slug}/edit', [AdminServiceController::class, 'edit'])->name('admin.services.edit');

    Route::put('services/{slug}', [AdminServiceController::class, 'update'])->name('admin.services.update');

    Route::delete('services/{slug}', [AdminServiceController::class, 'destroy'])->name('admin.services.destroy');
});

// cuma untuk seller
Route::middleware(['auth', 'seller'])->group(function () {


    Route::get('/bank-accounts', [UserBankAccountController::class, 'index'])->name('bank-accounts.index');

    Route::get('/bank-accounts/create', [UserBankAccountController::class, 'create'])->name('bank-accounts.create');

    Route::post('/bank-accounts', [UserBankAccountController::class, 'store'])->name('bank-accounts.store');

    Route::get('/bank-accounts/{id}/edit', [UserBankAccountController::class, 'edit'])->name('bank-accounts.edit');

    Route::put('/bank-accounts/{id}', [UserBankAccountController::class, 'update'])->name('bank-accounts.update');

    Route::delete('/bank-accounts/{id}', [UserBankAccountController::class, 'destroy'])->name('bank-accounts.destroy');
});



Route::middleware('auth')->group(function () {

    Route::post('/notifications/read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read');


    Route::patch('/reviews/{service:slug}', [ReviewController::class, 'store'])->name('reviews.store');


    Route::get('/service/apply', [ProviderApplicationController::class, 'create'])->name('service.apply');

    Route::post('/service/apply', [ProviderApplicationController::class, 'store'])->name('service.apply.submit');

    Route::patch('/services/{service:slug}/favorite', [ServiceController::class, 'toggleFavorite'])
        ->name('services.toggleFavorite');

    // Store → simpan service baru
    Route::post('services', [ServiceController::class, 'store'])
        ->name('services.store');

    // Edit → form edit service
    Route::get('services/{slug}/edit', [ServiceController::class, 'edit'])
        ->name('services.edit');

    // Update → simpan perubahan
    Route::put('services/{slug}', [ServiceController::class, 'update'])
        ->name('services.update');


    Route::get('services', [ServiceController::class, 'index'])
        ->name('services.index');

    // Create → form tambah service

    Route::get('/orders/{order}/info-payment', [OrderController::class, 'infoPayment'])->name('orders.infoPayment');

    Route::delete('services/{slug}', [ServiceController::class, 'destroy'])
        ->name('services.destroy');


    Route::get('/orders/create/{service:slug}', [OrderController::class, 'create'])
        ->name('orders.create');


    Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');


    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.myOrders');

    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::patch('/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');

    Route::patch('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');

    Route::patch('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/conversations', [ChatController::class, 'index'])->name('conversations.index');

    Route::get('/conversations/{conversation}', [ChatController::class, 'show'])->name('conversations.show');

    Route::post('/conversations/{conversation}/send', [ChatController::class, 'send'])->name('conversations.send');

    Route::post('/conversations/start', [ChatController::class, 'start'])->name('conversations.start');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/provider/applications', [ProviderApplicationController::class, 'index'])->name('provider.applications');

    Route::get('/provider/applications/{slug}', [ProviderApplicationController::class, 'show'])->name('provider.applications.show');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:admin'])->group(function () {

    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');

    Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

    // untuk suspend

});

Route::middleware(['auth', 'can:admin'])->group(function () {

    Route::resource('categories', CategoryController::class);

    Route::resource('subcategories', SubcategoryController::class);

    Route::get('/admin/provider/applications', [ProviderApplicationController::class, 'adminIndex'])->name('admin.provider.applications');

    Route::get('/admin/provider/applications/{slug}', [ProviderApplicationController::class, 'adminShow'])
        ->name('admin.provider.applications.show');

    Route::post('/admin/provider/applications/{id}/approve', [ProviderApplicationController::class, 'approve'])->name('admin.provider.applications.approve');

    Route::post('/admin/provider/applications/{id}/reject', [ProviderApplicationController::class, 'reject'])->name('admin.provider.applications.reject');
});


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

require __DIR__ . '/auth.php';
