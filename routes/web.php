<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\HouseController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\TenantDetailController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
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
    Route::get('/tenant-list', [TenantController::class, 'index'])->name('tenant.list');
    Route::get('/house-tenant', [HouseController::class, 'tenant'])->name('house-tenant.list');
    Route::post('/tenants/store', [TenantController::class, 'store'])->name('tenants.store');
    Route::delete('/tenants/{id}', [TenantController::class, 'destroy'])->name('tenants.destroy');
    //Tenant Detail
    Route::get('/tenant-detail-list', [TenantDetailController::class, 'index'])->name('tenant-detail.list');
    Route::get('/add-tenant-detail', [TenantDetailController::class, 'create'])->name('tenant-detail.create');
    Route::post('/tenant-detail/store', [TenantDetailController::class, 'store'])->name('tenant-detail.store');
    Route::get('/edit-tenant-detail/{id}', [TenantDetailController::class, 'edit'])->name('tenant-detail.edit');
    Route::put('/edit-tenant-detail/{id}', [TenantDetailController::class, 'update'])->name('tenant-detail.update');
    Route::delete('/tenant-detail/{id}', [TenantDetailController::class, 'destroy'])->name('tenant-detail.destroy');
});
