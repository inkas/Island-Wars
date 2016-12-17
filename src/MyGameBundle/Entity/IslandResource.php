<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IslandResource
 *
 * @ORM\Table(name="island_resources")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\IslandResourceRepository")
 */
class IslandResource
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
     * @var Island
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Island", inversedBy="resources")
     * @ORM\JoinColumn(name="island_id", nullable=false)
     */
    private $island;

    /**
     * @var GameResource
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\GameResource", inversedBy="islandResources")
     * @ORM\JoinColumn(name="resource_id", nullable=false)
     */
    private $resource;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="refresh_on", type="datetime")
     */
    private $refreshOn;


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
     * @return IslandResource
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
     * @return GameResource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param GameResource $resource
     */
    public function setResource(GameResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return \DateTime
     */
    public function getRefreshOn()
    {
        return $this->refreshOn;
    }

    /**
     * @param \DateTime $refreshOn
     */
    public function setRefreshOn(\DateTime $refreshOn)
    {
        $this->refreshOn = $refreshOn;
    }
}

