<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;
use Cothema\DAO\Entities\StandardEntity;
use Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_custom")
 */
class UserCustom extends StandardEntity
{

    use Entities\Attributes\Identifier;

    /**
     * @ORM\Column(type="text")
     */
    public $user;

    /**
     * @ORM\Column(type="text")
     */
    public $custom;

    /**
     * @ORM\Column(name="cust_val",type="text")
     */
    public $custVal;
}
