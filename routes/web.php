<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\PopupMessageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\PaymentController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\SupportController as ShopSupportController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PurchaseController;
use App\Http\Controllers\User\TransactionController as UserTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ShopProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ShopProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('auth');
Route::get('/order/{order}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

Route::get('/support', [ShopSupportController::class, 'index'])->name('support');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/signup', [RegisteredUserController::class, 'create'])->name('signup');
    Route::post('/signup', [RegisteredUserController::class, 'store'])->name('signup.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::view('/forgot-password', 'auth.forgot-password')->name('forgot-password');

Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::view('/profile', 'user.profile')->name('profile');
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases');
    Route::get('/purchases/{order}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/transactions', [UserTransactionController::class, 'index'])->name('transactions');
    Route::view('/wishlist', 'user.wishlist')->name('wishlist');
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::put('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
    Route::view('/settings', 'user.settings')->name('settings');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'store'])->name('login.store');
    Route::view('/forgot-password', 'admin.forgot-password')->name('forgot-password');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/purchases', [OrderController::class, 'index'])->name('purchases');
        Route::put('/purchases/{order}/status', [OrderController::class, 'updateStatus'])->name('purchases.status');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
        Route::put('/transactions/{payment}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::put('/transactions/{payment}/refund', [TransactionController::class, 'refund'])->name('transactions.refund');
        Route::put('/transactions/{payment}/fail', [TransactionController::class, 'markFailed'])->name('transactions.fail');

        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}/status', [UserController::class, 'toggleStatus'])->name('users.status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/payment-gateway', [PaymentGatewayController::class, 'index'])->name('payment-gateway');
        Route::put('/payment-gateway/{gateway}', [PaymentGatewayController::class, 'update'])->name('payment-gateway.update');

        Route::get('/system-settings', [SettingController::class, 'index'])->name('system-settings');
        Route::put('/system-settings', [SettingController::class, 'update'])->name('system-settings.update');

        Route::get('/support', [SupportController::class, 'index'])->name('support');
        Route::post('/support', [SupportController::class, 'store'])->name('support.store');
        Route::put('/support/{link}', [SupportController::class, 'update'])->name('support.update');
        Route::delete('/support/{link}', [SupportController::class, 'destroy'])->name('support.destroy');

        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        Route::get('/popup-messages', [PopupMessageController::class, 'index'])->name('popup-messages');
        Route::post('/popup-messages', [PopupMessageController::class, 'store'])->name('popup-messages.store');
        Route::put('/popup-messages/{message}', [PopupMessageController::class, 'update'])->name('popup-messages.update');
        Route::delete('/popup-messages/{message}', [PopupMessageController::class, 'destroy'])->name('popup-messages.destroy');

        Route::view('/maintenance', 'admin.maintenance')->name('maintenance');
    });
});
