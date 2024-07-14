<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController as UR;

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

/*
このようにRouteファサードのgroup()メソッドを使い、配列で'prefix'や'as'を指定することで、'prefix'の場合はURLの先頭、'as'の場合は名前付きルートの先頭を設定できます。

つまり以下の例であれば、グループ内で設定しているルートのURLが'admin/home'、名前付きルートが'admin.home'となります。
*/
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'auth:admin'
], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::resource('restaurants', RestaurantController::class);
    Route::resource('categories', CategoryController::class); // カテゴリ管理用のルートを追加
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
});

Route::resource('restaurants', RestaurantController::class);


// admin以外が通れる
// ※guest:adminの挙動: adminはadmin.homeへリダイレクト、それ以外はOK。
// Route::group(['middleware' => 'guest:admin'], function () {
    Route::group(['middleware' => 'guest:admin'], function () {
        
        Route::get('/restaurants', [UR::class, 'index'])->name('restaurants.index');
        Route::get('/restaurants/{restaurant}', [UR::class, 'show'])->name('restaurants.show');
    });

// auth:web = WEB（ユーザー）として認証のみ
Route::group(['middleware' => ['auth:web','verified']], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/user/{user}', [UserController::class, 'update'])->name('user.update');
});