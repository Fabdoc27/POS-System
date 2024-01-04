<?php

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
Route::view( '/', 'pages.home' );
Route::view( '/userLogin', 'pages.auth.login' )->name( 'login.page' );
Route::view( '/userRegistration', 'pages.auth.registration' )->name( 'registration.page' );
Route::view( '/sendOtp', 'pages.auth.sendOtp' )->name( 'otp.page' );
Route::view( '/verifyOtp', 'pages.auth.verifyOtp' );
Route::view( '/resetPassword', 'pages.auth.resetPassword' )->middleware( 'token' );
Route::view( '/dashboard', 'pages.dashboard.dashboard' )->name( 'dashboard.page' )->middleware( 'token' );
Route::view( '/userProfile', 'pages.dashboard.profile' )->name( 'profile.page' )->middleware( 'token' );
Route::get( '/logout', [UserController::class, 'userLogout'] )->name( 'logout' );