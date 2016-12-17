<?php

namespace MyGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sent_on", type="datetime")
     */
    private $sentOn;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Player", inversedBy="sentMessages")
     * @ORM\JoinColumn(name="receiver_id", nullable=false)
     */
    private $sender;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="MyGameBundle\Entity\Player", inversedBy="receivedMessages")
     * @ORM\JoinColumn(name="sender_id", nullable=false)
     */
    private $receiver;


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
     * Set text
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set sentOn
     *
     * @param \DateTime $sentOn
     *
     * @return Message
     */
    public function setSentOn($sentOn)
    {
        $this->sentOn = $sentOn;

        return $this;
    }

    /**
     * Get sentOn
     *
     * @return \DateTime
     */
    public function getSentOn()
    {
        return $this->sentOn;
    }

    /**
     * @return Player
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param Player $sender
     */
    public function setSender(Player $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return Player
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param Player $receiver
     */
    public function setReceiver(Player $receiver)
    {
        $this->receiver = $receiver;
    }


}

