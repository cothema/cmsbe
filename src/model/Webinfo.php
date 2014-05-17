<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_webinfo")
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

    /**
     * @ORM\Column(type="text")
     */
    protected $company;

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($in)
    {
        $this->company = $in;
    }

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
