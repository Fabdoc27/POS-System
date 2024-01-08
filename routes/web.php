<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// API Routes
Route::post( '/user-registration', [UserController::class, 'userRegistration'] );
Route::post( '/user-login', [UserController::class, 'userLogin'] );
Route::post( '/send-otp', [UserController::class, 'sendOtp'] );
Route::post( '/verify-otp', [UserController::class, 'verifyOtp'] );
Route::post( '/reset-password', [UserController::class, 'passwordReset'] )->middleware( 'token' );
Route::get( '/user-profile', [UserController::class, 'userProfile'] )->middleware( 'token' );
Route::post( '/user-update', [UserController::class, 'updateProfile'] )->middleware( 'token' );

// Page Routes
// Route::get('/',[HomeController::class,'HomePage']);
Route::view( '/', 'pages.home' )->name( 'home' );
Route::view( '/userLogin', 'pages.auth.login' )->name( 'login' );
Route::view( '/userRegistration', 'pages.auth.registration' )->name( 'registration' );
Route::view( '/sendOtp', 'pages.auth.sendOtp' )->name( 'otp' );
Route::view( '/verifyOtp', 'pages.auth.verifyOtp' );
Route::view( '/resetPassword', 'pages.auth.resetPassword' )->middleware( 'token' );
Route::view( '/dashboard', 'pages.dashboard.dashboard' )->name( 'dashboard' )->middleware( 'token' );
Route::view( '/userProfile', 'pages.dashboard.profile' )->name( 'user_profile' )->middleware( 'token' );
Route::get( '/logout', [UserController::class, 'userLogout'] )->name( 'logout' );

Route::middleware( ['token'] )->group( function () {
    // Category
    Route::view( '/category', 'pages.dashboard.category' )->name( 'category' );
    Route::get( '/category-list', [CategoryController::class, 'categoryList'] );
    Route::post( '/category-create', [CategoryController::class, 'categoryCreate'] );
    Route::post( '/category-unique', [CategoryController::class, 'categoryById'] );
    Route::post( '/category-update', [CategoryController::class, 'categoryUpdate'] );
    Route::post( '/category-delete', [CategoryController::class, 'categoryDelete'] );

    // Customer
    Route::view( '/customer', 'pages.dashboard.customer' )->name( 'customer' );
    Route::get( '/customer-list', [CustomerController::class, 'customerList'] );
    Route::post( '/customer-create', [CustomerController::class, 'customerCreate'] );
    Route::post( '/customer-unique', [CustomerController::class, 'customerById'] );
    Route::post( '/customer-update', [CustomerController::class, 'customerUpdate'] );
    Route::post( '/customer-delete', [CustomerController::class, 'customerDelete'] );
} );