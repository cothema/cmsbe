<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;
use \Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_otherweb_group")
 */
class OtherWebsiteGroup extends Entities\BaseEntity {

	use Entities\Attributes\Identifier;

	/**
	 * @ORM\Column(type="text")
	 */
	public $name;

	/**
	 * @ORM\Column(name="orderLine", type="text")
	 */
	public $orderLine;

}
