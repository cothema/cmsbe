<?php

namespace App\Presenters;

use Nette,
    App\Model;
use App;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $em;

    public function beforeRender()
    {
        parent::beforeRender();

        if (($this->getView() != 'Sign' && $this->getAction() != 'in')) {
            $this->permissions('admin');
        }

        if ($this->getUser()->isLoggedIn()) {
            $actualUserDao = $this->em->getDao(App\User::getClassName());
            $actualUser = $actualUserDao->find($this->getUser()->id);

            $this->template->actualUser = $actualUser;
        } else {
            $this->template->actualUser = null;
        }

        $this->template->companyName = 'Buena Vista';
        $this->template->companyWebsite = 'http://www.buena-vista.cz';
    }

    /*
     * @param mixed $role if array = OR (one of)
     */

    protected function permissions($role)
    {
        try {
            if (is_array($role)) {
                $ok = false;
                foreach ($role as $roleOne) {
                    $ok = ($this->user->isInRole($roleOne)) ? true : false;

                    if ($ok) {
                        return;
                    }
                }
            } elseif ($this->user->isInRole($role)) {
                return;
            }

            throw new \Exception('You do not have sufficient permissions.');
        } catch (\Exception $e) {

            if (is_array($role)) {
                $this->flashMessage('Pro vstup do této sekce musíte být přihlášen/a s příslušným oprávněním (' . implode(' / ', $role) . ').');
            } else {
                $this->flashMessage('Pro vstup do této sekce musíte být přihlášen/a s příslušným oprávněním (' . $role . ').');
            }

            $this->redirect('Sign:in');
        }
    }

}
