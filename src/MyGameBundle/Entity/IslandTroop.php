<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IslandTroop
 *
 * @ORM\Table(name="island_troops")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\IslandTroopRepository")
 */
class IslandTroop
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
     * @var Island
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Island", inversedBy="troops")
     * @ORM\JoinColumn(name="island_id", nullable=false)
     */
    private $island;

    /**
     * @var Troop
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Troop", inversedBy="islandTroops")
     * @ORM\JoinColumn(name="troop_id", nullable=false)
     */
    private $troop;

    /**
     * @var TroopTrip
     *
     * @ORM\OneToOne(targetEntity="MyGameBundle\Entity\TroopTrip", mappedBy="islandTroop")
     */
    private $trip;


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
     * @return IslandTroop
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
     * @return Island
     */
    public function getIsland()
    {
        return $this->island;
    }

    /**
     * @param Island $island
     */
    public function setIsland(Island $island)
    {
        $this->island = $island;
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

    /**
     * @return TroopTrip
     */
    public function getTrip()
    {
        return $this->trip;
    }

    /**
     * @param TroopTrip $trip
     */
    public function setTrip(TroopTrip $trip)
    {
        $this->trip = $trip;
    }
}

