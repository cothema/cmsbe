<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use Nette\DateTime;
use App;

/**
 * Users presenter.
 */
class UsersPresenter extends BasePresenter
{

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $em;

    public function renderList()
    {
        $usersDao = $this->em->getDao(App\User::getClassName());
        $users = $usersDao->findAll();

        $this->template->users = $users;
    }

}
