<?php

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

Route::post('/token', 'Auth\LoginController@apiLogin');
Route::post('/login', 'Auth\LoginController@apiLogin');

Route::post('/register','Auth\RegisterController@register');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

Route::group(['middleware' => ['auth:sanctum']], function () {
    // User account ops
    Route::put('users/{user}', 'UsersController@update')->name('users.update');
    Route::put('users/{user}/password', 'UsersController@updatePassword')->name('users.updatepassword');
    Route::post('users/image', 'UsersController@updateImage')->name('users.updateimage');
    Route::put('users/{user}/settings', 'UsersController@updateSetting')->name('users.updatesettings');
    //Route::put('user/{user}/settings', 'SettingsController@saveUserSettings');
    Route::post('device_tokens', 'DeviceTokenController@store')->name('tokens.store');

    // Task operations
    Route::get('tasks/{offset}/{limit}', 'TaskController@index')->name('api.tasks.index');
    Route::get('tasks/{task}', 'TaskController@show')->name('api.tasks.show');
    Route::post('tasks', 'TaskController@store')->name('api.tasks.store');
    Route::put('tasks/{task}/status', 'TaskController@updateStatus');
    Route::put('tasks/{task}', 'TaskController@update')->name('api.tasks.update');
    Route::delete('tasks/{task}', 'TaskController@destroy')->name('api.tasks.destroy');

    // Users directory
    Route::get('users/{offset}/{limit}', 'UsersController@index')->name('users.index');
    Route::get('members/{user}/{offset}/{limit}', 'UsersController@getUserTeamMembers')->name('members.index');
    Route::get('users/{user}', 'UsersController@show')->name('users.show');

    Route::post('comments', 'CommentController@store')->name('api.comments.store');

    // Messages
    Route::get('messages', 'MessagesController@index')->name('messages.index');
    Route::get('messages/{id}', 'MessagesController@show')->name('messages.show');
    Route::post('messages', 'MessagesController@store')->name('messages.store');
    Route::put('messages/{id}', 'MessagesController@update')->name('messages.update');
    Route::delete('messages/{id}', 'MessagesController@destroy')->name('messages.destroy');
});
