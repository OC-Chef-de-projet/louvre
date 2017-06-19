<?php
namespace OC\BookingBundle\Service;

use Stripe\Stripe;
use Stripe\Charge;

class StripePayment
{

    public function charge($data)
    {

        Stripe::setApiKey("sk_test_CZT8FaxCvhqRgJmIJYGdUFzs");

        $result = Charge::create([
            'amount'      => (int)$data['amount'] * 100,
            'currency'    => 'EUR',
            'source'      => $data['token'],
            'description' => $data['email']
        ]);
    }
}
