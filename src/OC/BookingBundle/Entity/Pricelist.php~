<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pricelist
 *
 * @ORM\Table(name="pricelist")
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\PricelistRepository")
 */
class Pricelist
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="agefrom", type="smallint")
     */
    private $agefrom;

    /**
     * @var int
     *
     * @ORM\Column(name="ageto", type="smallint")
     */
    private $ageto;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

   /**
     * @var visitor
     *
     * @ORM\OneToMany(targetEntity="OC\BookingBundle\Entity\Visitor", mappedBy="pricelist")
     */
    private $visitors;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return pricelist
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set agefrom
     *
     * @param integer $agefrom
     *
     * @return pricelist
     */
    public function setAgefrom($agefrom)
    {
        $this->agefrom = $agefrom;

        return $this;
    }

    /**
     * Get agefrom
     *
     * @return int
     */
    public function getAgefrom()
    {
        return $this->agefrom;
    }

    /**
     * Set ageto
     *
     * @param integer $ageto
     *
     * @return pricelist
     */
    public function setAgeto($ageto)
    {
        $this->ageto = $ageto;

        return $this;
    }

    /**
     * Get ageto
     *
     * @return int
     */
    public function getAgeto()
    {
        return $this->ageto;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return pricelist
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return pricelist
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visitors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add visitor
     *
     * @param \OC\BookingBundle\Entity\Visitor $visitor
     *
     * @return Pricelist
     */
    public function addVisitor(\OC\BookingBundle\Entity\Visitor $visitor)
    {
        $this->visitors[] = $visitor;

        return $this;
    }

    /**
     * Remove visitor
     *
     * @param \OC\BookingBundle\Entity\Visitor $visitor
     */
    public function removeVisitor(\OC\BookingBundle\Entity\Visitor $visitor)
    {
        $this->visitors->removeElement($visitor);
    }

    /**
     * Get visitors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * Get visitor
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitor()
    {
        return $this->visitor;
    }
}
