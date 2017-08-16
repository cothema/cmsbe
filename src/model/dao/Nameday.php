<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;
use Cothema\DAO\Entities\StandardEntity;
use Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="nameday")
 */
class Nameday extends StandardEntity
{

    use Entities\Attributes\Identifier;

    /**
     * @ORM\Column(type="integer")
     */
    public $day;

    /**
     * @ORM\Column(type="integer")
     */
    public $month;

    /**
     * @ORM\Column(type="text")
     */
    public $name;
}
