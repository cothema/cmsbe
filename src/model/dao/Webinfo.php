<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_webinfo")
 */
class Webinfo extends \Kdyby\Doctrine\Entities\Attributes\Identifier {

	/**
	 * @ORM\Column(type="text")
	 */
	public $webName;

	/**
	 * @ORM\Column(type="text")
	 */
	public $website;

	/**
	 * @ORM\Column(type="text")
	 */
	public $webAdmin;

	/**
	 * @ORM\Column(type="text")
	 */
	public $company;

	/**
	 * @ORM\Column(name="urlstats",type="text")
	 */
	public $urlStats;

	/**
	 * @ORM\Column(type="text")
	 */
	public $systype;

}
