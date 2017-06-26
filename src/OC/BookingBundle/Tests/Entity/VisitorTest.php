<?php
namespace Tests\OC\BookingBundle\Entity;

use PHPUnit\Framework\TestCase;
use OC\BookingBundle\Entity\Visitor;
use OC\BookingBundle\Entity\Ticket;

class VisitorTest extends TestCase
{
	/**
	 * Test tous les champs de l'entité Visitor
	 * @return [type] [description]
	 */
	public function testEntity()
	{
		$visitor = new Visitor();
		$visitor->setName('Prénom');
		$visitor->setSurname('Nom');
		$visitor->setBirthday(new \DateTime('1963-11-26'));
		$visitor->setCountry('FR');
		$visitor->setAmount(100);
		$visitor->setReduced(true);

		$this->assertNull($visitor->getId());
		$this->assertEquals('Prénom', $visitor->getName());
		$this->assertEquals('Nom', $visitor->getSurname());
		$this->assertEquals('1963-11-26', $visitor->getBirthday()->format('Y-m-d'));
		$this->assertEquals('FR', $visitor->getCountry());
		$this->assertEquals(100, $visitor->getAmount());
		$this->assertTrue($visitor->getReduced());

		$ticket = new Ticket();
		$visitor->setTicket($ticket);
		$ticket->setEmail('ps.augereau@gmail.com');
		$t = $visitor->getTicket();
		$this->assertEquals('ps.augereau@gmail.com', $t->getEmail());

	}
}