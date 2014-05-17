<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_otherweb_group")
 */
class OtherWebsiteGroup extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $orderLine;

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

}
