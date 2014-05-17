<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="log_activity")
 */
class LogActivity extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(type="text")
     */
    protected $user;

    /**
     * @ORM\Column(name="time_from",type="datetime")
     */
    protected $timeFrom;

    /**
     * @ORM\Column(name="time_to",type="datetime")
     */
    protected $timeTo;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($in)
    {
        $this->user = $in;
    }

    public function getTimeFrom()
    {
        return $this->timeFrom;
    }

    public function setTimeFrom($in)
    {
        $this->timeFrom = $in;
    }

    public function getTimeTo()
    {
        return $this->timeTo;
    }

    public function setTimeTo($in)
    {
        $this->timeTo = $in;
    }

}
