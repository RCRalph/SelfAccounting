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

Route::get('/', 'WelcomePageController@index')->name('welcome');

Auth::routes();

Route::get('/summary', 'SummaryController@index')->name('summary');

Route::get('/income', 'IncomeController@index')->name('income');
Route::get('/income/create/one', 'IncomeController@createOne')->name('income.create.one');
Route::get('/income/{income}', 'IncomeController@show')->name('income.show');

Route::get('/outcome', 'OutcomeController@index')->name('outcome');
Route::get('/outcome/create/one', 'OutcomeController@createOne')->name('outcome.create.one');
Route::get('/outcome/{outcome}', 'OutcomeController@show')->name('outcome.show');

Route::get('/settings', 'SettingsController@index')->name('settings');

// WebAPI

Route::post('/webapi/darkmode', 'UsersController@darkmode')->name('user.darkmode');

Route::get('/webapi/summary', 'WebApi\SummaryController@getData')->name('summary.getData');

Route::get('/webapi/settings', 'WebApi\SettingsController@getSettings')->name('settings.get');
Route::post('/webapi/settings/categories', 'WebApi\SettingsController@saveCategories')->name('settings.categories');
Route::post('/webapi/settings/means', 'WebApi\SettingsController@saveMeans')->name('settings.means');

Route::get('/webapi/income', 'WebApi\IncomeController@getIncome')->name('income.getIncome');
Route::get('/webapi/income/start', 'WebApi\IncomeController@start')->name('income.start');
Route::patch('/webapi/income/edit', 'WebApi\IncomeController@updateIncome')->name('income.update');
Route::get('/webapi/income/create/getData', 'WebApi\IncomeController@getCreateData')->name('income.getCreateData');
Route::post('/webapi/income/store/one', 'WebApi\IncomeController@storeOne')->name('income.store.one');
Route::delete('/webapi/income/delete/{income}', 'WebApi\IncomeController@deleteIncome')->name('income.delete');
Route::get('/webapi/income/{income}', 'WebApi\IncomeController@getEditData')->name('income.getEditData');

Route::get('/webapi/outcome', 'WebApi\OutcomeController@getOutcome')->name('outcome.getOutcome');
Route::get('/webapi/outcome/start', 'WebApi\OutcomeController@start')->name('outcome.start');
Route::patch('/webapi/outcome/edit', 'WebApi\OutcomeController@updateOutcome')->name('outcome.update');
Route::get('/webapi/outcome/create/getData', 'WebApi\OutcomeController@getCreateData')->name('outcome.getCreateData');
Route::post('/webapi/outcome/store/one', 'WebApi\OutcomeController@storeOne')->name('outcome.store.one');
Route::delete('/webapi/outcome/delete/{outcome}', 'WebApi\OutcomeController@deleteOutcome')->name('outcome.delete');
Route::get('/webapi/outcome/{outcome}', 'WebApi\OutcomeController@getEditData')->name('outcome.getEditData');
