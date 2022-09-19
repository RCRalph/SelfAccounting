<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Extension;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function failure()
    {
        return redirect()->route("app");
    }

    public function extensions(Extension $extension)
    {
        if (auth()->user()->extensions()->where("extensions.id", $extension->id)->exists()) {
            return redirect("/app#/extensions/$extension->directory");
        }

        return auth()->user()->checkout($extension->stripe_product_code,
            [
                "success_url" => route("payment.extensions.success", compact("extension")) . "?session_id={CHECKOUT_SESSION_ID}",
                "cancel_url" => route("payment.failure")
            ]
        );
    }

    public function extensionsSuccess(Extension $extension)
    {
        if (request("session_id") && auth()->user()->stripe()->checkout->sessions->retrieve(request("session_id"))->payment_status == "paid") {
            if (auth()->user()->extensions()->where("extensions.id", $extension->id)->doesntExist()) {
                auth()->user()->extensions()->attach($extension->id);
            }

            return redirect("/app#/extensions/$extension->directory");
        }

        return redirect()->route("payment.failure");
    }

    public function premium($length)
    {
        if (auth()->user()->subscribed("premium")) {
            return redirect(route("app") . "#/profile");
        }

        switch ($length) {
            case 1:
            case 12:
                return auth()->user()
                    ->newSubscription("premium", [
                        1 => env("STRIPE_PREMIUM_MONTH_PRICE"),
                        12 => env("STRIPE_PREMIUM_YEAR_PRICE")
                    ][$length])
                    ->checkout([
                        "success_url" => route("app") . "#/profile",
                        "cancel_url" => route("payment.failure")
                    ]);
            default:
                return redirect()->route("payment.failure");
        }
    }
}
