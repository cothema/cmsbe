<?php

namespace App\Presenters;

use Nette;
use App\Model;
use App\ORM\Sys\Pinned;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    function renderDefault() {
        $this->template->pins = $this->getAllPins();
    }

    private function getAllPins() {
        $pinnedDao = $this->em->getDao(Pinned::getClassName());
        return $pinnedDao->findBy(['user' => $this->user->id]);
    }

}
