<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Extension;

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

Route::get('/', function () {
    // Redirect if authenticated
    if (Auth::check()) {
        return redirect()->route('summary');
    }

    return view('welcome');
})->name('welcome');

Route::get("/app", "AppController@index")->name("app");

Route::prefix("/web-api")->group(function () {
    Route::get("/app", "WebAPI\AppController@index")->name("web-api.app");

    Route::prefix("/dashboard")->group(function () {
        Route::prefix("/{currency}")->group(function () {
            Route::get("/", "WebAPI\DashboardController@index")->name("web-api.dashboard");
            Route::get("/recent-transactions", "WebAPI\DashboardController@getRecentTransactions")->name("web-api.dashboard.recent-transactions");
        });
    });

    // Income and outcome routes
    foreach (["income", "outcome"] as $type) {
        Route::group(["prefix" => "/$type", "middleware" => "IO:$type"], function () use ($type) {
            Route::get("/{id}", "WebAPI\IOController@show")->name("web-api.$type.show");
            Route::patch("/{id}", "WebAPI\IOController@update")->name("web-api.$type.update");
            Route::delete("/{id}", "WebAPI\IOController@destroy")->name("web-api.$type.destroy");

            Route::prefix("/currency/{currency}")->group(function () use ($type) {
                Route::get("/", "WebAPI\IOController@index")->name("web-api.$type.currency");
                Route::post("/", "WebAPI\IOController@store")->name("web-api.$type.currency.store");
                Route::get("/data", "WebAPI\IOController@data")->name("web-api.$type.currency.data");
                Route::get("/list", "WebAPI\IOController@list")->name("web-api.$type.currency.list");
            });
        });
    }

    Route::prefix("/exchange")->group(function () {
        Route::get("/", "WebAPI\ExchangeController@show")->name("web-api.exchange.show");
        Route::post("/", "WebAPI\ExchangeController@store")->name("web-api.exchange.store");
    });

    Route::prefix("/settings")->group(function () {
        Route::prefix("/{currency}")->group(function () {
            Route::get("/", "WebAPI\SettingsController@index")->name("web-api.settings");
            Route::get("/categories", "WebAPI\SettingsController@getCategories")->name("web-api.settings.categories");
            Route::get("/means", "WebAPI\SettingsController@getMeans")->name("web-api.settings.means");
        });

        Route::prefix("/categories")->group(function () {
            Route::post("/", "WebAPI\SettingsController@createCategory")->name("web-api.settings.categories.create");
            Route::get("/{category}", "WebAPI\SettingsController@showCategory")->name("web-api.settings.categories.show");
            Route::patch("/{category}", "WebAPI\SettingsController@updateCategory")->name("web-api.settings.categories.update");
            Route::delete("/{category}", "WebAPI\SettingsController@deleteCategory")->name("web-api.settings.categories.delete");
        });

        Route::prefix("/means")->group(function () {
            Route::post("/", "WebAPI\SettingsController@createMean")->name("web-api.settings.means.create");
            Route::get("/{mean}", "WebAPI\SettingsController@showMean")->name("web-api.settings.means.show");
            Route::patch("/{mean}", "WebAPI\SettingsController@updateMean")->name("web-api.settings.means.update");
            Route::delete("/{mean}", "WebAPI\SettingsController@deleteMean")->name("web-api.settings.means.delete");
        });
    });

    Route::prefix("/profile")->group(function () {
        Route::get("/", "WebAPI\ProfileController@index")->name("web-api.profile");
        Route::delete("/", "WebAPI\ProfileController@destroy")->name("web-api.profile.delete");
        Route::post("/picture", "WebAPI\ProfileController@updatePicture")->name("web-api.profile.update.picture");
        Route::post("/information", "WebAPI\ProfileController@updateInformation")->name("web-api.profile.update.information");
        Route::post("/password", "WebAPI\ProfileController@updatePassword")->name("web-api.profile.update.password");
        Route::post("/settings", "WebAPI\ProfileController@updateSettings")->name("web-api.profile.update.settings");
    });

    Route::prefix("/tutorials")->group(function () {
        Route::get("/", "WebAPI\TutorialController@index")->name("web-api.tutorials");
        Route::post("/hide", "WebAPI\TutorialController@hide")->name("web-api.tutorials.hide");
        Route::post("/hide-all", "WebAPI\TutorialController@hideAll")->name("web-api.tutorials.hideAll");
    });

    Route::get("/charts/{chart}/currency/{currency}", "WebAPI\ChartsController@index")->name("web-api.charts");

    Route::prefix("/extensions")->group(function () {
        Route::get("/", "WebAPI\Extensions\ExtensionsController@index")->name("web-api.extensions");

        Route::group([
            "prefix" => "/{code}",
            "middleware" => "extension"
        ], function () {
            Route::post("/toggle", "WebAPI\Extensions\ExtensionsController@toggle")->name("web-api.extensions.code.toggle");
            Route::post("/toggle-premium", "WebAPI\Extensions\ExtensionsController@togglePremium")->name("web-api.extensions.code.toggle-premium");
        });

        Route::prefix("/cash/{currency}")->group(function () {
            Route::get("/", "WebAPI\Extensions\CashController@index")->name("web-api.extensions.cash");
            Route::post("/", "WebAPI\Extensions\CashController@update")->name("web-api.extensions.cash.update");
            Route::get("/list", "WebAPI\Extensions\CashController@list")->name("web-api.extensions.cash.list");
        });

        Route::prefix("/reports")->group(function () {
            Route::get("/", "WebAPI\Extensions\ReportsController@index")->name("web-api.extensions.reports");
            Route::get("/owned-reports", "WebAPI\Extensions\ReportsController@ownedReports")->name("web-api.extensions.reports.owned-reports");
            Route::get("/shared-reports", "WebAPI\Extensions\ReportsController@sharedReports")->name("web-api.extensions.reports.shared-reports");
        });
    });
});

