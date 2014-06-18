<?php

namespace App\Presenters;

use Nette,
    App\Model;
use App;
use App\Cothema\Admin;
use Pomeranc\Model as PModel;

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

    private function getActualNameday() {
        return $this->getNameday(date('j'),date('n'));
    }
    
    private function getNameday($day, $month) {
        $dao = $this->em->getDao(Admin\Nameday::getClassName());
        $nameday = $dao->findBy(['day' => (int)$day,'month' => (int)$month]);
        
        if(isset($nameday[0])) {
            return $nameday[0];
        }
        
        return null;
    }
    
    public function beforeRender()
    {
        parent::beforeRender();

        $this->template->actualDate = date('j. n. Y');
        
        $this->template->nameday = $this->getActualNameday();
        
        if ($this->user->isLoggedIn()) {
            Admin\LogActivityRepository::logActivity($this->em, $this->user->id);
        }

        if (($this->getPresenter()->name == 'Sign' && $this->getAction() == 'in') || ($this->getPresenter()->name == 'AboutWebapp')) {

        } else {
            $this->permissions('admin');
        }

        if ($this->user->isLoggedIn()) {
            $actualUserDao = $this->em->getDao(PModel\User\User::getClassName());
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
        $this->template->urlStats = $webinfo->urlStats;
        $this->template->webinfo = $webinfo;

        $this->template->otherWebsites = [];
        $otherWebsitesDao = $this->em->getDao(App\BEMenu::getClassName());
        $otherWebsites = $otherWebsitesDao->findBy(['parent' => null], ['orderLine' => 'ASC']);

        $this->template->menu = [];
        $beMenuDao = $this->em->getDao(App\BEMenu::getClassName());
        $beMenu = $beMenuDao->findBy(['parent' => null], ['orderLine' => 'ASC']);

        // TODO: recursive - be careful - cycle!
        foreach ($beMenu as $beMenuOne) {
            $menuHandle = [];
            $menuHandle['id'] = $beMenuOne->id;
            $menuHandle['nLink'] = $beMenuOne->nLink;
            $menuHandle['name'] = $beMenuOne->name;
            $menuHandle['orderLine'] = $beMenuOne->orderLine;
            $menuHandle['parent'] = $beMenuOne->parent;
            $menuHandle['module'] = $beMenuOne->module;

            // Find childs
            $beSubmenuDao = $this->em->getDao(App\BEMenu::getClassName());
            $beSubmenu = $beSubmenuDao->findBy(['parent' => $beMenuOne->id], ['orderLine' => 'ASC']);

            $menuHandle['childs'] = $beSubmenu;

            $this->template->menu[] = (object) $menuHandle;
        }

        $this->template->otherWebsites = [];
        $otherWebsitesDao = $this->em->getDao(App\OtherWebsite::getClassName());
        $otherWebsites = $otherWebsitesDao->findBy(['groupLine' => null], ['orderLine' => 'ASC']);

        $c = 0;
        foreach ($otherWebsites as $otherWebsitesOne) {
            $c++;

            if ($c == 1) {
                $handleOtherW = [];
                $handleOtherW['groupName'] = 'Nezařazené';
                $handleOtherW['items'] = [];
            }

            $handleOtherW['items'][] = $otherWebsitesOne;
        }

        if ($c > 0) {
            $this->template->otherWebsites[] = (object) $handleOtherW;
        }

        $otherWebsitesGroupDao = $this->em->getDao(App\OtherWebsiteGroup::getClassName());
        $otherWebsitesGroup = $otherWebsitesGroupDao->findBy([], ['orderLine' => 'ASC']);

        foreach ($otherWebsitesGroup as $otherWebsitesGroupOne) {
            $otherWebsitesBDao = $this->em->getDao(App\OtherWebsite::getClassName());
            $otherWebsitesB = $otherWebsitesBDao->findBy(['groupLine' => $otherWebsitesGroupOne->id], ['orderLine' => 'ASC']);

            $handleOtherWB = [];
            $handleOtherWB['groupName'] = $otherWebsitesGroupOne->name;

            $handleOtherWB['items'] = [];
            foreach ($otherWebsitesB as $otherWebsitesBOne) {
                $handleOtherWB['items'][] = $otherWebsitesBOne;
            }

            $this->template->otherWebsites[] = (object) $handleOtherWB;
        }

        $this->template->mainLayoutBeforePath = __DIR__ . '/../templates/@layout-before.latte';
        $this->template->mainLayoutAfterPath = __DIR__ . '/../templates/@layout-after.latte';

        if ($this->getUser()->id) {
            $user = $this->em->getDao(PModel\User\User::getClassName());
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
