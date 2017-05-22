<?php

namespace OC\TicketsBundle\Controller;

class OrderController extends Controller
{
	/**
	 * @Route("/prepare", name="order_prepare")
	 */
	public function prepare()
	{
		echo "OK";
		exit;
	}
}
