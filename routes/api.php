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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', 'UserController@register');
Route::post('auth/login', 'UserController@login');

// user route
//Route::group(['middleware' => 'jwt.auth', 'jwt.refresh'], function () {
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('user', 'UserController@getAuthUser');
    
    Route::get('user-view', 'UserController@getAllUser');

    // employee
    Route::resource('employee', 'EmployeeController');

    // department
    Route::resource('department', 'DepartmentController');

    // attendace
    Route::resource('attendance', 'AttendanceController');
    Route::get('inout', 'AttendanceController@inoutflag');

    Route::resource('chcklatlong', 'CheckLatLongController');
    Route::get('diff', 'CheckLatLongController@get_distance_between_points');

    // post
    Route::resource('post', 'PostController');

    // tag
    Route::resource('tag', 'TagController');

    // leave
    Route::resource('leave', 'LeaveController');
});