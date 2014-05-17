<?php

namespace App\Presenters;

use Nette,
    App\Model;
use App;
use App\Cothema\Admin;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public function getGravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $em;

    public function beforeRender()
    {
        parent::beforeRender();

        if ($this->user->isLoggedIn()) {
            Admin\LogActivityRepository::logActivity($this->em, $this->user->id);
        }

        if (($this->getPresenter()->name == 'Sign' && $this->getAction() == 'in') || ($this->getPresenter()->name == 'AboutWebapp')) {

        } else {
            $this->permissions('admin');
        }

        if ($this->user->isLoggedIn()) {
            $actualUserDao = $this->em->getDao(App\User::getClassName());
            $actualUser = $actualUserDao->find($this->getUser()->id);

            $custDao = $this->em->getDao(App\Cothema\Admin\Custom::getClassName());
            $cust = $custDao->findAll();
            $customOut = [];
            foreach ($cust as $custOne) {
                $userCustDao = $this->em->getDao(App\Cothema\Admin\UserCustom::getClassName());
                $userCust = $userCustDao->findBy(['user' => $this->getUser()->id, 'custom' => $custOne->id]);

                if (isset($userCust[0])) {
                    $customOut[$custOne->alias] = $userCust[0]->custVal;
                } else {
                    $customOut[$custOne->alias] = $custOne->defVal;
                }
            }
            $this->template->custom = (object) $customOut;


            $this->template->actualUser = $actualUser;
        } else {
            $custDao = $this->em->getDao(App\Cothema\Admin\Custom::getClassName());
            $cust = $custDao->findAll();
            $customOut = [];
            foreach ($cust as $custOne) {

                $customOut[$custOne->alias] = $custOne->defVal;
            }
            $this->template->custom = (object) $customOut;

            $this->template->actualUser = null;
        }

        $webinfoDao = $this->em->getDao(App\Webinfo::getClassName());
        $webinfo = $webinfoDao->find(1);

        $this->template->companyName = $webinfo->webName;
        $this->template->companyFullName = $webinfo->company;
        $this->template->companyWebsite = $webinfo->website;

        if ($this->getUser()->id) {
            $user = $this->em->getDao(App\User::getClassName());
            $profileUser = $user->find($this->getUser()->id);

            $this->template->gravatar = $this->getGravatar($profileUser->email);
        }
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
