<?php

namespace Cothema\CMSBE\Controls\Grido;

use \Grido\Components\Filters\Filter;

/**
 * Base of grid.
 */
class Grid extends \Grido\Grid {

	function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);

		$lang = $parent->translator->getLocale();
		$gridLang = ($lang === 'cz' ? 'cs' : 'en');

		$this->getTranslator()->setLang($gridLang);

		$this->filterRenderType = Filter::RENDER_INNER;
		$this->setExport();
	}

	function addColumnText($name, $label, $default = TRUE) {
		$column = parent::addColumnText($name, $label);

		if ($default === TRUE) {
			$column->setSortable()
					->setFilterText()
					->setSuggestion();
		}

		return $column;
	}

	function addColumnLongText($name, $label, $default = TRUE) {
		$column = parent::addColumnText($name, $label);

		if ($default === TRUE) {
			$column
					->setFilterText()
					->setSuggestion();
		}

		return $column;
	}

	function addColumnDate($name, $label, $dateFormat = NULL, $default = TRUE) {
		$column = parent::addColumnDate($name, $label, $dateFormat);

		if ($default === TRUE) {
			$column->setSortable()
					->setFilterDate();
		}

		return $column;
	}

}
