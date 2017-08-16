<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;
use Cothema\DAO\Entities\StandardEntity;
use Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="bemenu")
 */
class BEMenu extends StandardEntity
{

    use Entities\Attributes\Identifier;

    /**
     * @ORM\Column(name="nLink",type="text")
     */
    public $nLink;

    /**
     * @ORM\Column(type="text")
     */
    public $name;

    /**
     * @ORM\Column(name="orderLine", type="text")
     */
    public $orderLine;

    /**
     * @ORM\Column(type="text")
     */
    public $parent;

    /**
     * @ORM\Column(type="text")
     */
    public $module;

    /**
     * @ORM\Column(name="faIcon", type="text")
     */
    public $faIcon;
}
