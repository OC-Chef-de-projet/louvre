<?php
namespace OC\BookingBundle\Service;

use Stripe\Stripe;
use Stripe\Charge;

class StripePayment
{

    private $key;

    public function __construct($key)
    {
        $this->key = $key;

    }

    public function charge($data)
    {

        Stripe::setApiKey("sk_test_CZT8FaxCvhqRgJmIJYGdUFzs");

        $charge = Charge::create([
                "amount"      => (int)$data['amount'] * 100,
                "currency"    => 'EUR',
                "source"      => $data['token'],
                "description" => $data['email']
        ]);
    }
}
