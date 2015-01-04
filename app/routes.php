<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

//\Log::info("URL Call: " . array_get($_SERVER, "REMOTE_ADDR", "127.0.0.1") . "/" . Request::method() . "/" . Request::path());

Route::model("mshipAccount", "\Models\Mship\Account\Account", function() {
    Redirect::to("/adm/mship/account");
});

/* * ** ADM *** */
Route::group(array("namespace" => "Controllers\Adm"), function() {
    Route::group(array("prefix" => "adm"), function() {
        // Login is the only unauthenticated page.
        Route::get("/", "Authentication@getLogin");
        Route::controller("/authentication", "Authentication");

        // Auth required
        Route::group(array("before" => "auth.admin"), function() {
            Route::controller("/dashboard", "Dashboard");
            Route::controller("/system", "System");

            Route::group(array("prefix" => "mship", "namespace" => "Mship"), function() {
                /* Route::get("/airport/{navdataAirport}", "Airport@getDetail")->where(array("navdataAirport" => "\d"));
                  Route::post("/airport/{navdataAirport}", "Airport@getDetail")->where(array("navdataAirport" => "\d")); */
                Route::get("/account/{mshipAccount}", "Account@getDetail");
                Route::controller("/account", "Account");
            });
        });
    });
});

Route::group(array("namespace" => "Controllers"), function() {
    Route::group(array("prefix" => "mship", "namespace" => "Mship"), function() {
        // Guest access
        Route::controller("authentication", "Authentication");
        Route::controller("auth", "Authentication"); // Legacy URL.  **DO NOT REMOVE**
        Route::get("manage/landing", "Management@get_landing");

        Route::group(array("before" => "auth.user.basic"), function() {
            Route::get("security/forgotten-link/{code}", "Security@get_forgotten_link")->where(array("code" => "\w+"));
            Route::controller("security", "Security");
        });

        Route::group(array("before" => "auth.user.full"), function() {
            Route::get("manage/display", "Management@get_dashboard"); // Legacy URL.  **DO NOT REMOVE**
            Route::controller("manage", "Management");
        });
    });

    Route::group(array("prefix" => "sso", "namespace" => "Sso"), function() {
        Route::controller("auth", "Authentication");
        Route::controller("security", "Security");
    });
});

Route::get("/", "\Controllers\Mship\Management@get_landing");
