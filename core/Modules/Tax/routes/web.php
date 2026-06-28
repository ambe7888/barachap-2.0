<?php

use Illuminate\Support\Facades\Route;
use Modules\Tax\app\Http\Controllers\AdminTaxManageController;


Route::group(['prefix' => 'admin/tax', 'as' => 'admin.tax.', 'middleware' => ['auth:admin', 'setlang']], function () {
    Route::controller(AdminTaxManageController::class)->group(function () {
        Route::get("settings", "settings")->name("settings")->permission('tax-settings');
        Route::put("settings", "handleSettings");
        Route::get('country-state',  'getCountryStateInfo')->name('country.state.info.ajax');
        Route::get('state-city',  'getCountryCityInfo')->name('state.city.info.ajax');
    });

    Route::group(['prefix' => 'state'], function () {
        Route::get('/', 'StateTaxController@index')->name('state.all');
        Route::post('new', 'StateTaxController@store')->name('state.new');
        Route::post('update', 'StateTaxController@update')->name('state.update');
        Route::post('delete/{item}', 'StateTaxController@destroy')->name('state.delete');
        Route::post('bulk-action', 'StateTaxController@bulk_action')->name('state.bulk.action');
    });

    Route::group(['prefix' => 'city'], function () {
        Route::controller("CityTaxController")->group(function (){
            Route::get('/', 'index')->name('city.all');
            Route::post('new', 'store')->name('city.new');
            Route::post('update', 'update')->name('city.update');
            Route::post('delete/{item}', 'destroy')->name('city.delete');
            Route::post('bulk-action', 'bulk_action')->name('city.bulk.action');
        });
    });

});
