<?php
namespace OC\BookingBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PricelistRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetWithAge()
    {

        $tariff = $this->em
            ->getRepository('OCBookingBundle:Pricelist')
            ->getWithAge(53)
        ;
        // Not null
        $this->assertNotNull($tariff);

        // Price is 16.00
        $this->assertEquals(16.00,$tariff->getPrice());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
