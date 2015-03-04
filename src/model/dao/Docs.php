<?php

namespace App\Cothema\Admin;

use \Doctrine\ORM\Mapping as ORM;
use Cothema\DAO\Entities\StandardEntity;
use Kdyby\Doctrine\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="c_doc")
 */
class Docs extends StandardEntity {

	use Entities\Attributes\Identifier;

	/**
	 * @ORM\Column(type="text")
	 */
	public $file;

	/**
	 * @ORM\Column(type="text")
	 */
	public $type;

	/**
	 * @ORM\Column(type="text")
	 */
	public $link;

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
	public $note;

	public function getFormat() {
		$file = $this->file;

		$fileParts = explode('.', $file);

		$ext = $fileParts[count($fileParts) - 1];

		return $ext;
	}

}
