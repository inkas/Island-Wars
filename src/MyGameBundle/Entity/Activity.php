<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table(name="activities")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\ActivityRepository")
 */
class Activity
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
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Player", inversedBy="winActivities")
     * @ORM\JoinColumn(name="winner_id", nullable=false)
     */
    private $winner;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Player", inversedBy="loseActivities")
     * @ORM\JoinColumn(name="loser_id", nullable=false)
     */
    private $loser;

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
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Activity
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return Player
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param Player $winner
     */
    public function setWinner(Player $winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return Player
     */
    public function getLoser()
    {
        return $this->loser;
    }

    /**
     * @param Player $loser
     */
    public function setLoser(Player $loser)
    {
        $this->loser = $loser;
    }
}

