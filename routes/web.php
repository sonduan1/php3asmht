<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StasticController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\UpdateUser;
use Illuminate\Support\Facades\Route;

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
//Admin
//Category
Route::middleware([CheckAdmin::class])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [StasticController::class, 'stastic'])->name('stastic');
        Route::resource('/categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::get('/user', [AuthController::class, 'listUser'])->name('admin.user');
        Route::put('/user/{user}', [AuthController::class, 'editActive'])->name('admin.editActive')->middleware([UpdateUser::class]);
        Route::get('/user/add', [AuthController::class, 'addUser'])->name('admin.addUser');
        Route::post('/user/add', [AuthController::class, 'postUser'])->name('admin.postUser');
    });
});


Route::get('/', [ClientController::class, 'index'])->name('client.index')->middleware([CheckAuth::class]);
Route::prefix('/client')->group(function () {
});

//Auth
Route::get('/login', [AuthController::class, 'getLogin'])->name('getLogin');
Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
Route::get('/regester', [AuthController::class, 'getRegister'])->name('getRegister');
Route::post('/regester', [AuthController::class, 'postRegister'])->name('postRegister');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/update/user/{user}', [AuthController::class, 'edit'])->name('editUser');
Route::put('/update/user/{user}', [AuthController::class, 'update'])->name('updateUser');

//Shop
Route::get('/shop', [ClientController::class, 'shop'])->name('client.shop');
Route::get('/shop/{id}', [ClientController::class, 'shop'])->name('client.shop.id');
Route::get('/shop-detail/{id}', [ClientController::class, 'shopDetail'])->name('client.shopDetail');
Route::get('/contact',function(){
    return view('client.home.contact');
})->name('client.contact');
