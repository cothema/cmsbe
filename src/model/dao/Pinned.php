<?php

namespace App\ORM\Sys;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_pinned")
 */
class Pinned extends \Kdyby\Doctrine\Entities\Attributes\Identifier {

	/**
	 * @ORM\Column(type="integer")
	 */
	public $user;

	/**
	 * @ORM\Column(type="text")
	 */
	public $page;

	/**
	 * @ORM\Column(type="text")
	 */
	public $title;

	/**
	 * @ORM\Column(type="text")
	 */
	public $description;

	/**
	 * @ORM\Column(type="text")
	 */
	public $faIcon;

}
