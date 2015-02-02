<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;
use \Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="log_activity")
 */
class LogActivity extends Entities\BaseEntity {

	use Entities\Attributes\Identifier;

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
