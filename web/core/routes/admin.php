<?php

use App\Http\Controllers\Backend\AdminCommissionController;
use App\Http\Controllers\Backend\AdminDashboardController;
use App\Http\Controllers\Backend\AdminNotificationController;
use App\Http\Controllers\Backend\AdminOrderManageController;
use App\Http\Controllers\Backend\AdminProfileController;
use App\Http\Controllers\Backend\AdminProviderController;
use App\Http\Controllers\Backend\AdminScheduleManageController;
use App\Http\Controllers\Backend\AdminServiceController;
use App\Http\Controllers\Backend\AdminServiceLocationController;
use App\Http\Controllers\Backend\AdminServiceOrderManageController;
use App\Http\Controllers\Backend\AdminStaffManageController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\ClientOfferController;
use App\Http\Controllers\Backend\EmailSettingsController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\GeneralSettingsController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\MaintainsPageController;
use App\Http\Controllers\Backend\Manage404PageController;
use App\Http\Controllers\Backend\MapSettings;
use App\Http\Controllers\Backend\MediaUploadController;
use App\Http\Controllers\Backend\NoticeController;
use App\Http\Controllers\Backend\OfferController;
use App\Http\Controllers\Backend\PageBuilderController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\PageSettingsController;
use App\Http\Controllers\Backend\ProviderOfferController;
use App\Http\Controllers\Backend\ReportReasonController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\SuspendActiveController;
use App\Http\Controllers\Backend\UserManageController;
use App\Http\Controllers\Backend\OrderCancellationPolicyController;
use App\Http\Controllers\Backend\RefundGatewayController;
use App\Http\Controllers\Backend\RefundController;
use App\Http\Controllers\Backend\UserServiceManageController;
use App\Http\Controllers\Backend\WithdrawGatewayController;
use App\Http\Controllers\FireBaseSettingsController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;




