<?php
namespace OC\BookingBundle\Tests\Service;

use OC\BookingBundle\Service\Opening;
use PHPUnit\Framework\TestCase;

use OC\BookingBundle\Service\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OpeningTest extends WebTestCase
{

	protected static $translation;

	public static function setUpBeforeClass() {
		$kernel = static::createKernel();
		$kernel->boot();
		self::$translation = $kernel->getContainer()->get('translator');
	}


    public function testBankHolidays()
    {
    	$year = '2018';
    	$u = new Utils();
    	$opening = new Opening($u,self::$translation);
		$d = new \DateTime($year.'-05-01');
		$result = $opening->isOpen($d);
		$this->assertEquals(false, $result['open']);

		$d = new \DateTime($year.'-11-01');
		$result = $opening->isOpen($d);
		$this->assertEquals(false, $result['open']);

		// Pâques
		$easter = date("Y-m-d", easter_date($year));
		$d = new \DateTime($easter);
		$result = $opening->isOpen($d);
		$this->assertEquals(false, $result['open']);

		// Ascension
		$d = new \DateTime($easter.' +39 days');
		$result = $opening->isOpen($d);
		$this->assertEquals(false, $result['open']);

		// Pentecote
		$d = new \DateTime($easter.' +49 days');
		$result = $opening->isOpen($d);
		$this->assertEquals(false, $result['open']);
    }
}
