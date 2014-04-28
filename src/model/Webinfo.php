<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Webinfo extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(type="text")
     */
    protected $webName;

    /**
     * @ORM\Column(type="text")
     */
    protected $website;

    /**
     * @ORM\Column(type="text")
     */
    protected $webAdmin;

    public function getWebName()
    {
        return $this->webName;
    }

    public function setWebName($in)
    {
        $this->webName = $in;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function seWebsite($in)
    {
        $this->website = $in;
    }

    public function getWebAdmin()
    {
        return $this->webAdmin;
    }

    public function setWebAdmin($in)
    {
        $this->webAdmin = $in;
    }

}
