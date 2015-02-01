<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_otherweb")
 */
class OtherWebsite extends \Kdyby\Doctrine\Entities\Attributes\IdentifiedEntity {

	/**
	 * @ORM\Column(type="text")
	 */
	public $name;

	/**
	 * @ORM\Column(type="text")
	 */
	public $url;

	/**
	 * @ORM\Column(type="text")
	 */
	public $orderLine;

	/**
	 * @ORM\Column(type="text")
	 */
	public $groupLine;

}
