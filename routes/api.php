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

Route::get('display/apiGetDataDisplay/{id}', 'Api\Frontend\ApiDisplayController@apiGetDataDisplay');
Route::post('status-online/checkUserOnline', 'Api\Frontend\ApiTokenController@checkUserOnline');
Route::get('getDepartmentByBranch', 'Api\Frontend\ApiTokenController@getDepartmentByBranch');

/*
|--------------------------------------------------------------------------
| administrator|admin|staff
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['role:administrator|admin|staff']], function() {
    Route::get('calls/apiTokenNumber', 'Api\Backend\ApiCallController@apiTokenNumber');
    Route::get('calls/apiGetHaveCalled', 'Api\Backend\ApiCallController@apiGetHaveCalled');
});

Route::get('/helper/{code}', function ($code) {return App\Helpers\Helper::checkingCode($code);});
Route::get('/helper', function () {return App\Helpers\Helper::getInfo();});
Route::get('/write', function () {return App\Helpers\Helper::write();});
Route::get('/domain', function () {return App\Helpers\Helper::domain();});
