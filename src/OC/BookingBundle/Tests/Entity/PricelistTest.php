<?php

namespace Tests\OC\BookingBundle\Entity;

use OC\BookingBundle\Entity\Pricelist;
use PHPUnit\Framework\TestCase;

class PricelistTest extends TestCase
{
    /**
     * Test tous les champs de l'entité Pricelist.
     *
     * @return [type] [description]
     */
    public function testEntity()
    {
        $pricelist = new Pricelist();
        $pricelist->setName('Normal');
        $pricelist->setAgefrom(10);
        $pricelist->setAgeto(20);
        $pricelist->setPrice(16.00);
        $pricelist->setDescription('de 10 à 20');
        $this->assertEquals('Normal', $pricelist->getName());
        $this->assertEquals(10, $pricelist->getAgefrom());
        $this->assertEquals(20, $pricelist->getAgeto());
        $this->assertEquals(16.00, $pricelist->getPrice());
        $this->assertEquals('de 10 à 20', $pricelist->getDescription());
        $this->assertNull($pricelist->getId());
    }
}
