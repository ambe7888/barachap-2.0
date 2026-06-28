<?php

use App\Http\Controllers\Api\AccountSettingController;
use App\Http\Controllers\api\AdminServiceScheduleController;
use App\Http\Controllers\Api\ApiLanguageController;
use App\Http\Controllers\Api\ApiSocialLoginController;
use App\Http\Controllers\Api\CategoryManageController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Orders\OrderController;
use App\Http\Controllers\Api\PaymentGatewayController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\Service\ServiceController;
use App\Http\Controllers\Api\FavoriteItemController;
use App\Http\Controllers\Api\Offer\OfferController;
use App\Http\Controllers\Api\SliderManageApiController;
use App\Http\Controllers\Api\StateCityAreaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserEmailVerifyController;
use App\Http\Controllers\Api\UserLocationController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\UserReviewController;
use App\Http\Controllers\Api\Orders\OrderCancelPolicyController;
use Illuminate\Support\Facades\Route;


require_once __DIR__ . '/providerApi.php';
require_once __DIR__ . '/clientApi.php';


Route::group(['prefix'=>'v1', 'middleware' => 'setlang'],function(){
    // user registration
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/send-otp-in-mail',[UserController::class,'sendOTP']);
    Route::post('/reset-password',[UserController::class,'resetPassword']);

    //offer
    Route::get('/primary-offer',[OfferController::class,'primaryOffer']);
    Route::get('/offer-list',[OfferController::class,'allActiveOffers']);
    Route::get('/offer-services/{offer_id}',[OfferController::class,'offerServices']);
     //order cancellation policy
     Route::group(['prefix' => 'order'],function (){
        Route::get('cancel-policy-details',[OrderCancelPolicyController::class,'cancelPolicyDetails']);
    });



    // provider profile
    Route::get('provider/profile/details/{id?}',[UserProfileController::class,'publicProviderProfile'])->name('api.provider.profile.details');

    // user Api Routes
    Route::group(['prefix' => 'user/','middleware' => 'auth:sanctum'],function (){
        Route::post('logout',[UserController::class,'logout']);
        Route::post('change-password',[UserController::class,'changePassword']);
        Route::post('/send-otp-in-mail/success',[UserEmailVerifyController::class,'sendOTPSuccess']);

        // change phone number
        Route::post('change-email',[UserController::class,'changeUserEmail']);
        Route::post('change-phone-number',[UserController::class,'changePhoneNumber']);

        // user profile update
        Route::group(['prefix' => 'profile'],function (){
            Route::get('/',[UserProfileController::class,'profile']);
            Route::post('update',[UserProfileController::class,'updateProfile']);
            Route::post('firebase-token',[UserProfileController::class,'firebaseToken']);
        });

        // user review
        Route::post('/review-add',[UserReviewController::class, 'orderReviewAdd']);

        //favorites items
        Route::group(['prefix' => 'favorites/'],function (){
            Route::post('add',[FavoriteItemController::class,'addFavorite']);
            Route::post('remove',[FavoriteItemController::class,'favoriteRemove']);
            Route::get('lists',[FavoriteItemController::class,'favoriteLists']);
        });

        // client and provider notifications
        Route::group(['prefix' => 'notifications/'],function (){
            Route::get('all',[NotificationController::class,'userAllNotification']);
            Route::get('clear',[NotificationController::class,'allClearMessage']);
            Route::post('read/{id?}',[NotificationController::class,'readNotificationSingle']);
        });

        // user account settings
        Route::group(['prefix' => 'settings/'],function (){
            Route::controller(AccountSettingController::class)->group(function () {
                Route::post('/account-deactive','accountDeactive');
                Route::post('/account-active','accountActive');
                Route::post('account-delete','accountDelete');
            });
        });
   });

    //service for app
    Route::group(['prefix' => 'service/'],function (){
        Route::get('/all-service',[ServiceController::class,'allServices']);
        Route::get('/details/{id}',[ServiceController::class,'serviceDetails'])->name('api.service.details');
        Route::get('schedule-lists/{day?}/{provider_id?}',[ServiceController::class,'scheduleByDay']);
        Route::get('staff-lists/{provider_id?}/{admin_id?}',[ServiceController::class,'staffList']);
        Route::get('popular',[ServiceController::class,'popularServices']);
        Route::get('featured',[ServiceController::class,'featuredServices']);
    });

    // order invoice generate
    Route::get('client/order/invoice-details/{id?}', [InvoiceApiController::class, 'orderInvoiceGenerate']);
    Route::get('provider/order/invoice-details/{id?}', [InvoiceApiController::class, 'providerOrderInvoiceGenerate']);

    // public all routes lists
    Route::get('/currency',[GeneralController::class,'currencyInfo']);
    Route::get('/admin-commission-type',[GeneralController::class,'adminCommissionType']);
    Route::get('/language',[ApiLanguageController::class,'languageInfo']);
    Route::post('/translate-string',[ApiLanguageController::class,'translateString']);
    Route::get('payment-gateway-list',[PaymentGatewayController::class,'gatewayList']);
    Route::get('slider-lists',[SliderManageApiController::class,'sliderLists']);
    Route::get('provider-lists',[HomeController::class,'providerLists']);
    Route::get('terms-and-conditions',[HomeController::class,'termsAndConditionsPage']);
    Route::get('privacy-policy',[HomeController::class,'privacyPolicyPage']);
    Route::get('contact',[HomeController::class,'contactPage']);
    Route::get('reasons',[HomeController::class,'reasonLists']);
    Route::get('email-verify/enable-disable-status',[HomeController::class,'emailVerifyEnableDisable']);
    Route::get('/categories-with-services',[CategoryManageController::class,'categoriesWithService']);
    //all categories
    Route::get('/categories',[CategoryManageController::class,'allCategory']);
    Route::get('/sub-categories',[CategoryManageController::class,'allSubCategory']);
    Route::get('/child-categories',[CategoryManageController::class,'allChildCategory']);
    //all state, city ,area
    Route::get('/states',[StateCityAreaController::class,'allState']);
    Route::get('/cities',[StateCityAreaController::class,'allCity']);
    Route::get('/areas',[StateCityAreaController::class,'allArea']);

    //social login
    Route::controller(ApiSocialLoginController::class)->group(function(){
        Route::post('social/login', 'social_login');
    });

});

Route::group(['prefix'=>'v1'],function(){
    Route::post('/translate-string-slug',[ApiLanguageController::class,'multiTranslateString']);
});


