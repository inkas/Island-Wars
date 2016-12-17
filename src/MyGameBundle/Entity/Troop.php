<?php

namespace MyGameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Troop
 *
 * @ORM\Table(name="troops")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\TroopRepository")
 */
class Troop
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="attack", type="integer")
     */
    private $attack;

    /**
     * @var int
     *
     * @ORM\Column(name="health", type="integer")
     */
    private $health;

    /**
     * @var IslandTroop[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\IslandTroop", mappedBy="troop")
     */
    private $islandTroops;

    /**
     * @var TroopCostResource[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\TroopCostResource", mappedBy="troop")
     */
    private $costs;

    /**
     * @var TroopCostTime
     *
     * @ORM\OneToOne(targetEntity="MyGameBundle\Entity\TroopCostTime", mappedBy="troop")
     */
    private $timeCost;

    /**
     * @var TroopProcess[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\TroopProcess", mappedBy="troop")
     */
    private $troopProcess;


    public function __construct()
    {
        $this->islandTroops = new ArrayCollection();
        $this->costs = new ArrayCollection();
        $this->troopProcess = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Troop
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
     * @return int
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @param int $health
     */
    public function setHealth($health)
    {
        $this->health = $health;
    }

    /**
     * @return IslandTroop[]
     */
    public function getIslandTroops()
    {
        return $this->islandTroops;
    }

    /**
     * @param IslandTroop[] $islandTroops
     */
    public function setIslandTroops(array $islandTroops)
    {
        $this->islandTroops = $islandTroops;
    }

    /**
     * @return TroopCostResource[]
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * @param TroopCostResource[] $costs
     */
    public function setCosts(array $costs)
    {
        $this->costs = $costs;
    }

    /**
     * @return TroopCostTime
     */
    public function getTimeCost()
    {
        return $this->timeCost;
    }

    /**
     * @param TroopCostTime $timeCost
     */
    public function setTimeCost(TroopCostTime $timeCost)
    {
        $this->timeCost = $timeCost;
    }

    /**
     * @return TroopProcess[]
     */
    public function getTroopProcess()
    {
        return $this->troopProcess;
    }

    /**
     * @param TroopProcess[] $troopProcess
     */
    public function setTroopProcess(array $troopProcess)
    {
        $this->troopProcess = $troopProcess;
    }

    /**
     * @return int
     */
    public function getAttack()
    {
        return $this->attack;
    }

    /**
     * @param int $attack
     */
    public function setAttack(int $attack)
    {
        $this->attack = $attack;
    }
}

