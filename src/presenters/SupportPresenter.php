<?php

namespace App\Presenters;

/**
 * @Secured
 * @Secured\User(loggedIn)
 * @Secured\Role(admin)
 * @author     Milos Havlicek <miloshavlicek@gmail.com>
 *
 * Support Presenter
 */
final class SupportPresenter extends BasePresenter {

	public function renderDefault() {
		$navbar = [];
		$navbar[] = (object) ['name' => 'Pomoc', 'link' => 'Help:'];
		$navbar[] = (object) ['name' => 'Podpora systému'];

		$this->template->navbar = $navbar;

		$this->template->contact = $this->getSupportContact();
	}

	/**
	 *
	 * @return object
	 */
	private function getSupportContact() {
		return (object) [
					'name' => 'Miloš Havlíček',
					'tel' => '+420 606 460 316',
					'email' => 'miloshavlicek@gmail.com'
		];
	}

}
