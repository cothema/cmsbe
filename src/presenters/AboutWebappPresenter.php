<?php

namespace App\Presenters;

/**
 * AboutWebapp Presenter
 */
final class AboutWebappPresenter extends BasePresenter {

	public function renderDefault() {
		$navbar = [];
		$navbar[] = (object) ['name' => 'O aplikaci'];

		$this->template->navbar = $navbar;
	}

}
