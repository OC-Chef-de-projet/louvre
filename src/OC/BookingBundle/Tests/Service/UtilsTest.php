<?php
namespace OC\BookingBundle\Tests\Service;

use OC\BookingBundle\Service\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    public function testAge()
    {
    	$age = new Utils();

    	$refDate = new \DateTime('now - 1 year');
        $result = $age->getAge($refDate->format('Y-m-d'));
        $this->assertEquals(1, $result);

    	$refDate = new \DateTime('now - 2 years + 2 days');
        $result = $age->getAge($refDate->format('Y-m-d'));
        $this->assertEquals(1, $result);


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
