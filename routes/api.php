<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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

Route::post('/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return response()->json([
        'id'=>$user->id,
        'name'=>$user->name,
        'email'=>$user->email,
        'image_url'=>url('storage/'.$user->photo),
        'projects'=>\App\Http\Resources\ProjectResource::collection($user->projects),
        'token'=> $user->createToken($request->device_name)->plainTextToken,
        'message'=>'success',
    ], 201); // Status code here
});

Route::middleware(['auth:sanctum','notifications'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

Route::group(['middleware' => ['auth:sanctum']], function () {
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
});
