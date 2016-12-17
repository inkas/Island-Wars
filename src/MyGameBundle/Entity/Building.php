<?php

namespace MyGameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Building
 *
 * @ORM\Table(name="buildings")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\BuildingRepository")
 */
class Building
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
     * @var BuildingCostResource[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\BuildingCostResource", mappedBy="building")
     */
    private $costs;

    /**
     * @var BuildingCostTime
     *
     * @ORM\OneToOne(targetEntity="MyGameBundle\Entity\BuildingCostTime", mappedBy="building")
     */
    private $timeCost;

    /**
     * @var IslandBuilding[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\IslandBuilding", mappedBy="building")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $islandBuildings;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    public function __construct()
    {
        $this->costs = new ArrayCollection();
        $this->islandBuildings = new ArrayCollection();
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
     * @return Building
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
     * @return BuildingCostResource[]
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * @param BuildingCostResource[] $costs
     */
    public function setCosts(array $costs)
    {
        $this->costs = $costs;
    }

    /**
     * @return BuildingCostTime
     */
    public function getTimeCost()
    {
        return $this->timeCost;
    }

    /**
     * @param BuildingCostTime $timeCost
     */
    public function setTimeCost(BuildingCostTime $timeCost)
    {
        $this->timeCost = $timeCost;
    }

    /**
     * @return IslandBuilding[]
     */
    public function getIslandBuildings()
    {
        return $this->islandBuildings;
    }

    /**
     * @param IslandBuilding[] $islandBuildings
     */
    public function setIslandBuildings(array $islandBuildings)
    {
        $this->islandBuildings = $islandBuildings;
    }




}

