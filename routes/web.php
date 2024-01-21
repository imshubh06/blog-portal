<?php

use App\Http\Controllers\AmazonSrcappingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FlipkartSrcappingController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TinyMCEController;

Illuminate\Support\Facades\Auth::routes();

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

Route::post('/auth/google', [GoogleController::class, 'redirectToGoogle'])
    ->name('auth.google');

Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::post('/auth/google/refresh', [GoogleController::class, 'refreshGoogle'])
    ->name('google.refresh.token');

Route::post('/verify/otp', [LoginController::class, 'authenticateOTP'])->name('verify.otp');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'web']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::resource('roles', RoleController::class);

    Route::get('roles/all/view', [RoleController::class, 'view'])
        ->name('view.roles');

    Route::get('change/password', [UserController::class, 'updatePassword'])
        ->name('change.user.password');

    Route::post('update/password', [UserController::class, 'updatePassword'])
        ->name('update.user.password');

    Route::group(['prefix' => 'find-products'], function () {
        Route::get('amazon', [AmazonSrcappingController::class, 'find'])
            ->name('amazon.find');

        Route::post('amazon', [AmazonSrcappingController::class, 'findProducts'])
            ->name('amazon.find.products');

        Route::match(['get', 'post'], 'store', [AmazonSrcappingController::class, 'StoreFindProducts'])
            ->name('amazon.find.store');

        Route::get('flipkart', [FlipkartSrcappingController::class, 'find'])
            ->name('flipkart.find');

        Route::post('flipkart', [FlipkartSrcappingController::class, 'findProducts'])
            ->name('flipkart.find.products');

        Route::match(['get', 'post'], 'store', [FlipkartSrcappingController::class, 'StoreFindProducts'])
            ->name('flipkart.find.store');
    });

    Route::resource('listing', ListingController::class);

    Route::get('inventory', [ListingController::class, 'inventory'])
        ->name('inventory.index');

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('users', UserController::class);

    Route::get('users/verified/approved', [UserController::class, 'verified'])
        ->name('verified.users');

    Route::get('edit/users/status/{id}', [UserController::class, 'editStatus'])
        ->name('edit.users.status');

    Route::post('update/users/status', [UserController::class, 'updateStatus'])
        ->name('update.users.status');

    Route::group(['prefix' => 'settings'], function () {
        Route::get('blog', [SettingsController::class, 'blog'])
            ->name('settings.blog');

        Route::get('site', [SettingsController::class, 'site'])
            ->name('settings.site');

        Route::post('update/site', [SettingsController::class, 'update'])
            ->name('settings.site.update');
    });

    Route::post('tinymce/upload', [TinyMCEController::class, 'upload'])
        ->name('tinymce.upload');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});
