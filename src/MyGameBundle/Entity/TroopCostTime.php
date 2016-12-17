<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TroopCostTime
 *
 * @ORM\Table(name="troop_cost_time")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\TroopCostTimeRepository")
 */
class TroopCostTime
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
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var Troop
     *
     * @ORM\OneToOne(targetEntity="MyGameBundle\Entity\Troop", inversedBy="timeCost")
     * @ORM\JoinColumn(name="troop_id", nullable=false)
     */
    private $troop;


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
     * Set amount
     *
     * @param integer $amount
     *
     * @return TroopCostTime
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Troop
     */
    public function getTroop()
    {
        return $this->troop;
    }

    /**
     * @param Troop $troop
     */
    public function setTroop(Troop $troop)
    {
        $this->troop = $troop;
    }

}

