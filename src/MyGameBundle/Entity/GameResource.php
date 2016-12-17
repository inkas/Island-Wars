<?php

namespace MyGameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Resource
 *
 * @ORM\Table(name="resources")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\ResourceRepository")
 */
class GameResource
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
     * @var BuildingCostResource[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\BuildingCostResource", mappedBy="resource")
     */
    private $buildingCosts;

    /**
     * @var IslandResource[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\IslandResource", mappedBy="resource")
     */
    private $islandResources;

    /**
     * @var TroopCostResource[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\TroopCostResource", mappedBy="resource")
     */
    private $troopCosts;

    public function __construct()
    {
        $this->islandResources = new ArrayCollection();
        $this->buildingCosts = new ArrayCollection();
        $this->troopCosts = new ArrayCollection();
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
     * @return GameResource
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
     * @return IslandResource[]
     */
    public function getIslandResources()
    {
        return $this->islandResources;
    }

    /**
     * @param IslandResource[] $islandResources
     */
    public function setIslandResources(array $islandResources)
    {
        $this->islandResources = $islandResources;
    }

    /**
     * @return BuildingCostResource[]
     */
    public function getBuildingCosts()
    {
        return $this->buildingCosts;
    }

    /**
     * @param BuildingCostResource[] $buildingCosts
     */
    public function setBuildingCosts(array $buildingCosts)
    {
        $this->buildingCosts = $buildingCosts;
    }

    /**
     * @return TroopCostResource[]
     */
    public function getTroopCosts()
    {
        return $this->troopCosts;
    }

    /**
     * @param TroopCostResource[] $troopCosts
     */
    public function setTroopCosts(array $troopCosts)
    {
        $this->troopCosts = $troopCosts;
    }

}

