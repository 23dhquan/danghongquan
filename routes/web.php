<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




Route::get('/auth/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/logout', [LoginController::class, 'logout'])->name('logout');
Route::group(['middleware' => 'admin'], function () {

    Route::get('/',function () {return view('admin.dashboard');})->name('dashboard');

    //Area
    Route::get('/area-list', [AreaController::class, 'index'])->name('area.list');
    Route::get('/add-areas', [AreaController::class, 'create'])->name('areas.create');
    Route::post('/add-areas', [AreaController::class, 'store'])->name('areas.store');
    Route::delete('areas/{id}', [AreaController::class, 'destroy'])->name('areas.destroy');
    Route::get('/edit-areas/{id}', [AreaController::class, 'edit'])->name('areas.edit');
    Route::put('/edit-areas/{id}', [AreaController::class, 'update'])->name('areas.update');

    //User
    Route::get('/user-list',[UserController::class, 'index'])->name('user.list');
    Route::get('/add-users',[UserController::class, 'create'])->name('users.create');
    Route::post('/add-users', [UserController::class, 'store'])->name('users.store');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/edit-users/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/edit-users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/user/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('reset.password');

    //House
    Route::get('/house-list', [HouseController::class, 'index'])->name('house.list');
    Route::get('/add-house', [HouseController::class, 'create'])->name('house.create');
    Route::post('/add-house', [HouseController::class, 'store'])->name('house.store');
    Route::get('/edit-house/{id}', [HouseController::class, 'edit'])->name('house.edit');
    Route::put('/edit-house/{id}', [HouseController::class, 'update'])->name('house.update');
    Route::delete('/house/{id}', [HouseController::class, 'destroy'])->name('house.destroy');

    //Tenant
    Route::get('/house-tenant', [HouseController::class, 'tenant'])->name('house-tenant.list');


    //Tenant Detail
    Route::get('/tenant-detail-list', [TenantController::class, 'index'])->name('tenant-detail.list');

});
