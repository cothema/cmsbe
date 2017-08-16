<?php

namespace App\ORM\Sys;

use Doctrine\ORM\Mapping as ORM;
use Cothema\DAO\Entities\StandardEntity;
use Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_pinned")
 */
class Pinned extends StandardEntity
{

    use Entities\Attributes\Identifier;

    /**
     * @ORM\Column(type="integer")
     */
    public $user;

    /**
     * @ORM\Column(type="text")
     */
    public $page;

    /**
     * @ORM\Column(type="text")
     */
    public $title;

    /**
     * @ORM\Column(type="text")
     */
    public $description;

    /**
     * @ORM\Column(name="faIcon", type="text")
     */
    public $faIcon;
}
