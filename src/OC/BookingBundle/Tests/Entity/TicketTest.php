<?php
namespace Tests\OC\BookingBundle\Entity;

use PHPUnit\Framework\TestCase;
use OC\BookingBundle\Entity\Ticket;
use OC\BookingBundle\Entity\Visitor;

class TicketTest extends TestCase
{
	/**
	 * Test tous les champs de l'entité Ticket
	 * @return [type] [description]
	 */
	public function testEntity()
	{
		$ticket = new Ticket();
		$ticket->setVisit(new \DateTime('2017-06-01'));
		$ticket->setDuration(1);
		$ticket->setEmail('nobody@nowhere.com');
		$ticket->setNbticket(10);
		$ticket->setAmount(100.00);
		$ticket->setCode('AB12');
		$ticket->setPaymentdate(new \DateTime('2017-06-02'));
		$ticket->setOrderdate(new \DateTime('2017-06-03'));


		$this->assertNull($ticket->getId());
		$this->assertEquals('2017-06-01', $ticket->getVisit()->format('Y-m-d'));
		$this->assertEquals(1, $ticket->getDuration());
		$this->assertEquals('nobody@nowhere.com', $ticket->getEmail());
		$this->assertEquals(10, $ticket->getNbticket());
		$this->assertEquals(100.00, $ticket->getAmount());
		$this->assertEquals('AB12', $ticket->getCode());

		$this->assertEquals('2017-06-02', $ticket->getPaymentdate()->format('Y-m-d'));
		$this->assertEquals('2017-06-03', $ticket->getOrderdate()->format('Y-m-d'));

		$visitor = new Visitor();
		$visitor->setName('Prénom');
		$visitor->setSurname('Nom');
		$visitor->setBirthday(new \DateTime('1963-11-26'));
		$visitor->setCountry('FR');
		$visitor->setAmount(100);
		$visitor->setReduced(true);
		$ticket->addVisitor($visitor);
		$this->assertCount(1, $ticket->getVisitors());

		$ticket->removeVisitor($visitor);
		$this->assertCount(0, $ticket->getVisitors());
	}
}