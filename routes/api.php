<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => ['api'],
],
    function () {

        // Shop
        Route::group([
            'middleware' => ['auth'],
            'namespace' => 'Shop',
            'prefix' => 'shop',
        ], function() {
            Route::get('products', 'ProductController@index')->name('products.index');
            Route::get('products/show', 'ProductController@show')->name('products.show');
            Route::get('products/total', 'ProductController@total')->name('products.total');
            Route::post('consultation_requests', 'ConsultationRequestController@store')->name('consultation_requests.store');
        });

        // Payment
        Route::group([
            'middleware' => ['auth'],
            'namespace' => 'Payment',
            'prefix' => 'payment',
        ], function() {
            // Payment Cards
            Route::get('cards', 'CardController@index')->name('cards.index');
            Route::post('cards', 'CardController@store')->name('cards.store');
            Route::get('card/show', 'CardController@show')->name('cards.show');
            Route::delete('card/destroy', 'CardController@destroy')->name('cards.destroy');
        });

        // Visible (no OAuth required)
        Route::group([
            'namespace' => 'Visible',
            'prefix' => 'visible',
        ], function() {
            Route::resource('regions', RegionController::class);
            Route::resource('cities', CityController::class);
            Route::resource('partners', PartnerController::class);
        });

    }
);

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('user_types', 'AuthController@types');
    Route::post('login', 'AuthController@login');
    Route::post('refresh_token', 'AuthController@refreshToken');
    Route::post('forgot', 'AuthController@forgot');
    Route::get('is_login', 'AuthController@isLogin');
    Route::post('register_check_email', 'AuthController@registerCheckEmail');
    Route::post('register_check_phone', 'AuthController@registerCheckPhone');
    Route::post('register_phone_verify', 'AuthController@registerPhoneVerify');
    Route::post('register', 'AuthController@register');
});

Route::group(
    [
        'namespace' => 'Account',
        'prefix' => 'account',
//        'middleware' => ['auth']
    ],
    function() {

        Route::group(
            [
                'middleware' => ['auth']
            ],
            function() {
                Route::resource('protected_objects', 'ProtectedObjectController')->only(['index', 'store']);
                Route::post('protected_object/update', 'ProtectedObjectController@update')->name('protected_object.update');
                Route::get('protected_object/show', 'ProtectedObjectController@show')->name('protected_object.show');
                Route::get('protected_object/owners', 'ProtectedObjectController@owners')->name('protected_object.owners');

                /**
                 * Привязка устройства &
                 * Заявка на устройство
                 */
                Route::post('protected_objects/devices', 'ProtectedObjectDeviceController@store')->name('protected_objects.devices.store');
                Route::post('partner/applications', 'PartnerApplicationController@store')->name('partner.applications.store');
            });

        Route::get('protected_object/types', 'ProtectedObjectController@types');
        Route::get('protected_object/instructions', 'ProtectedObjectController@instructions');
    }
);

Route::group([
    'middleware' => 'api',
    'namespace' => 'Alarm',
], function() {
    Route::resource('panel-signals', 'PanelSignalsController')->only(['store']);
    Route::group([
        'middleware' => ['auth']
    ], function(){
        Route::resource('alarms', 'AlarmsController');
        Route::get('alarm/active', 'AlarmsController@active')->name('alarm.active');
        Route::get('alarm/cancel', 'AlarmsController@cancel')->name('alarm.cancel');
        Route::post('alarm/review', 'AlarmsController@review')->name('alarm.review');
    });
});
