<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Extension;

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

Route::get("/", "AppController@index")->name("index");
Route::get("/app", "AppController@app")->name("app")->middleware("auth");

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
            Route::prefix("/{id}")->group(function () use ($type) {
                Route::get("/", "WebAPI\IOController@show")->name("web-api.$type.show");
                Route::patch("/", "WebAPI\IOController@update")->name("web-api.$type.update");
                Route::delete("/", "WebAPI\IOController@destroy")->name("web-api.$type.destroy");
            });

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

            Route::prefix("/{category}")->group(function () {
                Route::get("/", "WebAPI\SettingsController@showCategory")->name("web-api.settings.categories.show");
                Route::patch("/", "WebAPI\SettingsController@updateCategory")->name("web-api.settings.categories.update");
                Route::delete("/", "WebAPI\SettingsController@deleteCategory")->name("web-api.settings.categories.delete");
            });
        });

        Route::prefix("/means")->group(function () {
            Route::post("/", "WebAPI\SettingsController@createMean")->name("web-api.settings.means.create");

            Route::prefix("/{mean}")->group(function () {
                Route::get("/", "WebAPI\SettingsController@showMean")->name("web-api.settings.means.show");
                Route::patch("/", "WebAPI\SettingsController@updateMean")->name("web-api.settings.means.update");
                Route::delete("/", "WebAPI\SettingsController@deleteMean")->name("web-api.settings.means.delete");
            });
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

        Route::group([ "prefix" => "/{code}", "middleware" => "extension" ], function () {
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

            Route::get("/create", "WebAPI\Extensions\ReportsController@create")->name("web-api.extensions.reports.create");
            Route::post("/create", "WebAPI\Extensions\ReportsController@store")->name("web-api.extensions.reports.store");

            Route::post("/user-info", "WebAPI\Extensions\ReportsController@userInfo")->name("web-api.extensions.reports.user-info");

            Route::prefix("/{report}")->group(function () {
                Route::get("/", "WebAPI\Extensions\ReportsController@show")->name("web-api.extensions.reports.show");
                Route::delete("/", "WebAPI\Extensions\ReportsController@destroy")->name("web-api.extensions.reports.destroy");
                Route::get("/edit", "WebAPI\Extensions\ReportsController@edit")->name("web-api.extensions.reports.edit");
                Route::post("/update", "WebAPI\Extensions\ReportsController@update")->name("web-api.extensions.reports.update");
                Route::post("/duplicate", "WebAPI\Extensions\ReportsController@duplicate")->name("web-api.extensions.reports.duplicate");
            });
        });

        Route::prefix("/backup")->group(function () {
            Route::get("/", "WebAPI\Extensions\BackupController@index")->name("web-api.extensions.backup");
            Route::get("/create", "WebAPI\Extensions\BackupController@create")->name("web-api.extensions.backup.create");
            Route::post("/restore", "WebAPI\Extensions\BackupController@restore")->name("web-api.extensions.backup.restore");
        });
    });
});

Route::prefix("/payment")->group(function () {
    Route::get("/premium/{length}", "PaymentController@premium");
    Route::get("/failure", "PaymentController@failure")->name("payment.failure");

    Route::prefix("/extensions/{extension}")->group(function () {
        Route::get("/", "PaymentController@extensions")->name("payment.extensions");
        Route::get("/success", "PaymentController@extensionsSuccess")->name("payment.extensions.success");
    });
});

Route::get('/premium', 'PagesController@premium')->name('premium');
