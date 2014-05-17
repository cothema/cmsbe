<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bemenu")
 */
class BEMenu extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(name="nLink",type="text")
     */
    protected $nLink;

    /**
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $orderLine;

    /**
     * @ORM\Column(type="text")
     */
    protected $parent;

    /**
     * @ORM\Column(type="text")
     */
    protected $module;

    public function getNLink()
    {
        return $this->nLink;
    }

    public function setNLink($in)
    {
        $this->nLink = $in;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($in)
    {
        $this->name = $in;
    }

    public function getOrderLine()
    {
        return $this->orderLine;
    }

    public function setOrderLine($in)
    {
        $this->orderLine = $in;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($in)
    {
        $this->parent = $in;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($in)
    {
        $this->module = $in;
    }

}
