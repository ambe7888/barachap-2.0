<?php

use Illuminate\Support\Facades\Route;
use Modules\JobPost\app\Http\Controllers\Backend\AdminJobController;
use Modules\JobPost\app\Http\Controllers\JobPostController;

require_once __DIR__ . '/client.php';

// backend routes
Route::group(['prefix' => 'admin/job', 'middleware' => ['auth:admin', 'setlang']], function () {
    Route::controller(AdminJobController::class)->group(function () {
        Route::get( 'all', 'jobs')->name('admin.jobs.all')->permission('job-list');
        Route::get('/details/{id?}/{notificationId?}', 'jobDetails')->name('admin.jobs.details');
        Route::post( '/status/{id}', 'change_status')->name('admin.jobs.status')->permission('job-status-change');
        Route::post( '/delete/{id}','delete')->name('admin.jobs.delete');
        Route::get( '/all-offers/{id}','all_offers')->name('admin.jobs.offers.all');
        // others
        Route::post('/published/{id}','jobPublishedStatus')->name('admin.job.published.status.change');
        Route::get('/search','searchJob')->name('admin.job.search');
        Route::get('/paginate','paginateJob')->name('admin.job.paginate');
        Route::post('/bulk-action','bulkAction')->name('admin.job.bulk.action')->permission('job-bulk-delete');
        Route::match(['get','post'],'userJob-restore/{id?}','userjob_restore')->name('admin.job.restore');
            Route::post('permanent-delete/{job_id}','permanent_delete')->name('admin.job.permanent.delete')->permission('job-permanent-delete');
            Route::get('paginate/delete/data', 'pagination_delete_job')->name('admin.job.paginate.delete.data');
            Route::get('delete/search-job', 'search_delete_job')->name('admin.job.delete.search');
        // jobs Settings
        Route::get( '/settings', [AdminJobController::class, 'jobSettings'])->name('admin.job.settings');
        Route::post('/settings-update', [AdminJobController::class, 'jobCreateSettingsUpdate'])->name('admin.job.create.settings.update');
    });
});


//frontend routes
Route::middleware(['setlang','provider'])->group(function () {

    // Dashboard
    Route::prefix('provider')->group(function () {
        Route::prefix('job')->group(function () {
            Route::controller(Modules\JobPost\app\Http\Controllers\Provider\ProviderFrontendJobController::class)->group(function(){
                Route::get('/all-jobs', 'index')->name('provider.all.jobs')->middleware('auth:web');
                Route::get('/details/{id?}/{notificationId?}', 'jobDetails')->name('provider.jobs.details');
                Route::match(['get','post'],'/send-job-offer/{job_post_id?}','job_offer_create')->name('provider.job.offer.send');
                Route::get('/search','searchJob')->name('provider.job.search');
                Route::get('/paginate','paginateJob')->name('provider.job.paginate');
                Route::post( '/add-favorite/{item_id}', 'addFavorite')->name('provider.job.favorite');
                Route::post('/remove-favorite/{item_id}','favoriteRemove')->name('provider.job.favorite.remove');

            
            });

        });
    });
});       



