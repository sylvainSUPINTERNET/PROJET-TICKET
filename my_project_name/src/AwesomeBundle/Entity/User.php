<?php
// src/AppBundle/Entity/User.php

namespace AwesomeBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="AwesomeBundle\Entity\Ticket", mappedBy="userCanSee")
     */
    protected $tickets;


    public function __construct()
    {
        parent::__construct();

        //user default
        $role = $this->roles = array('ROLE_USER');
        $this->addRole($role[0]);

        //admin
        /*
         *  $role = $this->roles = array('ROLE_ADMIN');
         *  $this->addRole($role[0]);
         */
    }


    /**
     * Add ticket
     *
     * @param \AwesomeBundle\Entity\Ticket $ticket
     *
     * @return User
     */
    public function addTicket(\AwesomeBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AwesomeBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AwesomeBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
