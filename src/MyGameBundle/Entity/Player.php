<?php

namespace MyGameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="players")
 * @ORM\Entity(repositoryClass="MyGameBundle\Repository\PlayerRepository")
 * @UniqueEntity(fields="username", message="That username is taken")
 * @UniqueEntity(fields="email", message="That email is taken")
 */
class Player implements UserInterface
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
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     *
     * @ORM\Column(name="username", type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * Plain password
     *
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/",
     *     message="Use 1 upper case letter, 1 lower case letter, and 1 number"
     * )
     */
    private $plainPassword;

    /**
     * @var Island[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\Island", mappedBy="player")
     */
    private $islands;

    /**
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\Message", mappedBy="sender")
     */
    private $sentMessages;

    /**
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\Message", mappedBy="receiver")
     */
    private $receivedMessages;

    /**
     * @var Activity[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\Activity", mappedBy="winner")
     */
    private $winActivities;

    /**
     * @var Activity[]
     *
     * @ORM\OneToMany(targetEntity="MyGameBundle\Entity\Activity", mappedBy="loser")
     */
    private $loseActivities;


    public function __construct()
    {
        $this->islands = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->winActivities = new ArrayCollection();
        $this->loseActivities = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     *
     * @return Player
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Player
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set plain password
     *
     * @param string $plainPassword
     *
     * @return Player
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get plain password
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->islands = null;
        $this->plainPassword = null;
    }

    /**
     * @return Island[]
     */
    public function getIslands()
    {
        return $this->islands;
    }

    /**
     * @param Island[] $islands
     */
    public function setIslands(array $islands)
    {
        $this->islands = $islands;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return Message[]
     */
    public function getSentMessages()
    {
        return $this->sentMessages;
    }

    /**
     * @param Message[] $sentMessages
     */
    public function setSentMessages(array $sentMessages)
    {
        $this->sentMessages = $sentMessages;
    }

    /**
     * @return Message[]
     */
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    /**
     * @param Message[] $receivedMessages
     */
    public function setReceivedMessages(array $receivedMessages)
    {
        $this->receivedMessages = $receivedMessages;
    }

    /**
     * @return Activity[]
     */
    public function getWinActivities()
    {
        return $this->winActivities;
    }

    /**
     * @param Activity[] $winActivities
     */
    public function setWinActivities(array $winActivities)
    {
        $this->winActivities = $winActivities;
    }

    /**
     * @return Activity[]
     */
    public function getLoseActivities()
    {
        return $this->loseActivities;
    }

    /**
     * @param Activity[] $loseActivities
     */
    public function setLoseActivities(array $loseActivities)
    {
        $this->loseActivities = $loseActivities;
    }
}

