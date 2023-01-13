<?php

use App\Http\Controllers\Auth\CheckPhoneController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Marketplace\CreateMarketplaceController;
use App\Http\Controllers\Marketplace\Product\AddProductController;
use App\Http\Controllers\Marketplace\ShowMarketplaceController;
use Illuminate\Support\Facades\Route;


Route::post('/check_phone', [CheckPhoneController::class, 'index'])->name('check_phone');
Route::post('/otp', [OtpController::class, 'index'])->name('otp');
Route::post('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/login', [LoginController::class, 'index'])->name('login');
Route::post('/logout', [LogoutController::class, 'index'])->middleware('auth:sanctum')->name('logout');



Route::post('/create_marketplace', [CreateMarketplaceController::class, 'index'])->middleware('auth:sanctum')->name('create_marketplace');

Route::get('/marketplace', [ShowMarketplaceController::class, 'index'])->name('marketplace');

Route::post('/add_product', [AddProductController::class, 'index'])->middleware('auth:sanctum')->name('add_product');










// })->middleware('throttle:60,1')->name('test');
