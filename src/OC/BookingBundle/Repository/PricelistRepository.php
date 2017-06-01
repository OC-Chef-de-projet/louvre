<?php

namespace OC\BookingBundle\Repository;

/**
 * pricelistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PricelistRepository extends \Doctrine\ORM\EntityRepository
{
	public function getWithAge($age){

		$qb = $this->createQueryBuilder('a');

		$qb->andWhere(':age BETWEEN a.agefrom AND a.ageto')
			->setParameter('age', $age);

		return $qb->getQuery()->getOneOrNullResult();
	}
}
