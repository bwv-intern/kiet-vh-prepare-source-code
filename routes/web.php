<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, CommonController, ProductController, TopController, UserController};

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
Route::get('404', function () {
    abort(404);
})->name('404');

// Route for guest and login
Route::get('/admin/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin/handleLogin', [AuthController::class, 'handleLogin'])->name('auth.handleLogin');
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('logout');

// Route for Admin and Support
Route::middleware(['auth', 'role:0,2'])->group(function () {

    Route::get('/admin', [TopController::class, 'index'])->name('admin.top.index');


    Route::get('/admin/product', [ProductController::class, 'search'])->name('admin.product.search');
    Route::get('/admin/product/add', [ProductController::class, 'add'])->name('admin.product.add');
    Route::get('/admin/product/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');

});

// Route only for admin
Route::middleware(['auth', 'role:0'])->group(function () {

    Route::post('/admin/user/fetch_data', [UserController::class, 'fetch_data'])->name('admin.user.fecth_paginate');

    Route::get('/admin/user', [UserController::class, 'search'])->name('admin.user.search');

    Route::post('/admin/user', [UserController::class, 'handleSearch'])->name('admin.user.search');

    Route::get('/admin/user/add', [UserController::class, 'add'])->name('admin.user.add');
    Route::post('/admin/user/add', [UserController::class, 'postAdd'])->name('admin.user.add');

    Route::get('/admin/user/{id}', [UserController::class, 'edit'])->name('admin.user.edit');

    Route::post('/admin/user/edit', [UserController::class, 'postEdit'])->name('admin.user.postEdit');


    Route::get('/admin/user/delete/{id}', [UserController::class, 'getDelete'])->name('admin.user.delete');

    Route::post('/admin/user/export', [UserController::class, 'exportCsv'])->name('admin.user.export');

    Route::prefix('common')->as('common.')->group(function () {
        Route::get('resetSearch', [CommonController::class, 'resetSearch'])->name('resetSearch');
    });

    Route::post('/admin/user/import', [UserController::class, 'importCsv'])->name('admin.user.import');
});
