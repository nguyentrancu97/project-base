<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'auth/users'], function () {
    Route::post('login', 'AuthController@login');
});
Route::post('users/save-token-device', 'UsersController@saveTokenDevice');
Route::post('users/forgot-password', 'UsersController@forgotPassword');
Route::post('users/reset-password', 'UsersController@resetPassword');
Route::group(['middleware' => ['auth:admin']], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/me', 'UsersController@me');
        Route::put('/me','UsersController@updateMe');
        Route::put('/avatar/me', 'UsersController@updateAvatarMe');
        Route::put('/avatar/{user_id}', 'UsersController@updateAvatar')->middleware('permission:users.update');
        Route::get('/', 'UsersController@index')->middleware('permission:users.browse');
        Route::post('/','UsersController@store')->middleware('permission:users.create');
        Route::put('/{user_id}', 'UsersController@update')->middleware('permission:users.update');
        Route::delete('/{user_id}', 'UsersController@destroy')->middleware('permission:users.destroy');
        Route::put('me/change-password', 'UsersController@changePassword');
        Route::get('/{user_id}', 'UsersController@show')->middleware('permission:users.browse');
        Route::post('/switch-user', 'UsersController@switchUser');
    });

    Route::group(['prefix'=>'roles'], function (){
        Route::get('/','RolesController@index')->middleware('permission:roles.browse');
    });

    Route::group(['prefix' => 'timezones'], function (){
        Route::get('/', 'UTCTimeController@index')->middleware('permission:timezones.browse');
    });
});
