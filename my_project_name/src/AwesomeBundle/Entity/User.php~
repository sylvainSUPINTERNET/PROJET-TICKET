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

}
