<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Custom extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $alias;

    /**
     * @ORM\Column(type="text")
     */
    protected $type;

    /**
     * @ORM\Column(name="def_val",type="text")
     */
    protected $defVal;

    public function getName()
    {
        return $this->name;
    }

    public function setName($in)
    {
        $this->name = $in;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($in)
    {
        $this->alias = $in;
    }

    public function geType()
    {
        return $this->type;
    }

    public function setType($in)
    {
        $this->type = $in;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($in)
    {
        $this->default = $in;
    }

}
