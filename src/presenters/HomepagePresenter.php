<?php

namespace App\Presenters;

use App\ORM\Sys\Pinned;

/**
 * @Secured
 * @Secured\User(loggedIn)
 * @Secured\Role(admin)
 * @author     Milos Havlicek <miloshavlicek@gmail.com>
 * 
 * Homepage Presenter
 */
final class HomepagePresenter extends BasePresenter {

	public function renderDefault() {
		$this->template->pins = $this->getAllPins();
	}

	private function getAllPins() {
		$pinnedDao = $this->em->getRepository(Pinned::class);
		return $pinnedDao->findBy(['user' => $this->user->id]);
	}

}
