<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TroopTrip
 *
 * @ORM\Table(name="troop_trips")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\TroopTripRepository")
 */
class TroopTrip
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
     * @var \DateTime
     *
     * @ORM\Column(name="arrives_on", type="datetime")
     */
    private $arrivesOn;

    /**
     * @var IslandTroop
     *
     * @ORM\OneToOne(targetEntity="MyGameBundle\Entity\IslandTroop", inversedBy="trip")
     * @ORM\JoinColumn(name="island_troop_id")
     */
    private $islandTroop;


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
     * Set arrivesOn
     *
     * @param \DateTime $arrivesOn
     *
     * @return TroopTrip
     */
    public function setArrivesOn($arrivesOn)
    {
        $this->arrivesOn = $arrivesOn;

        return $this;
    }

    /**
     * Get arrivesOn
     *
     * @return \DateTime
     */
    public function getArrivesOn()
    {
        return $this->arrivesOn;
    }

    /**
     * @return IslandTroop
     */
    public function getIslandTroop()
    {
        return $this->islandTroop;
    }

    /**
     * @param IslandTroop $islandTroop
     */
    public function setIslandTroop(IslandTroop $islandTroop)
    {
        $this->islandTroop = $islandTroop;
    }
}

