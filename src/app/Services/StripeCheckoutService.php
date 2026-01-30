<?php

namespace App\Services;

class StripeCheckoutService
{
    public function retrieveCheckoutSession(string $sessionId)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        return \Stripe\Checkout\Session::retrieve($sessionId);
    }
}
