<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (Request $request) {
    return 'Get request not supported for this api';
});

Route::get('/token-mismatch', 'ApiController@tokenMisMatch')->name('tokenMisMatch');

Route::post('/get-all-class', 'ApiController@getAllClass')->name('get-all-class');

Route::post('/get-subject', 'ApiController@getClassWiseSubjects')->name('get-subject');

Route::post('/create-user', 'ApiController@createNewUser')->name('create-user');

Route::post('/verify-email-otp', 'ApiController@verifyEmailOtp')->name('verify-email-otp');

Route::post('/login', 'ApiController@loginUser')->name('login');

Route::post('/forgot-password', 'ApiController@forgotPassword')->name('forgot-password');

Route::middleware('auth:api')->post('/verify-token', 'ApiController@verifyToken')->name('verify-token');

Route::middleware('auth:api')->post('/logout', 'ApiController@logoutUser')->name('logout');

Route::middleware('auth:api')->post('/get-videos', 'ApiController@getVideos')->name('get-videos');

Route::middleware('auth:api')->post('/videos-view', 'ApiController@updateVideoView')->name('videos-view');

Route::middleware('auth:api')->post('/pause-video', 'ApiController@savePauseVideo')->name('pause-video');

Route::middleware('auth:api')->post('/get-user-profile', 'ApiController@getUserProfileDetails')->name('get-user-profile');

Route::middleware('auth:api')->post('/update-user-profile', 'ApiController@updateUserProfileDetails')->name('update-user-profile');

Route::middleware('auth:api')->post('/get-user-class', 'ApiController@getUserClass')->name('get-user-class');

Route::middleware('auth:api')->post('/create-new-payment', 'ApiController@initializeNewPayment')->name('create-new-payment');

Route::middleware('auth:api')->post('/update-payment', 'ApiController@updatePayment')->name('update-payment');

Route::middleware('auth:api')->post('/get-active-subscription', 'ApiController@getUserActiveSubscription')->name('get-active-subscription');
