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
        Route::get('/', 'Admin\UsersController@list')->name('admin.users.list');

        Route::prefix('/{user}')->group(function() {
            Route::get('/', 'Admin\UsersController@details')->name('admin.users.details');
            Route::patch('/update', 'Admin\UsersController@update')->name('admin.users.update');

            Route::prefix('/delete')->group(function(){
                Route::get('/', 'Admin\UsersController@confirmDeletion')->name('admin.users.delete');
                Route::get('/confirmed', 'Admin\UsersController@delete')->name('admin.users.delete.confirmed');
            });
        });
    });

    Route::prefix('/bundles')->group(function() {
        Route::get('/', 'Admin\BundlesController@list')->name('admin.bundle.list');

        Route::prefix('/create')->group(function() {
            Route::get('/', 'Admin\BundlesController@create')->name('admin.bundle.create.page');
            Route::post('/', 'Admin\BundlesController@store')->name('admin.bundle.create.store');
        });

        Route::prefix('/{bundle}')->group(function() {
            Route::get('/', 'Admin\BundlesController@edit')->name('admin.bundle.edit.page');
            Route::patch('/', 'Admin\BundlesController@update')->name('admin.bundle.edit.update');
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
    Route::get('/', 'BundlesController@index')->name('bundles');
    Route::get('/{bundle}', 'BundlesController@show')->name('bundles.show');
});

Route::prefix('/webapi')->group(function() {
    Route::prefix('/admin')->group(function() {
        Route::prefix('/users')->group(function() {
            Route::get('/', 'Admin\WebApi\UsersController@users')->name('webapi.admin.user');

            Route::prefix('/{user}')->group(function() {
                Route::get('/', 'Admin\WebApi\UsersController@details')->name('webapi.admin.user.details');
                Route::patch('/update', 'Admin\WebApi\UsersController@update')->name('webapi.admin.user.details.update');
            });
        });

        Route::prefix('/bundles')->group(function() {
            Route::get('/', 'Admin\WebApi\BundlesController@bundles')->name('webapi.admin.bundles');
            Route::get('/create', 'Admin\WebApi\BundlesController@getCreateData')->name('webapi.admin.bundles.create');

            Route::prefix('/{bundle}')->group(function() {
                Route::get('/', 'Admin\WebApi\BundlesController@details')->name('webapi.admin.bundles.details');
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
        Route::prefix('/{bundle}')->group(function() {
            Route::post('/toggle', 'WebApi\BundlesController@toggle');
            Route::post('/toggle-premium', 'WebApi\BundlesController@togglePremium');
        });
    });
});
