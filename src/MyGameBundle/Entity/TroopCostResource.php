<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Resource;

/**
 * TroopCostResource
 *
 * @ORM\Table(name="troop_cost_resources")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\TroopCostResourceRepository")
 */
class TroopCostResource
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
     * @var Troop
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Troop", inversedBy="costs")
     * @ORM\JoinColumn(name="troop_id", nullable=false)
     */
    private $troop;

    /**
     * @var GameResource
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\GameResource", inversedBy="troopCosts")
     * @ORM\JoinColumn(name="resource_id", nullable=false)
     */
    private $resource;


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
     * @return TroopCostResource
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

}

