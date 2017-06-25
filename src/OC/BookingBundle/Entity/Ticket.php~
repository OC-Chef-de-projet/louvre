<?php

namespace OC\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use OC\BookingBundle\Validator\Constraints as BookingAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="OC\BookingBundle\Repository\TicketRepository")
 *
 * @BookingAssert\Capacity(max = 12)
 * @BookingAssert\HalfDay
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
     * @Assert\DateTime(format = "Y-m-d H:i:s")
     * @BookingAssert\Isopen
     */
    private $visit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paymentdate", type="datetime", nullable=true)
     * @Assert\Datetime(format = "Y-m-d H:i:s")
     */
    private $paymentdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="orderdate", type="datetime")
     * @Assert\Datetime(format = "Y-m-d H:i:s")
     */
    private $orderdate;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="smallint")
     * @Assert\Choice(choices = {Ticket::DAY, Ticket::HALFDAY},strict = true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotNull()
     * @Assert\Email(checkMX = true )
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="nbticket", type="integer")
     * @Assert\Range( min = 1, max = 10)
     */
    private $nbticket;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     * @Assert\Type(type = "numeric")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string")
     */
    private $code;

    



    public function __construct()
    {
    	// valeurs par défault à la création
        $this->setNbticket(1);
        $this->setDuration(Ticket::DAY);
        $this->setEmail('nobody@nowhere.com');
        $this->setAmount(0);
        $this->setCode('NOCODE');
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
        if(is_object($visit)){
            $this->visit = $visit;
        } else {
            $this->visit = new \DateTime($visit);
        }
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

    /**
     * Set paymentdate
     *
     * @param \DateTime $paymentdate
     *
     * @return Ticket
     */
    public function setPaymentdate($paymentdate)
    {
        $this->paymentdate = $paymentdate;

        return $this;
    }

    /**
     * Get paymentdate
     *
     * @return \DateTime
     */
    public function getPaymentdate()
    {
        return $this->paymentdate;
    }

    /**
     * Set orderdate
     *
     * @param \DateTime $orderdate
     *
     * @return Ticket
     */
    public function setOrderdate($orderdate)
    {
        $this->orderdate = $orderdate;

        return $this;
    }

    /**
     * Get orderdate
     *
     * @return \DateTime
     */
    public function getOrderdate()
    {
        return $this->orderdate;
    }

}
