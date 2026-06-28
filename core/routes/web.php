<?php

use App\Http\Controllers\Common\AdminUserController;
use App\Http\Controllers\Common\GetCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\SocialLoginController;


require_once __DIR__ . '/admin.php';
require_once __DIR__ . '/provider.php';
require_once __DIR__ . '/client.php';

Route::group(['middleware' => ['globalVariable','setlang']], function () {
  
    
    Route::controller(LoginController::class)->group(function(){
        Route::get('/admin', 'showLoginForm')->name('admin.login');
        Route::post('/admin',  'adminLogin');
        Route::get('/admin/forget-password', 'showAdminForgetPasswordForm')->name('admin.forget.password');
        Route::get('/admin/reset-password/{user}/{token}', 'showAdminResetPasswordForm')->name('admin.reset.password');
        Route::post('/admin/reset-password', 'AdminResetPassword')->name('admin.reset.password.change');
        Route::post('/admin/forget-password', 'sendAdminForgetPasswordMail');
    });
    Route::controller(App\Http\Controllers\Frontend\Auth\LoginController::class)->group(function(){
        Route::get('/login', 'showLoginForm')->name('user.login');
        Route::post('/login',  'userLogin');
        Route::get('/user/forget-password', 'showUserForgetPasswordForm')->name('user.forget.password');
        Route::get('/user/reset-password/{user}/{token}', 'showUserResetPasswordForm')->name('user.reset.password');
        Route::post('/user/reset-password', 'UserResetPassword')->name('user.reset.password.change');
        Route::post('/user/forget-password', 'sendUserForgetPasswordMail');
    });

    Route::controller(App\Http\Controllers\Frontend\Auth\RegisterController::class)->group(function(){
        Route::get('/register', 'userRegister')->name('user.frontend.register');
        Route::post('/register',  'userRegister');
        Route::post('/frontend-user-name-availability','userNameAvailability')->name('user.frontend.name.availability');
        Route::post('/frontend-email-availability','emailAvailability')->name('user.frontend.email.availability');
        Route::match(['get', 'post'], 'frontend-email-verify', 'emailVerify')->name('user.frontend.email.verify')->middleware('auth:web');
        Route::get('frontend-resend-verify-code-again', 'resendCode')->name('user.frontend.resend.verify.code')->middleware('auth:web');
      
    });

     // user social login
     Route::controller(SocialLoginController::class)->group(function(){
        Route::get('facebook/callback', 'facebook_callback')->name('facebook.callback');
        Route::get('facebook/redirect', 'facebook_redirect')->name('login.facebook.redirect');
        Route::get('google/callback', 'google_callback')->name('google.callback');
        Route::get('google/redirect', 'google_redirect')->name('login.google.redirect');
    });
});

Route::group(['middleware' => ['globalVariable', 'maintains_mode','setlang']], function () {
    // public routes for user and admin
    Route::controller(AdminUserController::class)->group(function(){
        Route::post('get-state','get_state_city')->name('au.state.all');
        Route::post('get-city','get_city_area')->name('au.city.all');
        Route::post('get-subcategory','get_subcategory')->name('au.subcategory.all');
    });

    // get category, subcategory, child category for select
    Route::post('get-subcategory',[GetCategoryController::class, 'get_sub_category'])->name('get.subcategory');
    Route::post('get-child-category',[GetCategoryController::class, 'get_child_category'])->name('get.subcategory.with.child.category');

    //dynamic single page
    Route::controller(App\Http\Controllers\Frontend\FrontendController::class)->group(function(){
        Route::get('/','home_page')->name('homepage');
        Route::get('/{slug}', 'dynamic_single_page')->name('frontend.dynamic.page');
    });

    // user registration
    Route::controller(RegisterController::class)->group(function(){
        Route::post('user-name-availability','userNameAvailability')->name('user.name.availability');
        Route::post('email-availability','emailAvailability')->name('user.email.availability');
        Route::post('phone-number-availability','phoneNumberAvailability')->name('user.phone.number.availability');
        Route::match(['get','post'],'user-register','userRegister')->name('user.register');
        Route::match(['get', 'post'], 'email-verify', 'emailVerify')->name('email.verify')->middleware('auth:web');
        Route::get('resend-verify-code-again', 'resendCode')->name('resend.verify.code')->middleware('auth:web');
    });

});
