<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="log_activity")
 */
class LogActivity extends \Kdyby\Doctrine\Entities\Attributes\Identifier {

	/**
	 * @ORM\Column(type="text")
	 */
	public $user;

	/**
	 * @ORM\Column(name="time_from",type="datetime")
	 */
	public $timeFrom;

	/**
	 * @ORM\Column(name="time_to",type="datetime")
	 */
	public $timeTo;

}
