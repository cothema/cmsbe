<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Custom extends \Kdyby\Doctrine\Entities\Attributes\Identifier {

	/**
	 * @ORM\Column(type="text")
	 */
	public $name;

	/**
	 * @ORM\Column(type="text")
	 */
	public $alias;

	/**
	 * @ORM\Column(type="text")
	 */
	public $type;

	/**
	 * @ORM\Column(name="def_val",type="text")
	 */
	public $defVal;

}
