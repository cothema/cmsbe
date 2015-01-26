<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bemenu")
 */
class BEMenu extends \Kdyby\Doctrine\Entities\IdentifiedEntity {

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

    /**
     * @ORM\Column(type="text")
     */
    public $faIcon;

    function getNLink() {
        return $this->nLink;
    }

    function setNLink($in) {
        $this->nLink = $in;
    }

    function getName() {
        return $this->name;
    }

    function setName($in) {
        $this->name = $in;
    }

    function getOrderLine() {
        return $this->orderLine;
    }

    function setOrderLine($in) {
        $this->orderLine = $in;
    }

    function getParent() {
        return $this->parent;
    }

    function setParent($in) {
        $this->parent = $in;
    }

    function getModule() {
        return $this->module;
    }

    function setModule($in) {
        $this->module = $in;
    }

}
