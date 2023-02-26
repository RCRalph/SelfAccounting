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

    // Income and expences routes
    foreach (["income", "expences"] as $type) {
        Route::group(["prefix" => "/$type", "middleware" => "income-expences:$type"], function () use ($type) {
            Route::prefix("/currency/{currency}")->group(function () use ($type) {
                Route::get("/", "WebAPI\IncomeExpencesController@index")->name("web-api.$type");
                Route::post("/", "WebAPI\IncomeExpencesController@store")->name("web-api.$type.store");
                Route::get("/data", "WebAPI\IncomeExpencesController@data")->name("web-api.$type.data");
                Route::get("/list", "WebAPI\IncomeExpencesController@list")->name("web-api.$type.list");
            });

            Route::prefix("/{id}")->group(function () use ($type) {
                Route::get("/", "WebAPI\IncomeExpencesController@show")->name("web-api.$type.show");
                Route::patch("/", "WebAPI\IncomeExpencesController@update")->name("web-api.$type.update");
                Route::delete("/", "WebAPI\IncomeExpencesController@destroy")->name("web-api.$type.destroy");
            });
        });
    }

    Route::prefix("/transfers")->group(function () {
        Route::get("/", "WebAPI\TransfersController@data")->name("web-api.transfers.data");
        Route::post("/", "WebAPI\TransfersController@store")->name("web-api.transfers.store");

        Route::prefix("/currency/{currency}")->group(function () {
            Route::get("/", "WebAPI\TransfersController@index")->name("web-api.transfers");
            Route::get("/list", "WebAPI\TransfersController@list")->name("web-api.transfers.list");
        });

        Route::prefix("/{transfer}")->group(function () {
            Route::get("/", "WebAPI\TransfersController@show")->name("web-api.transfers.show");
            Route::patch("/", "WebAPI\TransfersController@update")->name("web-api.transfers.update");
            Route::delete("/", "WebAPI\TransfersController@destroy")->name("web-api.transfers.destroy");
        });
    });

    Route::prefix("/categories")->group(function () {
        Route::get("/icons", "WebAPI\CategoriesController@icons")->name("web-api.categories.icons");

        Route::prefix("/{currency}")->group(function () {
            Route::get("/", "WebAPI\CategoriesController@index")->name("web-api.categories");
            Route::post("/", "WebAPI\CategoriesController@create")->name("web-api.categories.create");
        });

        Route::prefix("/category/{category}")->group(function () {
            Route::get("/", "WebAPI\CategoriesController@show")->name("web-api.categories.show");
            Route::patch("/", "WebAPI\CategoriesController@update")->name("web-api.categories.update");
            Route::delete("/", "WebAPI\CategoriesController@delete")->name("web-api.categories.delete");
        });
    });

    Route::prefix("/accounts")->group(function () {
        Route::prefix("/{currency}")->group(function () {
            Route::get("/", "WebAPI\AccountsController@index")->name("web-api.accounts");
            Route::post("/", "WebAPI\AccountsController@create")->name("web-api.accounts.create");
        });

        Route::prefix("/account/{account}")->group(function () {
            Route::get("/", "WebAPI\AccountsController@show")->name("web-api.accounts.show");
            Route::patch("/", "WebAPI\AccountsController@update")->name("web-api.accounts.update");
            Route::delete("/", "WebAPI\AccountsController@delete")->name("web-api.accounts.delete");
        });
    });

    Route::prefix("/profile")->group(function () {
        Route::get("/", "WebAPI\ProfileController@index")->name("web-api.profile");
        Route::delete("/", "WebAPI\ProfileController@destroy")->name("web-api.profile.delete");
        Route::post("/darkmode", "WebAPI\ProfileController@toggleDarkmode")->name("web-api.profile.darkmode");

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
