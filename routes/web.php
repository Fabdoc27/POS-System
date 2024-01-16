<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware( ['token'] )->group( function () {
    // API Routes
    Route::post( '/user-registration', [UserController::class, 'userRegistration'] )->withoutMiddleware( 'token' );
    Route::post( '/user-login', [UserController::class, 'userLogin'] )->withoutMiddleware( 'token' );
    Route::post( '/send-otp', [UserController::class, 'sendOtp'] )->withoutMiddleware( 'token' );
    Route::post( '/verify-otp', [UserController::class, 'verifyOtp'] )->withoutMiddleware( 'token' );

    Route::post( '/reset-password', [UserController::class, 'passwordReset'] );
    Route::get( '/user-profile', [UserController::class, 'userProfile'] );
    Route::post( '/user-update', [UserController::class, 'updateProfile'] );
    Route::get( '/logout', [UserController::class, 'userLogout'] )->name( 'logout' );

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

    // Products
    Route::view( '/product', 'pages.dashboard.product' )->name( 'product' );
    Route::get( '/product-list', [ProductController::class, 'productList'] );
    Route::post( '/product-create', [ProductController::class, 'productCreate'] );
    Route::post( '/product-unique', [ProductController::class, 'productById'] );
    Route::post( '/product-update', [ProductController::class, 'productUpdate'] );
    Route::post( '/product-delete', [ProductController::class, 'productDelete'] );

    // Invoice
    Route::view( '/sales', 'pages.dashboard.sale' )->name( 'sales' );
    Route::view( '/invoice', 'pages.dashboard.invoice' )->name( 'invoice' );
    Route::get( '/invoice-list', [InvoiceController::class, 'invoiceList'] );
    Route::post( '/invoice-create', [InvoiceController::class, 'invoiceCreate'] );
    Route::post( '/invoice-details', [InvoiceController::class, 'invoiceDetails'] );
    Route::post( '/invoice-delete', [InvoiceController::class, 'invoiceDelete'] );
} );