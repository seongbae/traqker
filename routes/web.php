<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get('/', [WelcomeController::class, 'index']);
Route::get('home', [HomeController::class, 'index']);

Route::group(['middleware' => ['web','auth','notifications']], function () {
    Route::get('dashboard', 'HomeController@showHome')->name('dashboard')->middleware('notifications');

    Route::post('tasks/{task}/status', 'TaskController@updateStatus');
    Route::get('tasks/{task}/archive', 'TaskController@archiveTask')->name('tasks.archive');
    Route::get('tasks/{task}/unarchive', 'TaskController@unarchiveTask')->name('tasks.unarchive');

    Route::resource('tasks', 'TaskController');
    Route::get('sections/create/{project}', 'SectionController@create');
    Route::post('sections/orders', 'SectionController@updateOrders');
    Route::resource('sections', 'SectionController');


    Route::post('projects/tasks/reposition', 'ProjectController@repositionTasks');

    Route::get('projects/{project}/calendar', 'ProjectController@showCalendar');
    Route::get('calendar/{project}', 'CalendarController@index');
    Route::post('calendar/{project}/create', 'CalendarController@store');
    Route::get('projects/{project}/files', 'ProjectController@showFiles');
    Route::resource('projects', 'ProjectController');
    Route::resource('clients', 'ClientController');
});

Route::group(['middleware' => ['web','auth','notifications']], function () {
    Route::resource('hours', 'HourController');
});

Route::group(['middleware' => ['web','auth','notifications']], function () {
    Route::resource('notifications', 'NotificationController');
});

Route::group(['middleware' => ['web','auth']], function () {
    Route::get('project/{projectid}/user/{userid}', 'ProjectController@removeMember');
    Route::get('hour/delete/{hour}', 'HourController@deleteHour');
});

Route::post('availability', 'AvailabilityController@store');
Route::put('availability/{availability}', 'AvailabilityController@update');
Route::delete('availability/{availability}', 'AvailabilityController@destroy');
Route::get('/user/{id}/availability', 'AvailabilityController@getUserAvailability');

Route::group(['middleware' => ['web','auth','notifications']], function () {
    Route::resource('/payment', 'PaymentController');
    Route::resource('/account/paymentmethods', 'PaymentmethodController');
    Route::post('/account/payment', 'PaymentmethodController@updatePaymentSettings');
    Route::post('/sendtestpayment', 'PaymentController@sendTestPayment');
    Route::post('/sendpayment', 'PaymentController@sendPayment');
});

Route::get('/reports/{start?}/{end?}', 'ReportController@index')->name('reports.index')->middleware('notifications');
//Route::post('/reports/filter', 'ReportController@filterReport')->name('reports.filter')->middleware('notifications');
Route::post('/search', 'SearchController@search')->name('search')->middleware('notifications');
Route::get('/download/{attachment}', 'AttachmentController@download')->name('download');
Route::post('/mark-as-read', 'HomeController@markNotification')->name('markNotification');

Route::get('my-account', 'HomeController@showAccount')->middleware('notifications');
Route::resource('users/roles', '\Seongbae\Canvas\Http\Controllers\Admin\RolesController', ['as'=>'admin'])->middleware('notifications');
Route::resource('users', '\Seongbae\Canvas\Http\Controllers\Admin\UsersController', ['as'=>'admin'])->middleware('notifications');

Route::get('settings', '\Seongbae\Canvas\Http\Controllers\Admin\AdminController@showSettings')->name('admin.settings')->middleware('notifications');
Route::post('settings', '\Seongbae\Canvas\Http\Controllers\Admin\AdminController@saveSettings')->middleware('notifications');

Route::group(['namespace' => '\Seongbae\Canvas\Http\Controllers', 'middleware' => ['web','notifications']], function () {


    Route::get('account', 'UserController@getUser');
    Route::put('account/{id}/profile', 'UserController@updateProfile');
    Route::post('account/{id}/password', 'UserController@updatePassword');

    Route::get('dynamicModal/{id}',[
        'as'=>'dynamicModal',
        'uses'=> 'Admin\MediaController@loadModal'
    ]);


});

Route::resource('teams', 'TeamController')->middleware('notifications');
Route::get('/team/{id}/availability', 'AvailabilityController@getTeamAvailability');
Route::post('/team/{id}/add', 'TeamController@addMember');
Route::delete('/team/{team}/remove/{user}', 'TeamController@removeMember')->name('team.remove');
Route::delete('/invitations/{invitation}', 'InvitationController@destroy')->name('invitation.remove');

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
})->middleware('notifications');
