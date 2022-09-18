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
        if ($length == 1) {
            return auth()->user()
                ->newSubscription("premium", "price_1LiOD9AmJbobJ1Gs4sXJhXvJ")
                ->checkout([
                    "success_url" => route("payment.premium.success", $length) . "?session_id={CHECKOUT_SESSION_ID}",
                    "cancel_url" => route("payment.failure")
                ]);
        }
        else if ($length == 12) {

        }

        return redirect()->route("payment.failure");
    }

    public function premiumSuccess()
    {
        if (request("session_id") && auth()->user()->stripe()->checkout->sessions->retrieve(request("session_id"))->payment_status == "paid") {
            return redirect("/app#/profile");
        }

        return redirect()->route("payment.failure");
    }
}
