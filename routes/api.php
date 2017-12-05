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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/users/register', 'UsersController@register')->name('api.users.register');
Route::apiResource('users', 'UsersController', [
  'names' => [
    'store' => 'api.users.store',
  ]
]);

Route::post('/login', 'SessionsController@store')->name('api.auth.login');
Route::post('/logout', 'SessionsController@destroy')->name('api.auth.logout');