Route::prefix('/admin')->group(function () {
    Route::prefix('/users')->group(function () {
        Route::get('/', 'Admin\UsersController@list')->name('admin.users.list');

        Route::prefix('/{user}')->group(function () {
            Route::get('/', 'Admin\UsersController@details')->name('admin.users.details');
            Route::patch('/update', 'Admin\UsersController@update')->name('admin.users.update');

            Route::prefix('/delete')->group(function () {
                Route::get('/', 'Admin\UsersController@confirmDeletion')->name('admin.users.delete');
                Route::get('/confirmed', 'Admin\UsersController@delete')->name('admin.users.delete.confirmed');
            });
        });
    });

    Route::prefix('/bundles')->group(function () {
        Route::get('/', 'Admin\BundlesController@list')->name('admin.bundles.list');

        Route::prefix('/create')->group(function () {
            Route::get('/', 'Admin\BundlesController@create')->name('admin.bundles.create.page');
            Route::post('/', 'Admin\BundlesController@store')->name('admin.bundles.create.store');
        });

        Route::prefix('/{bundle}')->group(function () {
            Route::get('/', 'Admin\BundlesController@edit')->name('admin.bundles.edit.page');
            Route::patch('/', 'Admin\BundlesController@update')->name('admin.bundles.edit.update');

            Route::prefix('/add-image')->group(function () {
                Route::get('/', 'Admin\BundlesController@addImage')->name('admin.bundles.add-image');
                Route::post('/', 'Admin\BundlesController@storeImage')->name('admin.bundles.store-image');
            });

            Route::prefix('/delete')->group(function () {
                Route::get('/', 'Admin\BundlesController@confirmDeletion')->name('admin.bundles.delete');
                Route::get('/confirmed', 'Admin\BundlesController@delete')->name('admin.bundles.delete.confirmed');
            });
        });
    });
});

Route::prefix('/bundles')->group(function () {
    Route::get('/', 'BundlesController@index')->name('bundles');

    Route::prefix('/charts')->group(function () {
        Route::get('/', 'Bundles\ChartsController@index')->name('bundles.charts.index');
        Route::get('/presence', 'Bundles\ChartsController@presence')->name('bundles.charts.presence');

        Route::get('/balance-monitor', 'Bundles\ChartsController@balanceMonitor')->name('bundles.charts.balance-monitor');
        Route::get('/income-by-categories', 'Bundles\ChartsController@incomeByCategories')->name('bundles.charts.income-by-categories');
        Route::get('/outcome-by-categories', 'Bundles\ChartsController@outcomeByCategories')->name('bundles.charts.outcome-by-categories');
        Route::get('/income-by-means-of-payment', 'Bundles\ChartsController@incomeByMeans')->name('bundles.charts.income-by-means');
        Route::get('/outcome-by-means-of-payment', 'Bundles\ChartsController@outcomeByMeans')->name('bundles.charts.outcome-by-means');
    });

    Route::prefix('/backup')->group(function () {
        Route::get('/', 'Bundles\BackupController@index')->name('bundles.backup.index');
    });

    Route::prefix('/cash')->group(function () {
        Route::get('/', 'Bundles\CashController@index')->name('bundles.cash.index');
    });

    Route::prefix('/reports')->group(function () {
        Route::get('/', 'Bundles\ReportsController@index')->name('bundles.reports.index');
        Route::get('/create', 'Bundles\ReportsController@create')->name('bundles.reports.create');

        Route::prefix('/{report}')->group(function () {
            Route::get('/', 'Bundles\ReportsController@show')->name('bundles.reports.show');
            Route::get('/edit', 'Bundles\ReportsController@edit')->name('bundles.reports.edit');
        });
    });

    Route::get('/{bundle}', 'BundlesController@show')->name('bundles.show');
});

