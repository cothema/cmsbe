<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;
use \Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="bemenu")
 */
class BEMenu extends Entities\BaseEntity {

	use Entities\Attributes\Identifier;

	/**
	 * @ORM\Column(name="nLink",type="text")
	 */
	public $nLink;

	/**
	 * @ORM\Column(type="text")
	 */
	public $name;

	/**
	 * @ORM\Column(type="text")
	 */
	public $orderLine;

	/**
	 * @ORM\Column(type="text")
	 */
	public $parent;

	/**
	 * @ORM\Column(type="text")
	 */
	public $module;

	/**
	 * @ORM\Column(type="text")
	 */
	public $faIcon;

}
