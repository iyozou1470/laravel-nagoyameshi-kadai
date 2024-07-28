<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
// use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController as UR;
// use App\Http\Controllers\SubscriptionController;
// use App\Http\Controllers\ReviewController;
// use App\Http\Controllers\ReservationController;
// use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CompanyController as User_Company_Cont;
// use App\Http\Controllers\TermController as User_Term_Cont;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'auth:admin'
], function () {
    Route::get('home', [AdminHomeController::class, 'index'])->name('home');
    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');

    Route::resource('restaurants', RestaurantController::class);
    Route::resource('categories', CategoryController::class);
    // Route::resource('company', CompanyController::class);
    // Route::resource('terms', TermController::class);
});

Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/restaurants', [UR::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/{restaurant}', [UR::class, 'show'])->name('restaurants.show');
    // Route::get('/company', [User_Company_Cont::class, 'index'])->name('company.index');
    // Route::get('/terms', [User_Term_Cont::class, 'index'])->name('terms.index');
});

Route::group(['middleware' => ['auth:web','verified']], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/user/{user}', [UserController::class, 'update'])->name('user.update');

    // レビュー機能のうち一般ユーザー向け
    // Route::get('/restaurants/{restaurant}/reviews', [ReviewController::class, 'index'])->name('restaurants.reviews.index');

    // サブスクなし
    // Route::group(['middleware' => 'not_subscribed'], function () {
    //     Route::get('/subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create');
    //     Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
    // });

    // サブスクあり
    // Route::group(['middleware' => 'subscribed'], function () {
    //     Route::get('/subscription/edit', [SubscriptionController::class, 'edit'])->name('subscription.edit');
    //     Route::patch('/subscription/update', [SubscriptionController::class, 'update'])->name('subscription.update');
    //     Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    //     Route::delete('/subscription/destroy', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');

    //     // レビュー機能
    //     Route::get('/restaurants/{restaurant}/reviews/create', [ReviewController::class, 'create'])->name('restaurants.reviews.create');
    //     Route::post('/restaurants/{restaurant}/reviews', [ReviewController::class, 'store'])->name('restaurants.reviews.store');
    //     Route::get('/restaurants/{restaurant}/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('restaurants.reviews.edit');
    //     Route::patch('/restaurants/{restaurant}/reviews/{review}', [ReviewController::class, 'update'])->name('restaurants.reviews.update');
    //     Route::delete('/restaurants/{restaurant}/reviews/{review}', [ReviewController::class, 'destroy'])->name('restaurants.reviews.destroy');

    //     // 予約機能
    //     Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    //     Route::get('/restaurants/{restaurant}/reservations/create', [ReservationController::class, 'create'])->name('restaurants.reservations.create');
    //     Route::post('/restaurants/{restaurant}/reservations', [ReservationController::class, 'store'])->name('restaurants.reservations.store');
    //     Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    //     // お気に入り機能
    //     Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    //     Route::post('/restaurants/{restaurant}/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    //     Route::delete('favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    // });
});
