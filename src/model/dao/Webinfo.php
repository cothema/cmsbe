<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;
use Cothema\DAO\Entities\StandardEntity;
use Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_webinfo")
 */
class Webinfo extends StandardEntity
{

    use Entities\Attributes\Identifier;

    /**
     * @ORM\Column(name="webName", type="text")
     */
    public $webName;

    /**
     * @ORM\Column(type="text")
     */
    public $website;

    /**
     * @ORM\Column(name="webAdmin", type="text")
     */
    public $webAdmin;

    /**
     * @ORM\Column(type="text")
     */
    public $company;

    /**
     * @ORM\Column(name="urlstats",type="text")
     */
    public $urlStats;

    /**
     * @ORM\Column(type="text")
     */
    public $systype;
}
