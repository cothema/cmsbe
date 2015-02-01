<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_custom")
 */
class UserCustom extends \Kdyby\Doctrine\Entities\Attributes\IdentifiedEntity {

	/**
	 * @ORM\Column(type="text")
	 */
	public $user;

	/**
	 * @ORM\Column(type="text")
	 */
	public $custom;

	/**
	 * @ORM\Column(name="cust_val",type="text")
	 */
	public $custVal;

}
