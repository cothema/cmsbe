<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_custom")
 */
class UserCustom extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(type="text")
     */
    protected $user;

    /**
     * @ORM\Column(type="text")
     */
    protected $custom;

    /**
     * @ORM\Column(name="cust_val",type="text")
     */
    protected $custVal;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($in)
    {
        $this->user = $in;
    }

    public function getCustom()
    {
        return $this->custom;
    }

    public function setCustom($in)
    {
        $this->custom = $in;
    }

    public function getCustVal()
    {
        return $this->custVal;
    }

    public function setCustVal($in)
    {
        $this->custVal = $in;
    }

}
