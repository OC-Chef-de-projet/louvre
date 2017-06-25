<?php
namespace Tests\OC\BookingBundle\Service;

use OC\BookingBundle\Service\StripePayment;
use PHPUnit\Framework\TestCase;

class StripePaymentTest extends TestCase
{

	public function testException()
	{
		try {
			$stripePayment = new StripePayment();
			$data['amount'] = 100;
			$data['token'] = '123456789';
			$data['email'] = 'ps.augereau@gmail.com';
			$stripePayment->charge($data);
			$this->fail('exception not expected');
		} catch (Exception $e) {
			error_log("(((((((((((((((");
		}
	}
}