<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Main web
Route::GET('/', 'Frontend\Token\TokenController@index')->name('token');
Route::GET('/token', 'Frontend\Token\TokenController@index')->name('token');
// Show token customer
Route::GET('/token/number/{token}', 'Frontend\Token\TokenController@tokenNumber')->name('token.number');
// Api Get token
Route::POST('/token/getToken/', 'Frontend\Token\TokenController@getToken')->name('token.getToken');
// Api get current token
Route::GET('/token/getCurrentToken/', 'Frontend\Token\TokenController@getCurrentToken')->name('token.getCurrentToken');
// Sent the token to customer mail
Route::POST('/token/sentMail/', 'Frontend\Token\TokenController@sentMail')->name('token.sentMail');
// Show all token base on department
Route::GET('/display/{id}', 'Frontend\Display\DisplayController@index')->name('display');
// Sent the token to customer via SMS
Route::POST('/token/sendMessage/', 'Frontend\Token\TokenController@sendMessage')->name('token.sendMessage');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
|--------------------------------------------------------------------------
| administrator|admin|staff|guest
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['role:administrator|admin|staff|guest']], function() {
    Route::GET('/checkProductVerify', 'MainController@checkProductVerify')->name('checkProductVerify');

    Route::GET('/profile/details', 'Backend\Profile\ProfileController@details')->name('profile.details');
    Route::POST('/profile/update', 'Backend\Profile\ProfileController@update')->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| administrator|admin
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['role:administrator|admin']], function() {
    Route::GET('/users', 'Backend\Users\UsersController@index')->name('users');
    Route::GET('/users/add', 'Backend\Users\UsersController@add')->name('users.add');
    Route::POST('/users/create', 'Backend\Users\UsersController@create')->name('users.create');
    Route::GET('/users/edit/{id}', 'Backend\Users\UsersController@edit')->name('users.edit');
    Route::POST('/users/update', 'Backend\Users\UsersController@update')->name('users.update');
    Route::GET('/users/delete/{id}', 'Backend\Users\UsersController@delete')->name('users.delete');

    Route::GET('/departments', 'Backend\Department\DepartmentController@index')->name('departments');
    Route::GET('/departments/add', 'Backend\Department\DepartmentController@add')->name('departments.add');
    Route::POST('/departments/create', 'Backend\Department\DepartmentController@create')->name('departments.create');
    Route::GET('/departments/edit/{id}', 'Backend\Department\DepartmentController@edit')->name('departments.edit');
    Route::POST('/departments/update', 'Backend\Department\DepartmentController@update')->name('departments.update');
    Route::GET('/departments/delete/{id}', 'Backend\Department\DepartmentController@delete')->name('departments.delete');

    Route::GET('/counters', 'Backend\Counter\CounterController@index')->name('counters');
    Route::GET('/counters/add', 'Backend\Counter\CounterController@add')->name('counters.add');
    Route::POST('/counters/create', 'Backend\Counter\CounterController@create')->name('counters.create');
    Route::GET('/counters/edit/{id}', 'Backend\Counter\CounterController@edit')->name('counters.edit');
    Route::POST('/counters/update', 'Backend\Counter\CounterController@update')->name('counters.update');
    Route::GET('/counters/delete/{id}', 'Backend\Counter\CounterController@delete')->name('counters.delete');

    // Settings
    Route::GET('/settings/', 'Backend\Setting\SettingController@index')->name('settings');
    Route::POST('/settings/update', 'Backend\Setting\SettingController@update')->name('settings.update');

    Route::GET('/analytic/', 'Backend\Analytic\AnalyticController@index')->name('analytic');
    Route::GET('/analytic/chartMonthSummary', 'Backend\Analytic\AnalyticController@chartMonthSummary')->name('analytic.chartMonthSummary');
    Route::GET('/analytic/chartMonthDetail', 'Backend\Analytic\AnalyticController@chartMonthDetail')->name('analytic.chartMonthDetail');
    Route::GET('/analytic/chartYearSummary', 'Backend\Analytic\AnalyticController@chartYearSummary')->name('analytic.chartYearSummary');
    Route::GET('/analytic/chartYearDetail', 'Backend\Analytic\AnalyticController@chartYearDetail')->name('analytic.chartYearDetail');
});

/*
|--------------------------------------------------------------------------
| administrator|admin|staff
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['role:administrator|admin|staff']], function() {
    Route::GET('/calls', 'Backend\Call\CallController@index')->name('calls');
    Route::POST('/calls/update', 'Backend\Call\CallController@update')->name('calls.update');
    Route::POST('/mail/sendToNextClient', 'Backend\Call\CallController@sendToNextClient')->name('mail.sendToNextClient');
    Route::POST('/status/updateStatusOnline', 'Backend\Call\CallController@updateStatusOnline')->name('status.updateStatusOnline');
    Route::POST('/status/checkStatusOnline', 'Backend\Call\CallController@checkStatusOnline')->name('status.checkStatusOnline');

    Route::POST('/notifications/getStatusOnline', 'MainController@getStatusOnline')->name('notifications.getStatusOnline');
    Route::POST('/notifications/markNotification', 'MainController@markNotification')->name('notifications.markNotification');

    Route::GET('/set-display/', 'Backend\Display\DisplayController@index')->name('display.index');

    Route::GET('/dashboard/checkUserOfflineOnline', 'HomeController@checkUserOfflineOnline')->name('dashboard.checkUserOfflineOnline');

});

/*
|--------------------------------------------------------------------------
| administrator
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['role:administrator']], function() {
    Route::GET('/branches', 'Backend\Branch\BranchController@index')->name('branches');
    Route::GET('/branches/add', 'Backend\Branch\BranchController@add')->name('branches.add');
    Route::POST('/branches/create', 'Backend\Branch\BranchController@create')->name('branches.create');
    Route::GET('/branches/edit/{id}', 'Backend\Branch\BranchController@edit')->name('branches.edit');
    Route::POST('/branches/update', 'Backend\Branch\BranchController@update')->name('branches.update');
    Route::GET('/branches/delete/{id}', 'Backend\Branch\BranchController@delete')->name('branches.delete');
});

Route::post('reinputkey/index/{code}', 'Utils\Activity\ReinputKeyController@index');

