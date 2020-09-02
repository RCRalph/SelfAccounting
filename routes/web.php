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
Route::get('/income/create/one', 'IncomeController@createOne')->name('income.create.one');
Route::post('/income/store/one', 'IncomeController@storeOne')->name('income.store.one');
Route::get('/income/{income}', 'IncomeController@show')->name('income.show');

Route::get('/outcome', 'OutcomeController@index')->name('outcome');
Route::get('/outcome/create/one', 'OutcomeController@createOne')->name('outcome.create.one');
Route::get('/outcome/{income}', 'OutcomeController@show')->name('outcome.show');

Route::get('/settings', 'SettingsController@index')->name('settings');

// WebAPI

Route::post('/webapi/darkmode', 'UsersController@darkmode')->name('user.darkmode');

Route::get('/webapi/settings', 'WebApi\SettingsController@getSettings')->name('settings.get');
Route::post('/webapi/settings/categories', 'WebApi\SettingsController@saveCategories')->name('settings.categories');
Route::post('/webapi/settings/means', 'WebApi\SettingsController@saveMeans')->name('settings.means');

Route::get('/webapi/income', 'WebApi\IncomeController@getIncome')->name('income.getIncome');
Route::get('/webapi/income/start', 'WebApi\IncomeController@start')->name('income.start');
Route::get('/webapi/income/{income}', 'WebApi\IncomeController@getEditData')->name('income.getEditData');

Route::get('/webapi/outcome', 'WebApi\OutcomeController@getOutcome')->name('outcome.getOutcome');
Route::get('/webapi/outcome/start', 'WebApi\OutcomeController@start')->name('outcome.start');
