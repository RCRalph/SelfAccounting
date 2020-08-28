<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/summary', 'SummaryController@index')->name('summary');

Route::get('/income', 'IncomeController@index')->name('income');
Route::get('/income/{id}', 'IncomeController@show')->name('income.show');
Route::get('/income/create/one', 'IncomeController@createOne')->name('income.create.one');

Route::get('/outcome', 'OutcomeController@index')->name('outcome');
Route::get('/outcome/{id}', 'OutcomeController@show')->name('outcome.show');
Route::get('/outcome/create/one', 'OutcomeController@createOne')->name('outcome.create.one');

Route::get('/settings', 'SettingsController@index')->name('settings');

Route::post('/user/darkmode', 'UsersController@darkmode')->name('user.darkmode');

// WebAPI

Route::get('/webapi/settings', 'WebApi\SettingsController@getSettings')->name('settings.get');
Route::post('/webapi/settings/categories', 'WebApi\SettingsController@saveCategories')->name('settings.categories');
Route::post('/webapi/settings/means', 'WebApi\SettingsController@saveMeans')->name('settings.means');
