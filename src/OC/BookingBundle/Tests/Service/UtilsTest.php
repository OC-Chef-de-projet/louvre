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
}
