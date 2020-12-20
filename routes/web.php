<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomePageController@index')->name('welcome');

Auth::routes();

Route::prefix('/admin')->group(function() {
    Route::prefix('/users')->group(function() {
        Route::get('/', 'Admin\UsersController@userList')->name('admin.user.list');
        Route::get('/details', 'Admin\UsersController@userDetails')->name('admin.user.details');
        Route::patch('/update', 'Admin\UsersController@updateUser')->name('admin.user.update');
        Route::prefix('/delete')->group(function(){
            Route::get('/', 'Admin\UsersController@confirmDeletion')->name('admin.delete');
            Route::get('/confirmed', 'Admin\UsersController@delete')->name('admin.delete.confirmed');
        });
    });
});

Route::prefix('/summary')->group(function() {
    Route::get('/', 'SummaryController@index')->name('summary');
});

Route::prefix('/income')->group(function() {
    Route::get('/', 'IncomeController@index')->name('income');
    Route::get('/create/one', 'IncomeController@createOne')->name('income.create.one');
    Route::get('/{income}', 'IncomeController@show')->name('income.show');
});

Route::prefix('/outcome')->group(function() {
    Route::get('/', 'OutcomeController@index')->name('outcome');
    Route::get('/create/one', 'OutcomeController@createOne')->name('outcome.create.one');
    Route::get('/{outcome}', 'OutcomeController@show')->name('outcome.show');
});

Route::prefix('/settings')->group(function() {
    Route::get('/', 'SettingsController@index')->name('settings');
});

Route::prefix('/profile')->group(function() {
    Route::get('/', 'ProfileController@index')->name('profile');
    Route::patch('/update', 'ProfileController@updateData')->name('profile.update');
    Route::patch('/password', 'ProfileController@updatePassword')->name('profile.password');

    Route::prefix('/delete')->group(function(){
        Route::get('/', 'ProfileController@confirmDeletion')->name('profile.delete');
        Route::get('/confirmed', 'ProfileController@delete')->name('profile.delete.confirmed');
    });
});

Route::prefix('/bundles')->group(function() {
    Route::get('/', 'BundleController@index')->name('bundles');
    Route::get('/{bundle}', 'BundleController@show')->name('bundles.show');
});

Route::prefix('/webapi')->group(function() {
    Route::prefix('/admin')->group(function() {
        Route::prefix('/users')->group(function() {
            Route::get('/', 'Admin\WebApi\UsersController@users')->name('webapi.admin.user');

            Route::prefix('/details')->group(function() {
                Route::get('/', 'Admin\WebApi\UsersController@details')->name('webapi.admin.user.details');
                Route::patch('/update', 'Admin\WebApi\UsersController@update')->name('webapi.admin.user.details.update');
            });
        });
    });

    Route::prefix('/summary')->group(function() {
        Route::get('/', 'WebApi\SummaryController@getData')->name('summary.getData');
    });

    Route::prefix('/settings')->group(function() {
        Route::get('/', 'WebApi\SettingsController@getSettings')->name('settings.get');
        Route::post('/categories', 'WebApi\SettingsController@saveCategories')->name('settings.categories');
        Route::post('/means', 'WebApi\SettingsController@saveMeans')->name('settings.means');
        Route::post('/darkmode', 'WebApi\SettingsController@darkmode')->name('settings.darkmode');
    });

    Route::prefix('/income')->group(function() {
        Route::get('/', 'WebApi\IncomeController@getIncome')->name('income.getIncome');
        Route::get('/start', 'WebApi\IncomeController@start')->name('income.start');
        Route::patch('/edit', 'WebApi\IncomeController@updateIncome')->name('income.update');
        Route::get('/create/getData', 'WebApi\IncomeController@getCreateData')->name('income.getCreateData');
        Route::post('/store/one', 'WebApi\IncomeController@storeOne')->name('income.store.one');
        Route::delete('/delete/{income}', 'WebApi\IncomeController@deleteIncome')->name('income.delete');
        Route::get('/{income}', 'WebApi\IncomeController@getEditData')->name('income.getEditData');
    });

    Route::prefix('/outcome')->group(function() {
        Route::get('/', 'WebApi\OutcomeController@getOutcome')->name('outcome.getOutcome');
        Route::get('/start', 'WebApi\OutcomeController@start')->name('outcome.start');
        Route::patch('/edit', 'WebApi\OutcomeController@updateOutcome')->name('outcome.update');
        Route::get('/create/getData', 'WebApi\OutcomeController@getCreateData')->name('outcome.getCreateData');
        Route::post('/store/one', 'WebApi\OutcomeController@storeOne')->name('outcome.store.one');
        Route::delete('/delete/{outcome}', 'WebApi\OutcomeController@deleteOutcome')->name('outcome.delete');
        Route::get('/{outcome}', 'WebApi\OutcomeController@getEditData')->name('outcome.getEditData');
    });

    Route::prefix('/profile')->group(function() {
        Route::get('/', 'WebApi\ProfileController@index')->name('webapi.profile.index');
    });

    Route::prefix('/bundles')->group(function() {
        Route::post('/toggle', 'WebApi\BundleController@toggle');

        Route::prefix('/premium')->group(function() {
            Route::post('/toggle', 'WebApi\BundleController@togglePremium');
        });
    });
});
