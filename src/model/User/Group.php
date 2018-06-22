<?php

namespace Cothema\Model\User;

use \Doctrine\ORM\Mapping as ORM;
use \Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_groups")
 */
class Group extends Entities\BaseEntity
{

    use Entities\Attributes\Identifier;

    /**
     * @ORM\Column(type="text")
     */
    public $abbrev;

    /**
     * @ORM\Column(type="text")
     */
    public $fullname;

    /**
     * @ORM\Column(type="text")
     */
    public $introd;
}
