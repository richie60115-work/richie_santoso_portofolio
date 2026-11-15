<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Manajer\DashboardController    as ManajerDashboard;
use App\Http\Controllers\Manajer\RawMaterialController;
use App\Http\Controllers\Manajer\RawMaterialTypeController;
use App\Http\Controllers\Manajer\LabaRugiController;
use App\Http\Controllers\Manajer\ProductController;
use App\Http\Controllers\Operator\DashboardController  as OperatorDashboard;
use App\Http\Controllers\Operator\OrderController      as AdminOrderController;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboard;
use App\Http\Controllers\Pelanggan\CartController;
use App\Http\Controllers\Pelanggan\OrderController     as PelangganOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shared\UserController;
use App\Http\Controllers\Operator\OrderController;

use App\Http\Controllers\Operator\SettingController;

// ─── Halaman Utama ────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::view('/projects/business-management-app', 'projects.business-management-app')->name('projects.business-management-app');
Route::view('/projects/restaurant-ordering-app', 'projects.restaurant-ordering-app')->name('projects.restaurant-ordering-app');
Route::view('/projects/face-recognition-attendance-system', 'projects.face-recognition-attendance-system')->name('projects.face-recognition-attendance-system');
Route::view('/projects/online-order-and-management-system', 'projects.online-order-and-management-system')->name('projects.online-order-and-management-system');