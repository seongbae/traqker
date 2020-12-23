<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get('/', [WelcomeController::class, 'index']);
Route::get('home', [HomeController::class, 'index']);

Route::group(['middleware' => ['web','auth']], function () {
    Route::get('dashboard', 'HomeController@showHome')->name('dashboard');

    Route::put('tasks/{task}/status', 'TaskController@updateStatus');
    Route::get('tasks/{task}/archive', 'TaskController@archiveTask')->name('tasks.archive');
    Route::get('tasks/{task}/unarchive', 'TaskController@unarchiveTask')->name('tasks.unarchive');
    Route::put('tasks/{task}/updatelist', 'TaskController@updateList');

    Route::get('tasks/archived', 'TaskController@indexArchived');
    Route::get('tasks/deleted', 'TaskController@indexDeleted');
    Route::get('tasks/deleted/{project}', 'TaskController@indexDeleted');
    Route::get('tasks/completed', 'TaskController@indexCompleted');
    Route::get('tasks/completed/{project}', 'TaskController@indexCompleted');

    Route::resource('tasks', 'TaskController');

    Route::get('sections/create/{id}', 'SectionController@create');
    Route::post('sections/orders', 'SectionController@updateOrders');
    Route::resource('sections', 'SectionController');

    Route::get('projects/archived', 'ProjectController@indexArchived');
    Route::post('projects/tasks/reposition', 'ProjectController@repositionTasks');
    Route::get('projects/{project}/board', 'ProjectController@showBoard');
    Route::get('projects/{project}/calendar', 'ProjectController@showCalendar');
    Route::get('projects/{project}/files', 'ProjectController@showFiles');
    Route::get('project/{projectid}/user/{userid}', 'ProjectController@removeMember');
    Route::get('projects/{project}/completed', 'ProjectController@index');
    Route::get('projects/{project}/timeline', 'ProjectController@showGantt');
    Route::resource('projects', 'ProjectController');

    Route::get('calendar', 'CalendarController@index')->name('calendar.index');
    Route::get('calendar/user', 'CalendarController@indexUser');
    Route::get('calendar/project/{id}', 'CalendarController@indexProject');
    Route::post('calendar/{project}/create', 'CalendarController@store');


    Route::resource('teams', 'TeamController');
    Route::get('teams/{team}/availability', 'AvailabilityController@getTeamAvailability')->name('teams.availability');
    Route::get('teams/{team}/settings', 'TeamController@getSettings')->name('teams.settings');
    Route::post('/team/{team}/add', 'TeamController@addMember');
    Route::delete('/team/{team}/remove/{user}', 'TeamController@removeMember')->name('team.remove');
    Route::delete('/invitations/{invitation}', 'InvitationController@destroy')->name('invitation.remove');

    Route::get('/invites/{invite}', 'InvitationController@show')->name('invites.show');
    Route::put('/invites/{invite}', 'InvitationController@update')->name('invites.update');


    Route::resource('notifications', 'NotificationController');
    Route::resource('attachments', 'AttachmentController');
    Route::resource('quicklinks', 'QuicklinkController');

    Route::get('hour/delete/{hour}', 'HourController@deleteHour');
    Route::resource('hours', 'HourController');

    Route::post('availability', 'AvailabilityController@store');
    Route::put('availability/{availability}', 'AvailabilityController@update');
    Route::delete('availability/{availability}', 'AvailabilityController@destroy');
    Route::get('/user/{id}/availability', 'AvailabilityController@getUserAvailability');

    Route::get('/download/{attachment}', 'AttachmentController@download')->name('download');
    Route::get('/reports/{start?}/{end?}', 'ReportController@index')->name('reports.index');
    Route::get('/search', 'SearchController@search')->name('search');
    Route::post('/mark-as-read', 'HomeController@markNotification')->name('markNotification');

    Route::get('my-account', 'HomeController@showAccount');
    Route::put('user/{user}/settings', 'SettingsController@saveUserSettings');

    // Discuss
    Route::get('teams/{slug}/discuss', 'ThreadsController@index')->name('discuss.index');
    Route::post('discuss', 'ThreadsController@store')->name('discuss.store');
    Route::get('discuss/{channel}/{thread}', 'ThreadsController@show')->name('discuss.show');


});

//Route::group(['middleware' => ['web','auth','notifications']], function () {
//    Route::resource('/payment', 'PaymentController');
//    Route::resource('/account/paymentmethods', 'PaymentmethodController');
//    Route::post('/account/payment', 'PaymentmethodController@updatePaymentSettings');
//    Route::post('/sendtestpayment', 'PaymentController@sendTestPayment');
//    Route::post('/sendpayment', 'PaymentController@sendPayment');
//});

Route::group(['namespace'=>'\Seongbae\Canvas\Http\Controllers\Admin', 'prefix' => 'admin', 'middleware' => ['web','auth','notifications']], function () {

    // Pages controller
    Route::resource('pages', 'PagesController', ['as'=>'admin']);

    // User management
    Route::resource('users/roles', 'RolesController', ['as'=>'admin']);
    Route::resource('users', 'UsersController', ['as'=>'admin']);

    // Media management
    Route::resource('media', 'MediaController', ['as'=>'admin']);

    // Settings
    Route::get('settings', 'AdminController@showSettings')->name('admin.settings');
    Route::post('settings', 'AdminController@saveSettings');

    // Module management
    Route::get('modules/install/{module}', 'AdminController@installModule');
    Route::get('modules/uninstall/{module}', 'AdminController@uninstallModule');

    // Backend search
    Route::post('search', 'AdminController@search')->name('admin.search');

    // Log management
    Route::get('logs/system', 'LogViewerController@index')->name('admin.logs.system');
    Route::get('logs/activity', 'AdminController@showActivityLogs')->name('admin.logs.activity');

});

Route::group(['namespace' => '\Seongbae\Discuss\Http\Controllers', 'middleware' => ['web']], function () {

    // Discussion
    Route::patch('discuss/{channel}/{thread}', 'ThreadsController@update')->name('discuss.update');
    Route::delete('discuss/{channel}/{thread}', 'ThreadsController@destroy')->name('discuss.destroy');

    Route::post('discuss/{channel}/{thread}/replies', 'RepliesController@store')->name('reply.store');
    Route::patch('replies/{reply}', 'RepliesController@update')->name('reply.update');
    Route::delete('replies/{reply}', 'RepliesController@destroy')->name('reply.destroy');

    Route::post('discuss/subscribe/{type}/{id}', 'SubscriptionController@update')->name('subscription.update');

});
