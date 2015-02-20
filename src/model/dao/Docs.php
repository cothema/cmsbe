<?php

namespace App\Cothema\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="c_doc")
 */
class Docs extends \Kdyby\Doctrine\Entities\IdentifiedEntity {

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