Route::middleware(['setlang'])->group(function () {

    //language
    Route::get('languages',[LanguageController::class, 'index'])->name('admin.languages')->permission('languages-list');
    Route::get('/languages/words/all/{id}',[LanguageController::class, 'allEditWords'])->name('admin.languages.words.all')->permission('languages-words-edit');
    Route::post('/languages/words/update/{id}',[LanguageController::class, 'updateWords'])->name('admin.languages.words.update');
    Route::post('/languages/new',[LanguageController::class, 'store'])->name('admin.languages.new')->permission('languages-add');
    Route::post('/languages/update',[LanguageController::class, 'update'])->name('admin.languages.update');
    Route::post('/languages/delete/{id}',[LanguageController::class, 'delete'])->name('admin.languages.delete')->permission('languages-delete');
    Route::post('/languages/default/{id}',[LanguageController::class, 'makeDefault'])->name('admin.languages.default');
    Route::post('/languages/clone',[LanguageController::class, 'cloneLanguages'])->name('admin.languages.clone')->permission('languages-clone');
    Route::post('/languages/add-new-word',[LanguageController::class, 'addNewWords'])->name('admin.languages.add.new.word');
    Route::post('/languages/regenerate-source-text',[LanguageController::class, 'regenerateSourceText'])->name('admin.languages.regenerate.source.texts');

    // admin commission
    Route::get('admin-commission/all',[AdminCommissionController::class, 'admin_commission_all'])->name('admin.commission.all');
    Route::post('admin-commission/update/{id?}', [AdminCommissionController::class, 'admin_commission_update'])->name('admin.commission.update');

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'adminDashboard'])->name('admin.dashboard')->permission('admin-dashboard');
        Route::get('/get-user-data', [AdminDashboardController::class, 'getUserData'])->name('admin.get.user.graph.data');
        Route::get('/get-services-data', [AdminDashboardController::class, 'getServiceData'])->name('admin.get.service.graph.data');
        Route::get('/get-total-income-data', [AdminDashboardController::class, 'getTotalIncomeData'])->name('admin.get.total.income.graph.data');
    });

    // General Settings
    Route::get('/dark-mode-toggle', 'AdminDashboardController@dark_mode_toggle')->name('admin.dark.mode.toggle');
    Route::get('/settings', [AdminDashboardController::class, 'adminSettings'])->name('admin.profile.settings');
    Route::get('/dark-mode-toggle',  [AdminDashboardController::class, 'darkModeToggle'])->name('admin.dark.mode.toggle');

    // admin profile settings
    Route::get('/logout', [AdminProfileController::class, 'adminLogout'])->name('admin.logout');
    Route::get('/profile-update', [AdminProfileController::class, 'adminProfile'])->name('admin.profile.update');
    Route::post('/profile-update', [AdminProfileController::class, 'adminProfileUpdate']);
    Route::get('/password-change',[AdminProfileController::class, 'adminPassword'])->name('admin.profile.password.change');
    Route::post('/password-change',[AdminProfileController::class, 'adminPasswordChange']);

    //account suspend active
    Route::group(['prefix' => 'account'],function(){
        Route::controller(\App\Http\Controllers\Backend\SuspendActiveController::class)->group(function () {
            Route::match(['get','post'],'suspend/{id}','suspend')->name('admin.account.suspend');
            Route::post('unsuspend/{id}','unsuspend')->name('admin.account.unsuspend');
        });
    });

    // all orders manage
    Route::group(['prefix' => 'orders/user'],function(){
        Route::controller(AdminOrderManageController::class)->group(function () {
            Route::get('/all-orders','allUserOrders')->name('admin.user.all.order');
            Route::get('/details/{id}/{notificationId?}','orderDetails')->name('admin.order.details');
            Route::get('/sub-order-addon/details/{id}','subOrderAddonDetails')->name('admin.sub.order.addon.details');
            Route::post('/status','changeStatus')->name('admin.user.order.status.change');
            Route::get('/search','searchOrder')->name('admin.user.order.search');
            Route::get('/paginate','paginate')->name('admin.user.order.paginate');
        });
    });

    // admin personal orders manage
    Route::group(['prefix' => 'orders/'],function(){
        Route::controller(AdminServiceOrderManageController::class)->group(function () {
            Route::get('/all-orders','allAdminOrders')->name('admin.service.all.orders');
            Route::get('/details/{id}/{notificationId?}','orderAdminDetails')->name('admin.main.order.details');
            Route::get('/sub-order-addon/details/{id}','adminSubOrderAddonDetails')->name('admin.main.sub.order.addon.details');
            Route::post('/status','changeAdminStatus')->name('admin.order.status.change');
            Route::get('/search','searchOrder')->name('admin.order.search');
            Route::get('/paginate','paginate')->name('admin.order.paginate');
            // payment status change
            Route::post('/change-manual-payment-status/{id}','change_payment_status')->name('admin.order.change.status');
            // order settings
            Route::get('settings', [AdminServiceOrderManageController::class, 'orderSettings'])->name('admin.order.settings');
            Route::post('settings', [AdminServiceOrderManageController::class, 'updateOrderSettings']);
            // order invoice generate
            Route::get('invoice-details/{id?}', [InvoiceController::class, 'orderInvoiceGenerate'])->name('admin.order.invoice.generate');
            // admin sub order manage
            Route::post('/sub-order-status','changeAdminSubOrderStatus')->name('admin.sub.order.status.change');
        });
        Route::get('refunded-order-list', [RefundController::class, 'refundList'])->name('admin.redunded-order.list');
        Route::get('/refund-details/{id}',[RefundController::class, 'refundDetails'])->name('admin.refunded-order.details');
        Route::post('refunded-order-status', [RefundController::class, 'changeRefundedStatus'])->name('admin.redunded-order.status');
       Route::get('/refunded-order-paginate',[RefundController::class, 'paginate'])->name('admin.refunded-order.paginate');
          //  // order cancellation policy
          Route::get('order-cancellation-policy', [OrderCancellationPolicyController::class, 'cancellationPolicyPage'])->name('admin.order.cancellation-policy');
          Route::post('order-cancellation-policy', [OrderCancellationPolicyController::class, 'cancellationPolicy']);
    });


    // services manage
    Route::group(['prefix' => 'services'],function(){
        // all user services
        Route::controller(UserServiceManageController::class)->group(function () {
            Route::get('/user-all-services','allServices')->name('admin.user.all.services')->permission('user-service-list');
            Route::get('/details/{id}/{notificationId?}','serviceDetails')->name('admin.service.details');
            Route::get('/details/{id}','serviceDetails')->name('admin.subservice.details');
            Route::post('/user-all/approved','userServiceAllApproved')->name('admin.service.user.all.approved')->permission('user-service-approved');
            Route::post('/published/{id}','servicePublishedStatus')->name('admin.service.published.status.change')->permission('user-service-published-status-change');
            Route::post('/status/{id}','changeStatus')->name('admin.service.status.change')->permission('user-service-status-change');
            Route::post('/make-featured/{id}','makeFeatured')->name('admin.service.make.featured')->permission('user-service-status-change');
            Route::get('/search','searchService')->name('admin.service.search');
            Route::get('/paginate','paginate')->name('admin.service.paginate');
            Route::post('/delete/{id}','serviceDelete')->name('admin.service.delete')->permission('user-service-delete');
            Route::post('/bulk-action','bulkAction')->name('admin.service.bulk.action')->permission('user-service-bulk-delete');
            Route::match(['get','post'],'userService-restore/{id?}','userService_restore')->name('admin.service.restore');
            Route::post('permanent-delete/{service_id}','permanent_delete')->name('admin.service.permanent.delete')->permission('service-permanent-delete');
            Route::get('paginate/delete/data', 'pagination_delete_service')->name('admin.service.paginate.delete.data');
            Route::get('delete/search-service', 'search_delete_service')->name('admin.service.delete.search');
           
        });
        Route::get('/get-cities/{stateId}',[AdminServiceLocationController::class,'getCity'])->name('getCity');
        Route::get('/get-areas/{cityId}/{stateId}',[AdminServiceLocationController::class,'getArea'])->name('getArea');

        // all admin services
        Route::controller(AdminServiceController::class)->group(function () {
            Route::get('all','adminAllServices')->name('admin.all.services')->permission('admin-service-list');
            Route::match(['get','post'],'add','adminAddService')->name('admin.add.new.service')->permission('admin-service-add');
            Route::match(['get','post'],'/admin-edit-service/{id?}','adminEditService')->name('admin.edit.service')->permission('admin-service-edit');
            Route::get('/admin-search','adminSearchService')->name('admin.search.service');
            Route::get('/admin-paginate','adminPaginate')->name('admin.paginate.service');
            Route::post('/admin-delete/{id}','adminServiceDelete')->name('admin.delete.service')->permission('admin-service-delete');
            Route::post('/admin-bulk-action','bulkAction')->name('admin.bulk.action.service')->permission('admin-service-bulk-delete');
            Route::post('/admin-published/{id}','adminServicePublishedStatus')->name('admin.service.published.status.change.by')->permission('admin-service-published-status-change');
            Route::post('/admin-status/{id}','adminChangeStatus')->name('admin.service.status.change.by')->permission('admin-service-status-change');
        });

        // admin service Location
        Route::controller(AdminServiceLocationController::class)->group(function () {
            Route::get('/admin-service-location','serviceLocation')->name('admin.service.location.page');
            Route::post('/adminServiceLocationUpdate','serviceLocationUpdate')->name('admin.service.location');
        });
        

    });

    // admin service staff manage
    Route::group(['prefix' => 'staff'],function(){
        Route::controller(AdminStaffManageController::class)->group(function () {
            Route::match(['get','post'],'add-staff','add_staff')->name('admin.staff.add');
            Route::get('/all-staff','all_staffs')->name('admin.staff.all');
            Route::match(['get','post'],'edit-user-info/{id}','edit_info')->name('admin.staff.info.edit');
            Route::post('/status/{id}','changeStatus')->name('admin.staff.status');
            Route::post('/delete/{id}','staffDelete')->name('admin.staff.delete');
            Route::get('/search','search_staff')->name('admin.staff.search');
            Route::get('/paginate','staff_paginate')->name('admin.staff.paginate.data');
        });
    });

    // admin service schedule manage
    Route::group(['prefix' => 'service/schedule'],function(){
        Route::controller(AdminScheduleManageController::class)->group(function () {
            Route::match(['get','post'],'add-schedule','add_schedule')->name('admin.schedule.add');
            Route::get('/list','all_schedules')->name('admin.schedule.all');
            Route::post('/edit-schedule','edit_schedule')->name('admin.schedule.info.edit');
            Route::post('/delete/{id}','scheduleDelete')->name('admin.schedule.delete');
            Route::get('/search','search_schedule')->name('admin.schedule.search');
            Route::get('/paginate','schedule_paginate')->name('admin.schedule.paginate.data');
        });
    });


    //all client  manage
    Route::group(['prefix' => 'user'],function(){
        Route::controller(\App\Http\Controllers\Backend\UserManageController::class)->group(function () {
            // add client or provider
            Route::match(['get','post'],'add-user','add_user')->name('admin.user.add')->permission('user-add');
            Route::get('all-users','all_users')->name('admin.user.all')->permission('user-list');
            Route::get('paginate/data/user', 'user_pagination')->name('admin.user.paginate.data');
            Route::get('search/user', 'search_user')->name('admin.user.search');
            Route::post('edit-user-info','edit_info')->name('admin.user.info.edit')->permission('user-edit');
            Route::post('change-user-password','change_password')->name('admin.user.password.change')->permission('user-password-change');
            Route::post('identity-details','identity_details')->name('admin.user.identity.details');
            Route::post('identity-verify/status','identity_verify_status')->name('admin.user.identity.verify.status')->permission('user-verify-status');
            Route::post('identity-verify/decline','identity_verify_decline')->name('admin.user.identity.verify.decline')->permission('user-verify-decline');

            Route::post('change-user-active-inactive-status/{id}','change_status')->name('admin.user.status')->permission('user-status-change');
            Route::post('delete/{id}','delete_user')->name('admin.user.delete')->permission('user-delete');
            Route::post('permanent-delete/{user_id}','permanent_delete')->name('admin.user.permanent.delete')->permission('user-permanent-delete');
            Route::match(['get','post'],'user-restore/{id?}','user_restore')->name('admin.user.restore');
            Route::get('paginate/delete/data', 'pagination_delete_user')->name('admin.user.paginate.delete.data');
            Route::get('delete/search-user', 'search_delete_user')->name('admin.user.delete.search');

            Route::get('verification-request','verification_requests')->name('admin.user.verification.request');
            Route::get('verification-request/paginate/data', 'verification_request_pagination')->name('admin.user.identity.request.paginate.data');
            Route::get('verification-request/search-user', 'verification_request_search_user')->name('admin.user.identity.request.search');
            Route::post('disable-2-factor-authentication/{id}','disable_2fa')->name('admin.user.disable._2fa');
            Route::post('verify-user-email/{id}','verify_user_email')->name('admin.user.verify.email');

            Route::get('deactivated/users-all', 'user_deactivated_all')->name('admin.user.deactivated.all')->permission('user-deactivated-list');
            Route::get('paginate/deactivated-user', 'user_deactivated_pagination')->name('admin.user.paginate.deactivated');
            Route::get('search/deactivated-user', 'search_deactivated_user')->name('admin.user.search.deactivated');
        });
    });

       Route::get('/user/profile/{id?}', [AdminProviderController::class, 'providerDetails'])->name('admin.provider.details.page');

    /*------------------ ADMIN CATEGORY MANAGE --------------*/
        Route::prefix('category')->group(function (){
            Route::get('/index',[CategoryController::class, 'index'])->name('admin.category')->permission('category-list');
            Route::match(['get','post'],'/add-new-category',[CategoryController::class, 'addNewCategory'])->name('admin.category.new')->permission('category-add');
            Route::match(['get','post'],'/edit-category/{id?}',[CategoryController::class, 'editCategory'])->name('admin.category.edit')->permission('category-edit');
            Route::post('/change-status/{id}',[CategoryController::class, 'changeStatus'])->name('admin.category.status')->permission('category-status-change');
            Route::post('/delete/{id}',[CategoryController::class, 'deleteCategory'])->name('admin.category.delete')->permission('category-delete');
            Route::post('/bulk-action', [CategoryController::class, 'bulkAction'])->name('admin.category.bulk.action')->permission('category-bulk-delete');
            Route::get('/search',[CategoryController::class, 'searchCategory'])->name('admin.category.search');
            Route::get('/paginate',[CategoryController::class, 'paginate'])->name('admin.category.paginate');
        });

        /*------------------ ADMIN SUBCATEGORY MANAGE --------------*/
        Route::prefix('subcategory')->group(function (){
            Route::get('/index',[SubCategoryController::class, 'index'])->name('admin.subcategory')->permission('subcategory-list');
            Route::match(['get','post'],'/add-new-subcategory',[SubcategoryController::class, 'addNewSubcategory'])->name('admin.subcategory.new')->permission('subcategory-add');
            Route::match(['get','post'],'/edit-subcategory/{id?}',[SubcategoryController::class, 'editSubcategory'])->name('admin.subcategory.edit')->permission('subcategory-edit');
            Route::post('/change-status/{id}',[SubcategoryController::class, 'changeStatus'])->name('admin.subcategory.status')->permission('subcategory-status-change');
            Route::post('/delete/{id}',[SubcategoryController::class, 'deleteSubcategory'])->name('admin.subcategory.delete')->permission('subcategory-delete');
            Route::post('/bulk-action', [SubcategoryController::class, 'bulkAction'])->name('admin.subcategory.bulk.action')->permission('subcategory-bulk-delete');
            Route::get('/search',[SubcategoryController::class, 'searchSubCategory'])->name('admin.subcategory.search');
            Route::get('/paginate',[SubcategoryController::class, 'paginate'])->name('admin.subcategory.paginate');
        });

        /*------------------ ADMIN SUBCATEGORY MANAGE --------------*/
        Route::prefix('child-category')->group(function (){
            Route::get('/index',[ChildCategoryController::class, 'index'])->name('admin.child.category')->permission('child-category-list');
            Route::match(['get','post'],'/add-new-child-category',[ChildCategoryController::class, 'addNewChildCategory'])->name('admin.child.category.new')->permission('child-category-add');
            Route::match(['get','post'],'/edit-child-category/{id?}',[ChildCategoryController::class, 'editChildCategory'])->name('admin.child.category.edit')->permission('child-category-edit');
            Route::post('/change-status/{id}',[ChildCategoryController::class, 'changeStatus'])->name('admin.child.category.status')->permission('child-category-status-change');
            Route::post('/delete/{id}',[ChildCategoryController::class, 'deleteChildCategory'])->name('admin.child.category.delete')->permission('child-category-delete');
            Route::post('/bulk-action', [ChildCategoryController::class, 'bulkAction'])->name('admin.child.category.bulk.action')->permission('child-category-bulk-delete');
            Route::get('/search',[ChildCategoryController::class, 'searchChildCategory'])->name('admin.child.category.search');
            Route::get('/paginate',[ChildCategoryController::class, 'paginate'])->name('admin.child.category.paginate');

            // get sub category for select
            Route::post('/admin-get-dependent-subcategory',[ChildCategoryController::class,'getSubcategory'])->name('admin.select.subcategory');
            Route::get('/get-subcategory-by-category',[ChildCategoryController::class,'getSubCategoryByCategoryId'])->name('admin.get.subcategory.by.category');
        });

        /*------------------ ADMIN PAGE MANAGE --------------*/
        Route::prefix('page-builder')->group(function (){
            Route::post('/update', [PageBuilderController::class, 'updateAddonContent'])->name('admin.page.builder.update');
            Route::post('/new', [PageBuilderController::class, 'storeNewAddonContent'])->name('admin.page.builder.new');
            Route::post('/delete', [PageBuilderController::class, 'delete'])->name('admin.page.builder.delete');
            Route::post('/update-order', [PageBuilderController::class, 'updateAddonOrder'])->name('admin.page.builder.update.addon.order');
            Route::post('/get-admin-markup', [PageBuilderController::class, 'getAdminPanelAddonMarkup'])->name('admin.page.builder.get.addon.markup');
        });

        /*------------------ ADMIN DYNAMIC Dynamic PAGE ROUTES --------------*/
        Route::prefix('dynamic-page')->group(function (){
            Route::get('/all',[PagesController::class, 'index'])->name('admin.page')->permission('dynamic-page-list');
            Route::get('/new',[PagesController::class, 'newPage'])->name('admin.page.new')->permission('dynamic-page-add');
            Route::post('/new',[PagesController::class, 'storeNewPage']);
            Route::get('/edit/{id}',[PagesController::class, 'editPage'])->name('admin.page.edit')->permission('dynamic-page-edit');
            Route::post('/update/{id}',[PagesController::class, 'updatePage'])->name('admin.page.update');
            Route::post('/delete/{id}',[PagesController::class, 'deletePage'])->name('admin.page.delete')->permission('dynamic-page-delete');
            Route::post('/delete/lang/all/{id}',[PagesController::class, 'deletePageLangAll'])->name('admin.page.delete.lang.all');
            Route::post('/bulk-action',[PagesController::class, 'bulkAction'])->name('admin.page.bulk.action')->permission('dynamic-page-bulk-delete');

            Route::get('/search',[PagesController::class, 'searchPage'])->name('admin.page.search');
            Route::get('/paginate',[PagesController::class, 'paginate'])->name('admin.page.paginate');
        });

 /*------------------ ADMIN Offer ROUTES --------------*/
        Route::group(['prefix' => 'offer'],function(){
            Route::controller(OfferController::class)->group(function () {
                Route::match(['get','post'],'add','addOffer')->name('admin.offer.add')->permission('offer-add');
                Route::get('/list','allOffers')->name('admin.offer.all')->permission('offer-list');
                Route::match(['get','post'],'/edit-offer/{id}/{serviceId?}','adminEditOffer')->name('admin.offer.edit');
               // Route::post('/edit-brand/{id}','editBrand')->name('admin.brand.edit');
                Route::get('/details/{id}','offerDetails')->name('admin.offer.details');
                Route::post('/delete/{id}','offerDelete')->name('admin.offer.delete');
               Route::get('/search','offerSearch')->name('admin.offer.search');
               Route::get('/paginate','offerPaginate')->name('admin.offer.paginate');
               Route::post('/admin-status/{id}','adminChangeStatus')->name('admin.offer.status.change')->permission('offer-status-change');
               Route::post('/admin-primary/{id}','adminChangePrimaryOption')->name('admin.offer.primaryOption.change')->permission('offer-primaryOption-change');
               Route::post('/bulk-action','bulkAction')->name('admin.offer.bulk.action')->permission('admin-offer-bulk-delete');
            });
        });


        /*------------------ ADMIN PAGE BUILDER ROUTES --------------*/
        Route::group(['prefix' => 'page-builder','middleware' => 'auth:admin','setlang'],function () {
            Route::get('/home-page', [PageBuilderController::class, 'homePageBuilder'])->name('admin.home.page.builder');
            Route::post('/home-page', [PageBuilderController::class, 'updateHomePageBuilder']);
            Route::get('/about-page', [PageBuilderController::class, 'aboutPageBuilder'])->name('admin.about.page.builder');
            Route::post('/about-page', [PageBuilderController::class, 'updateAboutPageBuilder']);
            Route::get('/contact-page', [PageBuilderController::class, 'contactPageBuilder'])->name('admin.contact.page.builder');
            Route::post('/contact-page', [PageBuilderController::class, 'updateContactPageBuilder']);
            Route::get('/dynamic-page/{type}/{id}', [PageBuilderController::class, 'dynamicPageBuilder'])->name('admin.dynamic.page.builder');
            Route::post('/dynamic-page', [PageBuilderController::class, 'updateDynamicPageBuilder'])->name('admin.dynamic.page.builder.store');
        });


        /*------------------ ADMIN Google Map SETTINGS  --------------*/
        Route::prefix('map-settings')->group(function (){
            Route::get('/add-page', [MapSettings::class, 'addMapSettings'])->name('admin.map.settings.page')->permission('google-map-settings');
            Route::post('/add-page', [MapSettings::class, 'UpdateMapSettings']);
        });

       /*------------------ ADMIN Appearance SETTINGS  --------------*/
        Route::prefix('appearance-settings')->group(function (){
            // Color Settings
            Route::get('/color-settings',[GeneralSettingsController::class, 'colorSettings'])->name('admin.general.color.settings')->permission('color-settings');
            Route::post('/color-settings',[GeneralSettingsController::class, 'updateColorSettings']);

            // media upload
            Route::get('/media-upload/page',[MediaUploadController::class, 'allUploadMediaImagesForPage'])->name('admin.upload.media.images.page')->permission('media-upload');
            Route::post('/media-upload/delete',[MediaUploadController::class, 'deleteUploadMediaFile'])->name('admin.upload.media.file.delete')->permission('media-upload-delete');

            //404 page manage
            Route::get('404-page-manage',[Manage404PageController::class, 'error404pageSettings'])->name('admin.404.page.settings')->permission('404-page-settings');
            Route::post('404-page-manage',[Manage404PageController::class, 'update404PageSettings']);

            // maintains page
            Route::get('/maintains-page',[MaintainsPageController::class, 'maintainsPageSettings'])->name('admin.maintains.page.settings')->permission('maintains-page-settings');
            Route::post('/maintains-page-update',[MaintainsPageController::class, 'updateMaintainsPageSettings'])->name('admin.maintains.page.update.settings');

        });

        /*------------------ ADMIN NOTICE SETTINGS  --------------*/
         Route::controller(AdminNotificationController::class)->group(function () {
            Route::prefix('notification')->group(function (){
                Route::get('/all','all_notification')->name('admin.notification.all')->permission('notifications-list');
                Route::post('all/read','read_notification')->name('admin.notification.read');
                Route::get('search-notification', 'search_notification')->name('admin.notification.search');
                Route::get('paginate/data', 'pagination')->name('admin.notification.paginate.data');
               
            });
        });
        Route::post('/notification/send/client',[ClientOfferController::class,'sendOfferNotification'])->name("Client.sendNotification");
        Route::post('/notification/send/provider',[ProviderOfferController::class,'sendOfferNotification'])->name("Provider.sendNotification");

        /*------------------ ADMIN NOTICE SETTINGS  --------------*/
        Route::prefix('notice')->group(function (){
            Route::get('/all',[NoticeController::class, 'allNotice'])->name('admin.all.notice')->permission('notice-list');
            Route::get('/add/page',[NoticeController::class, 'addNoticePage'])->name('admin.add.notice.page')->permission('notice-add');
            Route::post('/add',[NoticeController::class, 'addNotice'])->name('admin.add.notice');
            Route::get('/edit/{id}',[NoticeController::class, 'noticeEdit'])->name('admin.notice.edit')->permission('notice-edit');
            Route::post('/update',[NoticeController::class, 'noticeUpdate'])->name('admin.notice.update');
            Route::post('/delete-user/{id}',[NoticeController::class, 'newNoticeDelete'])->name('admin.delete.notice')->permission('notice-delete');
            Route::post('/status/{id}',[NoticeController::class, 'changeStatus'])->name('admin.notice.status')->permission('notice-status-change');
            Route::get('/search',[NoticeController::class, 'searchNotice'])->name('admin.notice.search');
            Route::get('/paginate',[NoticeController::class, 'paginate'])->name('admin.notice.paginate');
        });

        /*------------------ ADMIN ALL PAGE SETTINGS  --------------*/
        Route::prefix('page-settings')->group(function (){
            Route::match(['get', 'post'], '/register-page', [PageSettingsController::class, 'loginRegisterPageSettings'])->name('admin.login.register.page.settings')->permission('login-register-page-settings');
            Route::match(['get', 'post'], '/service-create-page/settings', [PageSettingsController::class, 'serviceCreateSettings'])->name('admin.service.create.settings')->permission('service-create-page-settings');
            Route::match(['get', 'post'], '/user-public-profile/settings', [PageSettingsController::class, 'userPublicProfileSettings'])->name('admin.user.public.profile.settings')->permission('user-public-profile-page-settings');
            Route::match(['get', 'post'], '/admin-login-page/settings', [PageSettingsController::class, 'adminLoginPageSettings'])->name('admin.login.page.settings')->permission('user-public-profile-page-settings');
        });

        /*------------------ EMAIL SETTINGS MANAGE --------------*/
        Route::prefix('email-settings')->group(function (){
            Route::post('/basic-settings',[EmailSettingsController::class, 'updateEmailSettings']);
            //smtp settings
            Route::get('/smtp',[EmailSettingsController::class, 'smtpSettings'])->name('admin.email.smtp.settings')->permission('smtp-settings');
            Route::post('/update-smtp',[EmailSettingsController::class, 'updateSmtpSettings'])->name('admin.email.smtp.update.settings');
            Route::post('/test-smtp', [EmailSettingsController::class, 'testSmtpSettings'])->name('admin.email.smtp.settings.test');

            //All Email  Templates
            Route::get('/all-email-templates', [EmailTemplateController::class, 'allEmailTemplates'])->name('admin.email.template.all');
            Route::match(['get', 'post'], '/global-template', [EmailTemplateController::class, 'globalEmailTemplateSettings'])->name('admin.email.global.template');
            Route::match(['get', 'post'], '/user/register/template', [EmailTemplateController::class, 'userRegisterTemplate'])->name('admin.email.user.register.template');
            Route::match(['get', 'post'], '/user/identity-verification/template', [EmailTemplateController::class, 'userIdentityVerificationTemplate'])->name('admin.email.user.identity.verification.template');
            Route::match(['get', 'post'], '/user/email-verify/template', [EmailTemplateController::class, 'userEmailVerifyTemplate'])->name('admin.email.user.verify.template');
            Route::match(['get', 'post'], '/user/wallet-deposit/template', [EmailTemplateController::class, 'userWalletDepositTemplate'])->name('admin.email.user.wallet.deposit.template');
            Route::match(['get', 'post'], '/user/new-service-approval/template', [EmailTemplateController::class, 'userNewServiceApprovalTemplate'])->name('admin.email.user.new.service.approval.template');
            Route::match(['get', 'post'], '/user/new-service-publish/template', [EmailTemplateController::class, 'userNewServicePublishTemplate'])->name('admin.email.user.new.service.publish.template');
            Route::match(['get', 'post'], '/user/new-service-unpublished/template', [EmailTemplateController::class, 'userNewServiceUnpublishedTemplate'])->name('admin.email.user.new.service.unpublished.template');

            // order email template
            Route::match(['get', 'post'], '/orders/new-order/template', [EmailTemplateController::class, 'newOrderTemplate'])->name('admin.email.new.order.template');
     });

        /*------------------ GENERAL SETTINGS MANAGE --------------*/
        Route::prefix('general-settings')->group(function (){
            Route::get('/site-identity',[GeneralSettingsController::class, 'siteIdentity'])->name('admin.general.site.identity')->permission('site-identity-settings');
            Route::post('/site-identity',[GeneralSettingsController::class, 'updateSiteIdentity']);

            Route::get('/basic-settings',[GeneralSettingsController::class, 'basicSettings'])->name('admin.general.basic.settings')->permission('basic-settings');
            Route::post('/basic-settings',[GeneralSettingsController::class, 'updateBasicSettings']);

            Route::get('/scripts',[GeneralSettingsController::class, 'scriptsSettings'])->name('admin.general.scripts.settings')->permission('scripts-settings');
            Route::post('/scripts',[GeneralSettingsController::class, 'updateScriptsSettings']);

            //license-setting
            Route::get('/license-setting',[GeneralSettingsController::class, 'licenseSettings'])->name('admin.general.license.settings')->permission('license-setting');
            Route::post('/license-setting',[GeneralSettingsController::class, 'updateLicenseSettings']);

            //cache settings
            Route::get('/cache-settings',[GeneralSettingsController::class, 'cacheSettings'])->name('admin.general.cache.settings')->permission('cache-setting');
            Route::post('/cache-settings',[GeneralSettingsController::class, 'updateCacheSettings']);

            //database upgrade
            Route::get('/database-upgrade', [GeneralSettingsController::class, 'databaseUpgrade'])->name('admin.general.database.upgrade')->permission('database-upgrade-setting');
            Route::post('/database-upgrade', [GeneralSettingsController::class, 'databaseUpgradePost']);

            Route::post('/license-setting-verify', [GeneralSettingsController::class, 'licenseKeyGenerate'])->name('admin.general.license.key.generate')->permission('license-key-generate');
            Route::get('/update-check', [GeneralSettingsController::class, 'updateVersionCheck'])->name('admin.general.update.version.check')->permission('update-version-check');
            Route::post('/download-update/{productId}/{tenant}', [GeneralSettingsController::class, 'updateDownloadLatestVersion'])->name('admin.general.update.download.settings');
            Route::get('/software-update-setting', [GeneralSettingsController::class, 'softwareUpdateCheckSettings'])->name('admin.general.software.update.settings')->permission('software-update-settings');
        });


   // manage withdraw
    Route::prefix('withdraw/')->group(function (){
         Route::controller(WithdrawGatewayController::class)->group(function () {
            Route::get('gateway/settings','gateway_settings')->name('admin.withdraw.gateway')->permission('withdraw-payment-gateway-list');
            Route::match(['get','post'],'settings', 'withdraw_settings')->name('admin.withdraw.settings')->permission('withdraw-settings-view');
            Route::post('gateway/create','gateway_create')->name('admin.withdraw.gateway.create')->permission('withdraw-payment-gateway-add');
            Route::post('gateway/update/{id?}','gateway_update')->name('admin.withdraw.gateway.update')->permission('withdraw-payment-gateway-edit');
            Route::post('change-status/{id}','change_status')->name('admin.withdraw.gateway.status')->permission('withdraw-payment-status-change');
            Route::post('delete-gateway/{id}', 'delete_gateway')->name('admin.withdraw.gateway.delete')->permission('withdraw-payment-gateway-delete');
            Route::get('request/all','withdraw_request')->name('admin.withdraw.request')->permission('withdraw-list');
            Route::get('request/details/{id?}','withdraw_request_details')->name('admin.withdraw.request.details')->permission('withdraw-list');
            Route::post('request/update','withdraw_request_update')->name('admin.withdraw.request.update')->permission('withdraw-status-change');
            Route::get('request/paginate/data', 'pagination')->name('admin.withdraw.paginate.data');

            // withdraw fee system
             Route::match(['get','post'],'fee/settings','withdraw_fee_settings')->name('admin.withdraw.fee.settings')->permission('withdraw-fee-settings-view');
        });
    });

      // manage refund
   Route::prefix('refund/')->group(function (){
    Route::controller(RefundGatewayController::class)->group(function () {
       Route::get('gateway/settings','gateway_settings')->name('admin.refund.gateway')->permission('refund-payment-gateway-list');
       Route::post('gateway/create','gateway_create')->name('admin.refund.gateway.create')->permission('refund-payment-gateway-add');
       Route::post('gateway/update/{id?}','gateway_update')->name('admin.refund.gateway.update')->permission('refund-payment-gateway-edit');
       Route::post('change-status/{id}','change_status')->name('admin.refund.gateway.status')->permission('refund-payment-status-change');
       Route::post('delete-gateway/{id}', 'delete_gateway')->name('admin.refund.gateway.delete')->permission('refund-payment-gateway-delete');
      
   });
});

    // mobile slider manage
    Route::prefix('slider/')->group(function (){
         Route::controller(SliderController::class)->group(function () {
             Route::match(['get','post'],'/add','add_new_slider')->name('admin.slider.add');
             Route::match(['get','post'],'/edit/{id?}','edit_slider')->name('admin.slider.edit');
             Route::post('/delete/{id}','delete_slider')->name('admin.slider.delete');
             Route::post('/bulk-action', 'bulk_action')->name('admin.slider.bulk.action');
        });
    });

    //  report reasons
    Route::controller(ReportReasonController::class)->group(function () {
        Route::match(['get','post'],'report/reason/all','all_reason')->name('admin.report.reason.all')->permission('report-reason-list');
        Route::post('report/reason/edit-reason','edit_reason')->name('admin.report.reason.edit')->permission('report-reason-edit');
        Route::post('report/reason/delete/{id}','delete_reason')->name('admin.report.reason.delete')->permission('report-reason-delete');
        Route::post('report/reason/bulk-action', 'bulk_action_reason')->name('admin.report.reason.delete.bulk.action')->permission('report-reason-bulk-delete');
        Route::get('report/reason/paginate/data', 'pagination')->name('admin.report.reason.paginate.data');
        Route::get('report/reason/search', 'search_reason')->name('admin.report.reason.search');
    });
    // firebase settings
    Route::match(['get','post'],'firebase/settings', [FireBaseSettingsController::class, 'uploadFirebaseJson'])->name('admin.firebase.settings');

});

    // media upload routes end
    Route::post('/media-upload/all', [MediaUploadController::class,'allUploadMediaFile'])->name('admin.upload.media.file.all');
    Route::post('/media-upload', [MediaUploadController::class,'uploadMediaFile'])->name('admin.upload.media.file');
    Route::post('/media-upload/alt', [MediaUploadController::class,'altChangeUploadMediaFile'])->name('admin.upload.media.file.alt.change');
    // media upload routes for restrict user in demo mode
    Route::post('/media-upload/loadmore',  [MediaUploadController::class,'getImageForLoadmore'])->name('admin.upload.media.file.loadmore');

