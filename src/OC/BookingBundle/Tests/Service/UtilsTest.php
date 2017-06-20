<?php
namespace OC\BookingBundle\Tests\Service;

use OC\BookingBundle\Service\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    public function testAge()
    {
        $age = new Utils();
        $result = $age->getAge('1963-11-26');
        $this->assertEquals(53, $result);
    }

    public function testPrettyDate()
    {
    	$dateRef = '2017-06-21';
    	$testDate = new \DateTime($dateRef);
        $prettyDate = new Utils();
        $result = $prettyDate->getPrettyDate('2017-06-21');
        $result = new \DateTime($result);

        $this->assertEquals($dateRef, $result->format('Y-m-d'));
    }
}