Route::prefix('/summary')->group(function () {
    Route::get('/', 'SummaryController@index')->name('summary');
});

Route::prefix('/settings')->group(function () {
    Route::get('/', 'SettingsController@index')->name('settings');
});

Route::prefix('/profile')->group(function () {
    Route::get('/', 'ProfileController@index')->name('profile');
    Route::patch('/update', 'ProfileController@updateData')->name('profile.update');
    Route::patch('/password', 'ProfileController@updatePassword')->name('profile.password');

    Route::prefix('/delete')->group(function () {
        Route::get('/', 'ProfileController@confirmDeletion')->name('profile.delete');
        Route::get('/confirmed', 'ProfileController@delete')->name('profile.delete.confirmed');
    });
});

Route::get('/payment', 'PagesController@payment')->name('payment');
Route::get('/premium', 'PagesController@premium')->name('premium');
Route::get('/getting-started', 'PagesController@gettingStarted')->name('getting-started');

// ----- Web API ----- //

Route::prefix('/webapi')->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::prefix('/users')->group(function () {
            Route::get('/', 'Admin\Web_Api\UsersController@users')->name('webapi.admin.user');

            Route::prefix('/{user}')->group(function () {
                Route::get('/', 'Admin\Web_Api\UsersController@details')->name('webapi.admin.user.details');
                Route::patch('/update', 'Admin\Web_Api\UsersController@update')->name('webapi.admin.user.details.update');
                Route::post('/enable-backup', 'Admin\Web_Api\UsersController@enableBackup')->name('admin.users.enable-backup');
                Route::post('/enable-restoration', 'Admin\Web_Api\UsersController@enableRestoration')->name('admin.users.enable-restoration');
            });
        });

        Route::prefix('/bundles')->group(function () {
            Route::get('/', 'Admin\Web_Api\BundlesController@bundles')->name('webapi.admin.bundles');
            Route::get('/create', 'Admin\Web_Api\BundlesController@getCreateData')->name('webapi.admin.bundles.create');

            Route::prefix('/{bundle}')->group(function () {
                Route::get('/', 'Admin\Web_Api\BundlesController@details')->name('webapi.admin.bundles.details');
                Route::patch('/update-gallery', 'Admin\Web_Api\BundlesController@updateGallery')->name('webapi.admin.bundles.update-gallery');
            });
        });
    });

    Route::prefix('/bundles')->group(function () {
        Route::prefix('/charts')->group(function () {
            Route::prefix('/presence')->group(function () {
                Route::get('/', 'Bundles\Web_Api\ChartsController@getPresence')->name('webapi.bundles.charts.presence.get');
                Route::patch('/categories', 'Bundles\Web_Api\ChartsController@updateCategories')->name('webapi.bundles.charts.presence.categories');
                Route::patch('/means-of-payment', 'Bundles\Web_Api\ChartsController@updateMeans')->name('webapi.bundles.charts.presence.means');
            });

            Route::get('/balance-monitor', 'Bundles\Web_Api\ChartsController@balanceMonitor')->name('webapi.bundles.charts.balance-monitor');
            Route::get('/income-by-categories', 'Bundles\Web_Api\ChartsController@incomeByCategories')->name('webapi.bundles.charts.income-by-categories');
            Route::get('/outcome-by-categories', 'Bundles\Web_Api\ChartsController@outcomeByCategories')->name('webapi.bundles.charts.outcome-by-categories');
            Route::get('/income-by-means-of-payment', 'Bundles\Web_Api\ChartsController@incomeByMeans')->name('webapi.bundles.charts.income-by-means');
            Route::get('/outcome-by-means-of-payment', 'Bundles\Web_Api\ChartsController@outcomeByMeans')->name('webapi.bundles.charts.outcome-by-means');
        });

        Route::prefix('/backup')->group(function () {
            Route::get('/', 'Bundles\Web_Api\BackupController@index')->name('webapi.bundles.backup.index');
            Route::get('/create', 'Bundles\Web_Api\BackupController@createBackup')->name('webapi.bundles.backup.create');
            Route::post('/restore', 'Bundles\Web_Api\BackupController@restoreData')->name('webapi.bundles.backup.restore');
        });

        Route::prefix('/cash')->group(function () {
            Route::get('/', 'Bundles\Web_Api\CashController@index')->name('webapi.bundles.cash.index');
            Route::post('/', 'Bundles\Web_Api\CashController@saveCashAndMeans')->name('webapi.bundles.cash.save-cash-and-means');
        });

        Route::prefix('/reports')->group(function () {
            Route::get('/user-reports', 'Bundles\Web_Api\ReportsController@userReports')->name('webapi.bundles.reports.user-reports');
            Route::get('/shared-reports', 'Bundles\Web_Api\ReportsController@sharedReports')->name('webapi.bundles.reports.shared-reports');
            Route::get('/create', 'Bundles\Web_Api\ReportsController@create')->name('webapi.bundles.reports.create');
            Route::post('/get-user-info', 'Bundles\Web_Api\ReportsController@getUserInfo')->name('webapi.bundles.reports.get-user-info');
            Route::post('/store', 'Bundles\Web_Api\ReportsController@store')->name('webapi.bundles.reports.store');

            Route::prefix('/{report}')->group(function () {
                Route::get('/', 'Bundles\Web_Api\ReportsController@show')->name('webapi.bundles.reports.show');
                Route::get('/edit', 'Bundles\Web_Api\ReportsController@edit')->name('webapi.bundles.reports.edit');
                Route::patch('/update', 'Bundles\Web_Api\ReportsController@update')->name('webapi.bundles.reports.update');
                Route::delete('/delete', 'Bundles\Web_Api\ReportsController@destroy')->name('webapi.bundles.reports.delete');
                Route::post('/duplicate', 'Bundles\Web_Api\ReportsController@duplicate')->name('webapi.bundles.reports.duplicate');
            });
        });

        Route::prefix('/{bundle}')->group(function () {
            Route::post('/toggle', 'Web_Api\BundlesController@toggle')->name('webapi.bundle.toggle');
            Route::post('/toggle-premium', 'Web_Api\BundlesController@togglePremium')->name('webapi.bundle.toggle-premium');
        });
    });

    Route::prefix('/summary')->group(function () {
        Route::get('/', 'Web_Api\SummaryController@getData')->name('webapi.summary.getData');
    });

    Route::prefix('/settings')->group(function () {
        Route::get('/', 'Web_Api\SettingsController@getSettings')->name('webapi.settings.get');
        Route::post('/categories', 'Web_Api\SettingsController@saveCategories')->name('webapi.settings.categories');
        Route::post('/means', 'Web_Api\SettingsController@saveMeans')->name('webapi.settings.means');
        Route::post('/darkmode', 'Web_Api\SettingsController@darkmode')->name('webapi.settings.darkmode');
        Route::post('/tutorials', 'Web_Api\SettingsController@tutorials')->name('webapi.settings.tutorials');
    });

    Route::prefix('/profile')->group(function () {
        Route::get('/', 'Web_Api\ProfileController@index')->name('webapi.profile.index');
    });

    Route::prefix('/payment')->group(function () {
        Route::get('/', 'Web_Api\PagesController@payment')->name('webapi.payment.index');
    });

    // ----- Income / Outcome ----- //

    Route::prefix('/{viewType}')->group(function () {
        Route::get('/start', 'Web_Api\IncomeOutcomeController@start')->name('webapi.income.start');
        Route::post('/store', 'Web_Api\IncomeOutcomeController@store')->name('webapi.income-outcome.store');
        Route::get('/create', 'Web_Api\IncomeOutcomeController@create')->name('webapi.income-outcome.create');
        Route::post('/exchange', 'Web_Api\IncomeOutcomeController@exchange')->name('webapi.income-outcome.exchange');

        Route::get('/all/{currency}', 'Web_Api\IncomeOutcomeController@all')->name('webapi.income-outcome.all');

        Route::prefix('/{incomeOutcome}')->group(function () {
            Route::get('/', 'Web_Api\IncomeOutcomeController@edit')->name('webapi.income-outcome.edit');
            Route::patch('/update', 'Web_Api\IncomeOutcomeController@update')->name('webapi.income-outcome.update');
            Route::delete('/delete', 'Web_Api\IncomeOutcomeController@delete')->name('webapi.income-outcome.delete');
        });
    });
});

// ----- Income / Outcome ----- //

Route::prefix('/{viewType}')->group(function () {
    Route::get('/', 'IncomeOutcomeController@index')->name('income-outcome');
    Route::get('/create-one', 'IncomeOutcomeController@createOne')->name('income-outcome.create-one');
    Route::get('/create-multiple', 'IncomeOutcomeController@createMultiple')->name('income-outcome.create-multiple');
    Route::get('/exchange', 'IncomeOutcomeController@exchange')->name('income-outcome.exchange');
    Route::get('/{incomeOutcome}', 'IncomeOutcomeController@edit')->name('income-outcome.edit');
});
