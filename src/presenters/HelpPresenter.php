<?php

namespace App\Presenters;

/**
 * @Secured\User(loggedIn)
 * 
 * Help Presenter
 */
final class HelpPresenter extends BasePresenter {

	public function renderDefault() {

		$navbar = [];
		$navbar[] = (object) ['name' => 'Pomoc'];

		$this->template->navbar = $navbar;
		$this->template->yourIp = $_SERVER['REMOTE_ADDR'];
	}

}
