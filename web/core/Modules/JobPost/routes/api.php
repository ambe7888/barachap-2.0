<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\JobPost\app\Http\Controllers\Api\JobController;
use Modules\JobPost\app\Http\Controllers\Api\JobHireController;
use Modules\JobPost\app\Http\Controllers\Api\ProviderJobOfferController;
use Modules\JobPost\app\Http\Controllers\Client\ClientJobController;


Route::group(['prefix'=>'v1', 'middleware' => 'setlang'],function(){

    //provider jobs
    Route::group(['prefix' => 'provider/job','middleware' => 'auth:sanctum'],function (){
        Route::get('offers/lists', [ProviderJobOfferController::class, 'job_offers_lists']);
    });

    //client jobs
    Route::group(['prefix' => 'client/job','middleware' => 'auth:sanctum'],function (){
        Route::get('lists', [ClientJobController::class, 'job_list']);
        Route::get('details/{id?}', [ClientJobController::class, 'job_details']);
        Route::post('on-off', [ClientJobController::class, 'job_on_off']);
        Route::post('add', [ClientJobController::class, 'create_job']);
        Route::post('edit/{id?}', [ClientJobController::class, 'edit_job']);
        Route::post('published-status/{id?}', [ClientJobController::class, 'job_published_status']);
        Route::post('delete/{id?}', [ClientJobController::class, 'delete_job']);

        // job offers
        Route::get('offers/lists', [ClientJobController::class, 'offers_list']);
        Route::get('offers/details/{id?}', [ClientJobController::class, 'offers_details']);
        Route::get('offers/reject/{id?}', [ClientJobController::class, 'offers_reject']);
        Route::get('offers/delete/{id?}', [ClientJobController::class, 'offer_delete']);

        /* job hire */
        Route::post('hire', [JobHireController::class, 'job_hire']);
    });

    //jobs
    Route::group(['prefix' => 'job/'], function () {
        Route::get('lists', [JobController::class, 'job_list']);
        Route::get('details/{id}', [JobController::class, 'job_details'])->name('api.job.post.details');
        Route::post('offer-create', [ProviderJobOfferController::class, 'job_offer_create']);
        Route::get('recent-jobs', [JobController::class, 'recent_jobs']);
    });

});
