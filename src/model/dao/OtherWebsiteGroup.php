<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_otherweb_group")
 */
class OtherWebsiteGroup extends \Kdyby\Doctrine\Entities\Attributes\Identifier {

	/**
	 * @ORM\Column(type="text")
	 */
	public $name;

	/**
	 * @ORM\Column(type="text")
	 */
	public $orderLine;

}
