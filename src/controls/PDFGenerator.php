<?php

namespace Cothema\CMSBE\Service;

/**
 * PDF Generator service - Wrapper for dompdf/dompdf
 *
 * @author Miloš Havlíček <miloshavlicek@gmail.com>
 */
class PDFGenerator extends \Nette\Object {

	/** @var \Latte\Engine	Template engine */
	protected $template;

	/** @var \DOMPDF	DOMPDF object */
	protected $domPDF = null;

	/** @var array	List of images used for template creation */
	protected $images = [];

	/** @var string	String with HTML code for export to PDF */
	protected $html = '';

	function __construct() {
		$this->prepareDomPDF();
	}

	function download($fileName) {
		return $this->getStream($fileName);
	}

	function getHTML() {
		if (empty($this->template)) {
			throw new \Exception('Template is not defined!');
		}

		return (string) $this->template;
	}

	function getPDF() {
		return $this->getDomPDF(true)->output();
	}

	function setTemplate($template, $file) {
		$template->setFile($file);

		$this->template = $template;
	}

	function setImages($images) {
		$this->images = $images;
	}

	private function getDomPDF($refresh = false) {
		if ($refresh) {
			$this->loadHtml();
		}

		$this->domPDF->render();

		return $this->domPDF;
	}

	private function loadHtml() {
		$this->domPDF->load_html($this->getHTML());
	}

	private function getStream($filename) {
		return $this->getDomPDF(true)->stream($filename);
	}

	private function prepareDomPDF() {
		if ($this->domPDF === null) {
			$this->initializeDomPDF();
		}

		$this->domPDF->set_paper('a4', 'portrait');
	}

	private function initializeDomPDF() {
		require_once DIR_VENDOR . '/dompdf/dompdf/dompdf_config.inc.php';

		if (!defined('DOMPDF_UNICODE_ENABLED')) {
			define('DOMPDF_UNICODE_ENABLED', true);
		}

		$this->domPDF = new \DOMPDF;
	}

}
