<?php

use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\HomeController;
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

//Route::controller(SignInController::class)->group(function (){
//    Route::get('/login', 'index')->name('login');
//    Route::post('/login', 'signIn')
//        ->middleware('throttle:auth')
//        ->name('signIn');
//
//    Route::post('/sign-up', 'store')->name('store');
//
//    Route::get('/sign-up', 'signUp')
//        ->middleware('throttle:auth')
//        ->name('signUp');
//
//
//    Route::delete('/logout', 'logOut')->name('logOut');
//    Route::get('/forgot-password','forgot')
//        ->middleware('guest')
//        ->name('password.request');
//
//    Route::post('/forgot-password', 'forgotPassword')
//        ->middleware('guest')
//        ->name('password.email');
//
//    Route::get('/reset-password/{token}', 'reset')
//        ->middleware('guest')
//        ->name('password.reset');
//
//    Route::post('/reset-password', 'resetPassword')
//        ->middleware('guest')
//        ->name('password.update');
//
//    Route::get('/auth/socialite/github','github')
//        ->name('socialite.github');
//
//    Route::get('/auth/socialite/github/callback','githubCallback')
//    ->name('socialite.github.callback');
//});
//
//Route::get('/', HomeController::class)->name('home');



