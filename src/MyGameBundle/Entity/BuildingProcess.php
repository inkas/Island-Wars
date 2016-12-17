<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BuildingProcess
 *
 * @ORM\Table(name="building_process")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\BuildingProcessRepository")
 */
class BuildingProcess
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
     * @var IslandBuilding
     *
     * @ORM\OneToOne(targetEntity="MyGameBundle\Entity\IslandBuilding", inversedBy="process")
     * @ORM\JoinColumn(name="island_building_id", nullable=false)
     */
    private $islandBuilding;


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
     * @return BuildingProcess
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
     * @return IslandBuilding
     */
    public function getIslandBuilding()
    {
        return $this->islandBuilding;
    }

    /**
     * @param IslandBuilding $islandBuilding
     */
    public function setIslandBuilding(IslandBuilding $islandBuilding)
    {
        $this->islandBuilding = $islandBuilding;
    }


}

