<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check()) {
        return redirect('dashboard');
    } else {
        return redirect('login');
    }
});

Route::get('/password-reset', 'ApiController@getUserPasswordReset')->name('password-reset');
Route::post('/password-reset', 'ApiController@postUserPasswordReset')->name('password-reset');

Auth::routes();

Route::get('/home', 'DashboardController@index')->name('home');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

/*--------------------------SUBJECTS Routes--------------------------------*/
Route::get('/subjects', 'SubjectController@viewAllSubject')->name('view-subject');
Route::post('/add-subject', 'SubjectController@postCreateSubject')->name('add-subject');
Route::post('/update-subject', 'SubjectController@postUpdateSubject')->name('update-subject');
Route::delete('/delete-subject', 'SubjectController@postDeleteSubject')->name('delete-subject');

/*--------------------------CLASSES Routes--------------------------------*/
Route::get('/classes', 'ClassController@viewAllClass')->name('view-classes');
Route::post('/add-class', 'ClassController@postCreateClass')->name('add-class');
Route::post('/update-class', 'ClassController@postUpdateClass')->name('update-class');
Route::delete('/delete-class', 'ClassController@postDeleteClass')->name('delete-class');

/*--------------------------Videos Routes--------------------------------*/
Route::get('/view-videos', 'VideoController@viewAllVideos')->name('view-videos');
Route::post('/get-class-subjects', 'VideoController@getClassOfSubjects')->name('get-class-subjects');
Route::get('/add-video', 'VideoController@getAddVideoView')->name('add-video');
Route::post('/add-video', 'VideoController@postAddVideo')->name('add-video');
Route::post('/get-filters-videos', 'VideoController@getFiltersVideos')->name('get-filters-videos');

/*--------------------------View Users Routes--------------------------------*/
Route::get('/users', 'UserController@viewAllUsers')->name('view-users');

/*--------------------------View Payment Routes--------------------------------*/
Route::get('/payments', 'PaymentController@viewPaymentDetails')->name('payments');
Route::post('/get-payments-data', 'PaymentController@getPaymentDetails')->name('get-payments-data');
