<?php

namespace App\Presenters;

use App\ORM\Sys\Pinned;

/**
 * @Secured
 * @Secured\User(loggedIn)
 *
 * Homepage Presenter
 */
final class HomepagePresenter extends BasePresenter {

	public function renderDefault() {
		$this->template->pins = $this->getAllPins();
	}

	private function getAllPins() {
		$pinnedDao = $this->em->getRepository(Pinned::getClassName());
		return $pinnedDao->findBy(['user' => $this->user->id]);
	}

}
