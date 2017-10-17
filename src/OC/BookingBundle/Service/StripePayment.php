<?php

namespace OC\BookingBundle\Service;

use Stripe\Charge;
use Stripe\Stripe;

class StripePayment
{
    public function charge($data)
    {
        try {
            $stripe_error = '';
            Stripe::setApiKey('sk_test_CZT8FaxCvhqRgJmIJYGdUFzs');

            Charge::create([
                'amount'      => (int) $data['amount'] * 100,
                'currency'    => 'EUR',
                'source'      => $data['token'],
                'description' => $data['email'],
            ]);
        } catch (\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $stripe_error = $body['error']['code'];
        }

        return $stripe_error;
    }
}
