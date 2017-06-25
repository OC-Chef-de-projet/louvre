<?php
namespace OC\BookingBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\BookingBundle\Entity\Pricelist;

class LoadPricelistData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $pricelist = new Pricelist();
		$pricelist->setName('Normal');
		$pricelist->setAgefrom(12);
		$pricelist->setAgeto(59);
		$pricelist->setPrice(16.00);
		$pricelist->setDescription('de 12 ans à 60 ans');
        $manager->persist($pricelist);

		$pricelist = new Pricelist();
		$pricelist->setName('Enfants');
		$pricelist->setAgefrom(5);
		$pricelist->setAgeto(11);
		$pricelist->setPrice(8.00);
		$pricelist->setDescription('de 4 ans à 12 ans');
        $manager->persist($pricelist);

		$pricelist = new Pricelist();
		$pricelist->setName('Senior');
		$pricelist->setAgefrom(60);
		$pricelist->setAgeto(999);
		$pricelist->setPrice(12.00);
		$pricelist->setDescription('audela de 60 ans');
        $manager->persist($pricelist);

		$pricelist = new Pricelist();
		$pricelist->setName('Réduit');
		$pricelist->setAgefrom(9999);
		$pricelist->setAgeto(9999);
		$pricelist->setPrice(10.00);
		$pricelist->setDescription('sous conditions');
        $manager->persist($pricelist);

		$pricelist = new Pricelist();
		$pricelist->setName('Gratuit');
		$pricelist->setAgefrom(0);
		$pricelist->setAgeto(4);
		$pricelist->setPrice(0.00);
		$pricelist->setDescription('Enfants de moins de 4 ans');
        $manager->persist($pricelist);

        $manager->flush();
    }
}
