<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Extension;

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
}
