<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IslandBuilding
 *
 * @ORM\Table(name="island_buildings")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\IslandBuildingRepository")
 */
class IslandBuilding
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
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var Island
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Island", inversedBy="buildings")
     * @ORM\JoinColumn(name="island_id", nullable=false)
     */
    private $island;

    /**
     * @var Building
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Building", inversedBy="islandBuildings")
     * @ORM\JoinColumn(name="building_id", nullable=false)
     */
    private $building;

    /**
     * @var BuildingProcess
     *
     * @ORM\OneToOne(targetEntity="MyGameBundle\Entity\BuildingProcess", mappedBy="islandBuilding")
     */
    private $process;


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
     * Set level
     *
     * @param integer $level
     *
     * @return IslandBuilding
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
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
     * @return Building
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param Building $building
     */
    public function setBuilding(Building $building)
    {
        $this->building = $building;
    }

    /**
     * @return BuildingProcess
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param BuildingProcess $process
     */
    public function setProcess(BuildingProcess $process)
    {
        $this->process = $process;
    }
}

