<?php

namespace App\Presenters;

use App\Cothema\Admin\Docs;

/**
 * Cust Phone List Presenter
 */
final class DocsPresenter extends BasePresenter {

	public function renderList() {
		$docs = $this->getDocsAll();

		$this->template->docs = $docs;

		$navbar = [];
		$navbar[] = (object) ['name' => 'Dokumenty'];

		$this->template->navbar = $navbar;
	}

	public function renderProcedures() {
		$doc = $this->getDocByAlias('postupy');

		$this->template->doc = $doc;
	}

	public function renderDetail() {
		$id = $this->params['id'];

		$doc = $this->getDocById($id);

		$this->template->doc = $doc;

		$navbar = [];
		$navbar[] = (object) ['link' => 'Docs:list', 'name' => 'Dokumenty'];
		$navbar[] = (object) ['name' => 'Detail'];

		$this->template->navbar = $navbar;
	}

	public function renderBasicIt() {
		$doc = $this->getDocByAlias('zakladni_udaje_IT');

		$this->template->doc = $doc;
	}

	private function getDocsAll() {
		$dao = $this->em->getDao(Docs::getClassName());
		$out = $dao->findBy([], ['id' => 'ASC']);

		return $out;
	}

	private function getDocById($id) {
		$dao = $this->em->getDao(Docs::getClassName());
		$out = $dao->find($id);

		return $out;
	}

	private function getDocByAlias($alias) {
		$dao = $this->em->getDao(Docs::getClassName());
		$out = $dao->findBy(['alias' => $alias]);

		if (isset($out[0])) {
			return $out[0];
		}

		return null;
	}

}
