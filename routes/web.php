<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Subscribed;
use App\Http\Middleware\NotSubscribed;
use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\ProfileController;

Route::group(['middleware' => 'guest:admin'], function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Restaurant
    Route::resource('restaurants', RestaurantController::class)->only(['index', 'show']);
    
    // 会社概要ページ
    Route::get('company', [CompanyController::class, 'index'])->name('company.index');
    // 利用規約ページ
    Route::get('terms', [TermController::class, 'index'])->name('terms.index');

    Route::middleware(['auth', 'verified'])->group(function () {
        // User
        Route::resource('user', UserController::class)->only(['index', 'edit', 'update']);

        // レビューページ
        Route::resource('restaurants.reviews', ReviewController::class)->only(['index']);

        // サブスクリプションが未登録の場合
        Route::middleware([NotSubscribed::class])->group(function () {
            Route::get('subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create');
            Route::post('subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
        });

        // サブスクリプションが登録済の場合
        Route::middleware([Subscribed::class])->group(function () {
            Route::get('subscription/edit', [SubscriptionController::class, 'edit'])->name('subscription.edit');
            Route::patch('subscription', [SubscriptionController::class, 'update'])->name('subscription.update');
            Route::get('subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
            Route::delete('subscription', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');

            Route::resource('restaurants.reviews', ReviewController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
            Route::resource('reservations', ReservationController::class)->only(['index', 'destroy']);
            Route::resource('restaurants.reservations', ReservationController::class)->only(['create', 'store']);
            
            // Favorites
            Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
            Route::post('favorites/{restaurant_id}', [FavoriteController::class, 'store'])->name('favorites.store');
            Route::delete('favorites/{restaurant_id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
        });

        Route::middleware(['auth', 'verified'])->group(function () {
          // プロフィール表示ページ
          Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
          
          // プロフィール更新
          Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
          
          // プロフィール削除
          Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
      });
    });
});

require __DIR__.'/auth.php';

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    // HOME
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');

    // User
    Route::resource('users', Admin\UserController::class)->only(['index', 'show']);

    // Restaurant
    Route::resource('restaurants', Admin\RestaurantController::class);

    // Category
    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    // Company
    Route::resource('company', Admin\CompanyController::class)->only(['index', 'edit', 'update']);

    // Term
    Route::resource('terms', Admin\TermController::class)->only(['index', 'edit', 'update']);
});
