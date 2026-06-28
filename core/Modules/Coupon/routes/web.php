<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupon\app\Http\Controllers\AdminCouponController;

Route::group(['prefix' => 'admin/coupons', 'as' => 'admin.coupon.', 'middleware' => ['auth:admin', 'setlang']], function () {
    Route::controller(AdminCouponController::class)->group(function () {
        Route::get('/all', 'index')->name('all')->permission('coupon-list');
        Route::post('new', 'store')->name('new')->permission('coupons-new');
        Route::post('update', 'update')->name('update')->permission('coupon-edit-add');
        Route::post('delete/{item}', 'destroy')->name('delete')->permission('coupon-delete-add');
        Route::post('bulk-action', 'bulk_action')->name('bulk.action');
        Route::get('check', 'check')->name('check');
        Route::get('get-products', 'allProductsAjax')->name('service');
    });
});

