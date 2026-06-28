<?php

use Illuminate\Support\Facades\Route;

use Modules\CountryManage\app\Http\Controllers\AreaController;
use Modules\CountryManage\app\Http\Controllers\CityController;
use \Modules\CountryManage\app\Http\Controllers\StateController;

Route::group(['prefix' => 'admin/location', 'middleware' => ['auth:admin', 'setlang']], function () {
    Route::group(['prefix' => 'state'], function () {
        Route::match(['get', 'post'], '/all-state', [StateController::class, 'all_state'])->name('admin.state.all')->permission('state-list');
        Route::post('edit-state/{id?}', [StateController::class,'edit_state'])->name('admin.state.edit')->permission('state-edit');
        Route::post('change-status/{id}', [StateController::class,'change_status_state'])->name('admin.state.status')->permission('state-status-change');
        Route::post('delete/{id}', [StateController::class,'delete_state'])->name('admin.state.delete')->permission('state-delete');
        Route::post('bulk-action', [StateController::class,'bulk_action_state'])->name('admin.state.delete.bulk.action')->permission('state-bulk-delete');
        Route::get('paginate/data', [StateController::class,'pagination'])->name('admin.state.paginate.data');
        Route::get('search-state', [StateController::class,'search_state'])->name('admin.state.search');
        Route::get('csv/import', [StateController::class,'import_settings'])->name('admin.state.import.csv.settings')->permission('state-csv-file-import');
        Route::post('csv/import', [StateController::class,'update_import_settings'])->name('admin.state.import.csv.update.settings');
        Route::post('csv/import/database', [StateController::class,'import_to_database_settings'])->name('admin.state.import.database');
    });

    Route::group(['prefix' => 'city'], function () {
        Route::controller(CityController::class)->group(function () {
            Route::match(['get', 'post'], 'all-city', 'all_city')->name('admin.city.all')->permission('city-list');
            Route::post('edit-city/{id?}', 'edit_city')->name('admin.city.edit')->permission('city-edit');
            Route::post('change-status/{id}', 'change_status_city')->name('admin.city.status')->permission('city-status-change');
            Route::post('delete/{id}', 'delete_city')->name('admin.city.delete')->permission('city-delete');
            Route::post('bulk-action', 'bulk_action_city')->name('admin.city.delete.bulk.action')->permission('city-bulk-delete');
            Route::get('paginate/data', 'pagination')->name('admin.city.paginate.data');
            Route::get('search-city', 'search_city')->name('admin.city.search');
            Route::get('csv/import', 'import_settings')->name('admin.city.import.csv.settings')->permission('city-csv-file-import');
            Route::post('csv/import', 'update_import_settings')->name('admin.city.import.csv.update.settings');
            Route::post('csv/import/database', 'import_to_database_settings')->name('admin.city.import.database');

            // city wise state get ajax select
            Route::get('city', 'getCityByState')->name('admin.city.by.state');

        });
    });

    Route::group(['prefix' => 'area'], function () {
        Route::controller(AreaController::class)->group(function () {
            Route::match(['get', 'post'], 'all-area', 'all_area')->name('admin.area.all')->permission('area-list');
            Route::post('edit-area/{id?}', 'edit_area')->name('admin.area.edit')->permission('area-edit');
            Route::post('change-status/{id}', 'area_status')->name('admin.area.status')->permission('area-status-change');
            Route::post('delete/{id}', 'delete_area')->name('admin.area.delete')->permission('area-delete');
            Route::post('bulk-action', 'bulk_action_area')->name('admin.area.delete.bulk.action')->permission('area-bulk-delete');
            Route::get('paginate/data', 'pagination')->name('admin.area.paginate.data');
            Route::get('search-area', 'search_area')->name('admin.area.search');
            Route::get('csv/import', 'import_settings')->name('admin.area.import.csv.settings')->permission('area-csv-file-import');
            Route::post('csv/import', 'update_import_settings')->name('admin.area.import.csv.update.settings');
            Route::post('csv/import/database', 'import_to_database_settings')->name('admin.area.import.database');
        });
    });
});
