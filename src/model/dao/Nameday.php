<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="nameday")
 */
class Nameday extends \Kdyby\Doctrine\Entities\BaseEntity {

	/**
	 * @ORM\Column(type="integer")
	 */
	public $day;

	/**
	 * @ORM\Column(type="integer")
	 */
	public $month;

	/**
	 * @ORM\Column(type="text")
	 */
	public $name;

}
