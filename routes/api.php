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
    Route::post('device_tokens', 'DeviceTokenController@store')->name('tokens.store');

    Route::get('tasks/{offset}/{limit}', 'TaskController@index')->name('tasks.index');
    Route::get('tasks/{task}', 'TaskController@show')->name('tasks.show');
    Route::post('tasks', 'TaskController@store')->name('tasks.store');
    Route::put('tasks/{task}/status', 'TaskController@updateStatus');
    Route::put('tasks/{task}', 'TaskController@update')->name('tasks.update');
    Route::delete('tasks/{task}', 'TaskController@destroy')->name('tasks.destroy');

    Route::get('users/{offset}/{limit}', 'UsersController@index')->name('users.index');
    Route::get('users/{user}', 'UsersController@show')->name('users.show');
    Route::get('users/{user}', 'UsersController@show')->name('users.show');
    Route::put('users/{user}', 'UsersController@update')->name('users.update');
    Route::post('users/image', 'UsersController@updateImage')->name('users.updateimage');

    Route::post('comments', 'CommentController@store')->name('api.comments.store');
});
