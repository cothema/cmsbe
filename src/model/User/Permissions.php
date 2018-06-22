<?php

namespace Cothema\Model\User;

use Doctrine\ORM\Mapping as ORM;
use \Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_permission")
 */
class Permissions extends Entities\BaseEntity
{

    use Entities\Attributes\Identifier;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    public $user;

    /**
     * @ORM\Column(type="text")
     */
    public $section;

    /**
     * @ORM\Column(name="allowRead", type="boolean")
     */
    public $allowRead;

    /**
     * @ORM\Column(name="allowWrite", type="boolean")
     */
    public $allowWrite;

    /**
     * @ORM\Column(name="allowDelete", type="boolean")
     */
    public $allowDelete;
}
