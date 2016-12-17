<?php

namespace MyGameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Island
 *
 *
 * @ORM\Table(name="islands")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\IslandRepository")
 */
class Island
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
     * @ORM\Column(name="x", type="integer")
     */
    private $x;

    /**
     * @var int
     *
     * @ORM\Column(name="y", type="integer")
     */
    private $y;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Player", inversedBy="islands")
     * @ORM\JoinColumn(name="player_id", nullable=false)
     */
    private $player;

    /**
     * @var IslandResource[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\IslandResource", mappedBy="island")
     */
    private $resources;

    /**
     * @var IslandBuilding[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\IslandBuilding", mappedBy="island")
     */
    private $buildings;

    /**
     * @var IslandTroop[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\IslandTroop", mappedBy="island")
     */
    private $troops;

    /**
     * @var TroopProcess[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\TroopProcess", mappedBy="island")
     */
    private $process;


    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->buildings = new ArrayCollection();
        $this->troops = new ArrayCollection();
        $this->process = new ArrayCollection();
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
     * Set x
     *
     * @param integer $x
     *
     * @return Island
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param integer $y
     *
     * @return Island
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }

    /**
     * @return IslandResource[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param IslandResource[] $resources
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return IslandBuilding[]
     */
    public function getBuildings()
    {
        return $this->buildings;
    }

    /**
     * @param IslandBuilding[] $buildings
     */
    public function setBuildings(array $buildings)
    {
        $this->buildings = $buildings;
    }

    /**
     * @return IslandTroop[]
     */
    public function getTroops()
    {
        return $this->troops;
    }

    /**
     * @param IslandTroop[] $troops
     */
    public function setTroops(array $troops)
    {
        $this->troops = $troops;
    }

    /**
     * @return TroopProcess[]
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param TroopProcess[] $process
     */
    public function setProcess(array $process)
    {
        $this->process = $process;
    }
}

