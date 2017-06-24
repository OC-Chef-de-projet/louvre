<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use OC\BookingBundle\Entity\Ticket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Visitor
 *
 * @ORM\Table(name="visitor")
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\VisitorRepository")
 */
class Visitor
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
     * @Assert\Length(min = 1, max = 50)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     * @Assert\Length(min = 1,max = 50)
     * @Assert\NotBlank()
     */
    private $surname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date")
     * @Assert\DateTime(format = "Y-m-d")
     */
    private $birthday;


    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=3)
     * @Assert\Country()
     */
    private $country;

    /**
     * @var decimal
     *
     *  @ORM\Column(name="amount", type="decimal")
     *  @Assert\Type(type = "numeric")
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="visitors")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    private $ticket;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Pricelist", inversedBy="visitor")
     * @ORM\JoinColumn(name="pricelist_id", referencedColumnName="id")
     */
    private $pricelist;

   /**
    * @var boolean $isEnabled
    *
    * @ORM\Column(name="reduced", type="boolean")
    * @Assert\Type("bool")
    */
    private $reduced;



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
     * @return visitor
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
     * Set surname
     *
     * @param string $surname
     *
     * @return visitor
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return visitor
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

 
 

    /**
     * Set country
     *
     * @param string $country
     *
     * @return visitor
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set ticket
     *
     * @param \OC\BookingBundle\Entity\Ticket $ticket
     *
     * @return Visitor
     */
    public function setTicket(\OC\BookingBundle\Entity\Ticket $ticket = null)
    {
        $this->ticket = $ticket;

        return $this;
    }


    /**
     * Get ticket
     *
     * @return \OC\BookingBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Visitor
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setAmount(0);

        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ticket
     *
     * @param \OC\BookingBundle\Entity\Ticket $ticket
     *
     * @return Visitor
     */
    public function addTicket(\OC\BookingBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \OC\BookingBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\OC\BookingBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set reduced
     *
     * @param boolean $reduced
     *
     * @return Visitor
     */
    public function setReduced($reduced)
    {
        $this->reduced = $reduced;

        return $this;
    }

    /**
     * Get reduced
     *
     * @return boolean
     */
    public function getReduced()
    {
        return $this->reduced;
    }

    /**
     * Set pricelist
     *
     * @param \OC\BookingBundle\Entity\Pricelist $pricelist
     *
     * @return Visitor
     */
    public function setPricelist(\OC\BookingBundle\Entity\Pricelist $pricelist = null)
    {
        $this->pricelist = $pricelist;

        return $this;
    }

    /**
     * Get pricelist
     *
     * @return \OC\BookingBundle\Entity\Pricelist
     */
    public function getPricelist()
    {
        return $this->pricelist;
    }
}
