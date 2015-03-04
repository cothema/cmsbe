<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;
use Cothema\DAO\Entities\StandardEntity;
use Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_otherweb")
 */
class OtherWebsite extends StandardEntity {

	use Entities\Attributes\Identifier;

	/**
	 * @ORM\Column(type="text")
	 */
	public $name;

	/**
	 * @ORM\Column(type="text")
	 */
	public $url;

	/**
	 * @ORM\Column(name="orderLine", type="text")
	 */
	public $orderLine;

	/**
	 * @ORM\Column(name="groupLine", type="text")
	 */
	public $groupLine;

}
