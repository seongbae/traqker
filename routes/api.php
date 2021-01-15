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
        'name'=>$user->name,
        'email'=>$user->email,
        'image_url'=>url($user->photo),
        'token'=> $user->createToken($request->device_name)->plainTextToken,
        'message'=>'success',
    ], 201); // Status code here
});

Route::middleware(['auth:sanctum','notifications'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('tasks/{task}', 'TaskController@show')->name('tasks.show');
    Route::put('tasks/{task}', 'TaskController@update')->name('tasks.update');
    Route::post('tasks', 'TaskController@store')->name('tasks.store');
    Route::get('tasks', 'TaskController@index')->name('tasks.index');
});
