<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\TicketRepository")
 */
class Ticket
{
    /**  */
    const DAY = 1;
    const HALFDAY = 2;

    public $options;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visit", type="datetime")
     */
    private $visit;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="smallint")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="nbticket", type="integer")
     */
    private $nbticket;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string")
     */
    private $code;

    /**
     * @var visitor
     *
     * @ORM\OneToMany(targetEntity="OC\BookingBundle\Entity\Visitor", mappedBy="ticket", cascade="all", orphanRemoval=true)
     */
    private $visitors;



    public function __construct()
    {
    	// valeurs par défault à la création
        $this->setNbticket(1);
        $this->setDuration(Ticket::DAY);
        $this->setEmail('nobody@nowhere.com');
        $this->setAmount(0);
        $this->setCode('NOCODE');
        $this->visitors = new ArrayCollection();
    }


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
     * Set visit
     *
     * @param \DateTime $visit
     *
     * @return Ticket
     */
    public function setVisit($visit)
    {
        $this->visit = new \DateTime($visit);

        return $this;
    }

    /**
     * Get visit
     *
     * @return \DateTime
     */
    public function getVisit()
    {
        return $this->visit;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Ticket
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Ticket
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nbticket
     *
     * @param integer $nbticket
     *
     * @return Ticket
     */
    public function setNbticket($nbticket)
    {
        $this->nbticket = $nbticket;

        return $this;
    }

    /**
     * Get nbticket
     *
     * @return int
     */
    public function getNbticket()
    {
        return $this->nbticket;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Ticket
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
     * Add visitor
     *
     * @param \OC\BookingBundle\Entity\Visitor $visitor
     *
     * @return Ticket
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
     * Set code
     *
     * @param string $code
     *
     * @return Ticket
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
