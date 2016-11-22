<?php

namespace AwesomeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AwesomeBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="AwesomeBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */

    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="AwesomeBundle\Entity\User", inversedBy="tickets")
     */
    private $userCanSee;



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
     * Set title
     *
     * @param string $title
     *
     * @return Ticket
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Ticket
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Ticket
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param \AwesomeBundle\Entity\User $user
     *
     * @return Ticket
     */
    public function setUser(\AwesomeBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AwesomeBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userCanSee = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userCanSee
     *
     * @param \AwesomeBundle\Entity\User $userCanSee
     *
     * @return Ticket
     */
    public function addUserCanSee(\AwesomeBundle\Entity\User $userCanSee)
    {
        $this->userCanSee[] = $userCanSee;

        return $this;
    }

    /**
     * Remove userCanSee
     *
     * @param \AwesomeBundle\Entity\User $userCanSee
     */
    public function removeUserCanSee(\AwesomeBundle\Entity\User $userCanSee)
    {
        $this->userCanSee->removeElement($userCanSee);
    }

    /**
     * Get userCanSee
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserCanSee()
    {
        return $this->userCanSee;
    }
}
