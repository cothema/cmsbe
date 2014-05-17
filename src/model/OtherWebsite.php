<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_otherweb")
 */
class OtherWebsite extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $url;

    /**
     * @ORM\Column(type="text")
     */
    protected $orderLine;

    /**
     * @ORM\Column(type="text")
     */
    protected $groupLine;

    public function getName()
    {
        return $this->name;
    }

    public function setName($in)
    {
        $this->name = $in;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($in)
    {
        $this->url = $in;
    }

    public function getOrderLine()
    {
        return $this->orderLine;
    }

    public function setOrderLine($in)
    {
        $this->orderLine = $in;
    }

    public function getGroupLine()
    {
        return $this->groupLine;
    }

    public function setGroupLine($in)
    {
        $this->groupLine = $in;
    }

}
