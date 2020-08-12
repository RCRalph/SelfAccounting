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

Route::get('/settings', 'SettingsController@index')->name('settings');

Route::get('/income', 'IncomeController@index')->name('income');
Route::get('/income/{id}', 'IncomeController@show')->name('income.show');
Route::get('/income/create/one', 'IncomeController@createOne')->name('income.create.one');

Route::get('/outcome', 'OutcomeController@index')->name('outcome');
Route::get('/outcome/{id}', 'OutcomeController@show')->name('outcome.show');
Route::get('/outcome/create/one', 'OutcomeController@createOne')->name('outcome.create.one');

