<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TroopProcess
 *
 * @ORM\Table(name="troop_process")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\TroopProcessRepository")
 */
class TroopProcess
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
     * @ORM\Column(name="finishes_on", type="datetime")
     */
    private $finishesOn;

    /**
     * @var Island
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Island", inversedBy="process")
     * @ORM\JoinColumn(name="island_id", nullable=false)
     */
    private $island;

    /**
     * @var Troop
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Troop", inversedBy="troopProcess")
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
     * Set finishesOn
     *
     * @param \DateTime $finishesOn
     *
     * @return TroopProcess
     */
    public function setFinishesOn($finishesOn)
    {
        $this->finishesOn = $finishesOn;

        return $this;
    }

    /**
     * Get finishesOn
     *
     * @return \DateTime
     */
    public function getFinishesOn()
    {
        return $this->finishesOn;
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
}

