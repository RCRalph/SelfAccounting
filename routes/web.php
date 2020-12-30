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

Auth::routes();

// ----- Pages ----- //

Route::get('/', function() {
    // Redirect if authenticated
    if (Auth::check()) {
        return redirect()->route("summary");
    }

    return view("welcome");
})->name('welcome');

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
        Route::get('/', 'Admin\BundlesController@list')->name('admin.bundles.list');

        Route::prefix('/create')->group(function() {
            Route::get('/', 'Admin\BundlesController@create')->name('admin.bundles.create.page');
            Route::post('/', 'Admin\BundlesController@store')->name('admin.bundles.create.store');
        });

        Route::prefix('/{bundle}')->group(function() {
            Route::get('/', 'Admin\BundlesController@edit')->name('admin.bundles.edit.page');
            Route::patch('/', 'Admin\BundlesController@update')->name('admin.bundles.edit.update');

            Route::prefix('/add-image')->group(function() {
                Route::get('/', 'Admin\BundlesController@addImage')->name('admin.bundles.add-image');
                Route::post('/', 'Admin\BundlesController@storeImage')->name('admin.bundles.store-image');
            });

            Route::prefix('/delete')->group(function() {
                Route::get('/', 'Admin\BundlesController@confirmDeletion')->name('admin.bundles.delete');
                Route::get('/confirmed', 'Admin\BundlesController@delete')->name('admin.bundles.delete.confirmed');
            });
        });
    });
});

Route::prefix('/summary')->group(function() {
    Route::get('/', 'SummaryController@index')->name('summary');
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

Route::get('/payment', 'PagesController@payment')->name('payment');

Route::get('/premium', 'PagesController@premium')->name('premium');

// ----- Web API ----- //

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
                Route::patch('/update-gallery', 'Admin\WebApi\BundlesController@updateGallery')->name('webapi.admin.bundles.update-gallery');
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

    Route::prefix('/profile')->group(function() {
        Route::get('/', 'WebApi\ProfileController@index')->name('webapi.profile.index');
    });

    Route::prefix('/bundles')->group(function() {
        Route::prefix('/{bundle}')->group(function() {
            Route::post('/toggle', 'WebApi\BundlesController@toggle')->name('bundle.toggle');
            Route::post('/toggle-premium', 'WebApi\BundlesController@togglePremium')->name('bundle.toggle-premium');
        });
    });

    Route::prefix('/payment')->group(function() {
        Route::get('/', 'WebApi\PagesController@payment')->name('payment.index');
    });

    // ----- Income / Outcome ----- //

    Route::prefix('/{viewType}')->group(function() {
        Route::get('/start', 'WebApi\IncomeOutcomeController@start')->name('income.start');
        Route::post('/store', 'WebApi\IncomeOutcomeController@store')->name('income-outcome.store');
        Route::get('/create', 'WebApi\IncomeOutcomeController@create')->name('income-outcome.create');

        Route::get('/all/{currency}', 'WebApi\IncomeOutcomeController@all')->name('income-outcome.all');

        Route::prefix('/{incomeOutcome}')->group(function() {
            Route::get('/', 'WebApi\IncomeOutcomeController@edit')->name('income-outcome.edit');
            Route::patch('/update', 'WebApi\IncomeOutcomeController@update')->name('income-outcome.update');
            Route::delete('/delete', 'WebApi\IncomeOutcomeController@delete')->name('income-outcome.delete');
        });
    });
});

// ----- Income / Outcome ----- //

Route::prefix('/{viewType}')->group(function() {
    Route::get('/', 'IncomeOutcomeController@index')->name('income-outcome');
    Route::get('/create-one', 'IncomeOutcomeController@createOne')->name('income-outcome.create-one');
    Route::get('/create-multiple', 'IncomeOutcomeController@createMultiple')->name('income-outcome.create-multiple');
    Route::get('/{incomeOutcome}', 'IncomeOutcomeController@edit')->name('income-outcome.edit');
});
