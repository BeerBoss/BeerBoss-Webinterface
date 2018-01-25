<?php

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

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::view('/home', 'home')->name('home');
    Route::view('/stats/temperature', 'stats.temperature')->name('tempStats');
    Route::view('/stats/connection', 'stats.connection')->name('connStats');
    Route::view('/profiles/manage', 'actions.profiles')->name('manageProfiles');

    //Internal api
    Route::get('/stats/connection/get', 'ConnectionController@getLastConnection')->name('getConnStats');
    Route::get('/stats/temperature/get', 'SensorDataController@getTemps')->name('getTemps');
    Route::get('/stats/temperature/get/last', 'SensorDataController@getLastTemp')->name('getLastTemp');
    Route::get('/stats/temperature/get/daily', 'SensorDataController@getDailyTemps')->name('getDailyTemps');

    Route::get('/profiles/get', 'ProfileController@get')->name('getProfiles');
    Route::post('/profiles/save', 'ProfileController@save')->name('saveProfile');
    Route::delete('/profiles/{id}', 'ProfileController@delete')->name('deleteProfile');
    Route::get('/profiles/{id}/toggle', 'ProfileController@toggle')->name('toggleProfile');
    Route::get('/profiles/get/active', 'ProfileController@getActive')->name('getActiveProfile');

});

Route::redirect('/', '/home');
