<?php

namespace Cothema\Model\User;

use Doctrine\ORM\Mapping as ORM;
use \Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends Entities\BaseEntity
{

    use Entities\Attributes\Identifier;

    /**
     * @ORM\Column(name="firstname",type="text")
     */
    public $firstName;

    /**
     * @ORM\Column(name="lastname",type="text")
     */
    public $lastName;

    /**
     * @ORM\Column(type="text")
     */
    public $email;

    /**
     * @ORM\Column(type="boolean")
     */
    public $active;

    /**
     * @ORM\Column(type="text")
     */
    public $username;

    /**
     * @ORM\Column(type="text")
     */
    public $role;

    /**
     * @ORM\Column(type="bigint")
     */
    public $facebookId;

    /**
     * @ORM\Column(type="text")
     */
    public $facebookToken;

    public function getFullName()
    {
        return trim($this->firstName . " " . $this->lastName);
    }
}
