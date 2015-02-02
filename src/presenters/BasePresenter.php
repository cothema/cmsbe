<?php

namespace App\Presenters;

use Nette;
use App;
use App\Cothema\Admin;
use Cothema\Model as CModel;
use WebLoader;
use App\ORM\Sys\Pinned;
use App\BEMenu;
use Cothema\Model\User\User;
use Cothema\Model\User\Permissions;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/** @persistent */
	public $locale;

	/** @var \Kdyby\Translation\Translator @inject */
	public $translator;

	/** @var \Kdyby\Doctrine\EntityManager @inject */
	public $em;

	function __construct() {
		parent::__construct();
	}

	function handlePinIt() {

		$pinnedEntity = new Pinned;
		$pinnedEntity->user = $this->user->id;
		$pinnedEntity->page = $this->getAction(TRUE);

		$menuItemDao = $this->em->getDao(BEMenu::getClassName());
		$menuItem = $menuItemDao->findBy(['nLink' => $this->getName() . ':' . $this->action]);

		$pinnedPageName = '';
		if (isset($menuItem[0])) {
			$pinnedPageName = $pinnedEntity->title = $menuItem[0]->name;
			$pinnedEntity->faIcon = $menuItem[0]->faIcon;
		}

		$this->em->persist($pinnedEntity);

		try {
			$this->em->flush();

			$this->flashMessage('Stránka "' . $pinnedPageName . '" byla připnuta na Hlavní panel.', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Došlo k chybě.', 'danger');
		}

		$this->redirect('this');
	}

	function handleUnpinIt() {
		$pinnedDao = $this->em->getDao(Pinned::getClassName());
		$pinned = $pinnedDao->findBy(['user' => $this->user->id, 'page' => $this->getAction(TRUE)]);

		foreach ($pinned as $pinnedOne) {
			$this->em->remove($pinnedOne);
		}

		try {
			$this->em->flush();

			$this->flashMessage('Stránka byla odebrána z Hlavního panelu.', 'warning');
		} catch (\Exception $e) {
			$this->flashMessage('Došlo k chybě.', 'danger');
		}

		$this->redirect('this');
	}

	function isPinned() {
		$pinnedDao = $this->em->getDao(Pinned::getClassName());
		$pinned = $pinnedDao->findBy(['user' => $this->user->id, 'page' => $this->getAction(TRUE)]);

		return isset($pinned[0]) ? true : false;
	}

	function isPinable() {
		$result = false;

		$params = $this->request->getParameters();

		if ($this->getName() !== 'Homepage' && empty($params['id'])) {
			$result = true;
		}

		return $result;
	}

	protected function createTemplate($class = null) {
		$template = parent::createTemplate($class);
		$template->registerHelperLoader(callback($this->translator->createTemplateHelpers(), 'loader'));

		return $template;
	}

	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param integer $s Size in pixels, defaults to 80px [ 1 - 2048 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @param boole $img True to return a complete IMG tag False for just the URL
	 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
	 * @return String containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	function getGravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5(strtolower(trim($email)));
		$url .= "?s=$s&d=$d&r=$r";
		if ($img) {
			$url = '<img src="' . $url . '"';
			foreach ($atts as $key => $val) {
				$url .= ' ' . $key . '="' . $val . '"';
			}
			$url .= ' />';
		}
		return $url;
	}

	/**
	 * CSS stylesheet loading.
	 * @return WebLoader\Nette\CssLoader
	 */
	function createComponentCssScreen() {
		return $this->lessComponentWrapper(['screen.less'], 'screen,projection,tv');
	}

	/**
	 * CSS stylesheet loading.
	 * @return WebLoader\Nette\CssLoader
	 */
	function createComponentCssPrint() {
		return $this->lessComponentWrapper(['print.css'], 'print');
	}

	/**
	 * CSS stylesheet loading.
	 * @return WebLoader\Nette\CssLoader
	 */
	function createComponentCssAdminLTE() {
		return $this->lessComponentWrapper(['AdminLTE.css'], false, __DIR__ . '/../../../admin-lte/css');
	}

	/**
	 * JavaScript loading.
	 * @return WebLoader\Nette\JavaScriptLoader
	 */
	function createComponentJsJquery() {
		return $this->jsComponentWrapper(['jquery.js']);
	}

	/**
	 * JavaScript loading.
	 * @return WebLoader\Nette\JavaScriptLoader
	 */
	function createComponentJsMain() {
		return $this->jsComponentWrapper(['main.js']);
	}

	/**
	 * JavaScript loading.
	 * @return WebLoader\Nette\JavaScriptLoader
	 */
	function createComponentJsAdminLTE() {
		return $this->jsComponentWrapper(['app.js', '../plugins/iCheck/icheck.min.js'], __DIR__ . '/../../../admin-lte/js/AdminLTE');
	}

	/**
	 * JavaScript loading.
	 * @return WebLoader\Nette\JavaScriptLoader
	 */
	function createComponentJsNetteForms() {
		return $this->jsComponentWrapper(['netteForms.js']);
	}

	/**
	 * @param string $jsDir
	 */
	private function jsComponentWrapper(array $fileNames, $jsDir = null) {
		if ($jsDir === null) {
			$jsDir = __DIR__ . '/../scripts';
		}

		$outputDirName = '/tmp/js';

		$fileCollection = new WebLoader\FileCollection($jsDir);
		$fileCollection->addFiles($fileNames);

		$name = strtolower(substr($this->name, strrpos($this->name, ':') + 1)) . '.css';
		if (file_exists($jsDir . '/' . $name)) {
			$files->addFile($name);
		}

		$compiler = WebLoader\Compiler::createJsCompiler($fileCollection, $this->context->parameters['wwwDir'] . $outputDirName);

		$control = new WebLoader\Nette\JavaScriptLoader($compiler, $this->template->basePath . $outputDirName);

		return $control;
	}

	/**
	 * @param string|false $media
	 * @param string $stylesDir
	 */
	private function lessComponentWrapper(array $fileNames, $media = null, $stylesDir = null) {
		if ($media === null) {
			$media = 'screen,projection,tv';
		}

		if ($stylesDir === null) {
			$stylesDir = __DIR__ . '/../styles';
		}

		$outputDirName = '/tmp/css';

		$fileCollection = new WebLoader\FileCollection($stylesDir);
		$fileCollection->addFiles($fileNames);

		$name = strtolower(substr($this->name, strrpos($this->name, ':') + 1)) . '.css';
		if (file_exists($stylesDir . '/' . $name)) {
			$files->addFile($name);
		}

		$compiler = WebLoader\Compiler::createCssCompiler($fileCollection, $this->context->parameters['wwwDir'] . $outputDirName);

		$filter = new WebLoader\Filter\LessFilter;
		$compiler->addFileFilter($filter);

		$control = new WebLoader\Nette\CssLoader($compiler, $this->template->basePath . $outputDirName);

		if (is_string($media)) {
			$control->setMedia($media);
		}

		return $control;
	}

	private function getActualNameday() {
		return $this->getNameday(date('j'), date('n'));
	}

	/**
	 * @param string $day
	 * @param string $month
	 */
	private function getNameday($day, $month) {
		$dao = $this->em->getDao(Admin\Nameday::getClassName());
		$nameday = $dao->findBy(['day' => (int) $day, 'month' => (int) $month]);

		if (isset($nameday[0])) {
			return $nameday[0];
		}

		return null;
	}

	function beforeRender() {
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
			$actualUserDao = $this->em->getDao(CModel\User\User::getClassName());
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
		$this->template->isPinned = $this->isPinned();
		$this->template->isPinable = $this->isPinable();

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
			$menuHandle['faIcon'] = $beMenuOne->faIcon;

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
			$user = $this->em->getDao(CModel\User\User::getClassName());
			$profileUser = $user->find($this->getUser()->id);

			$this->template->gravatar = $this->getGravatar($profileUser->email);
		}
	}

	/*
	 * @param mixed $role if array = OR (one of)
	 */

	protected function permissions($role) {
		try {
			$ok = (is_array($role)) ? $this->permissionsRoleArray($role) : $this->permissionsRole($role);

			if (!$ok) {
				throw new \Exception('You do not have sufficient permissions.');
			}
		} catch (\Exception $e) {
			if (is_array($role)) {
				$this->flashMessage('Pro vstup do této sekce musíte být přihlášen/a s příslušným oprávněním (' . implode(' / ', $role) . ').');
			} else {
				$this->flashMessage('Pro vstup do této sekce musíte být přihlášen/a s příslušným oprávněním (' . $role . ').');
			}

			$this->redirect('Sign:in', ['backSignInUrl' => $this->getHttpRequest()->url->path]);
		}
	}

	private function permissionsRoleArray($role) {
		foreach ($role as $roleOne) {
			$ok = $this->permissionsRole($roleOne);

			if ($ok) {
				return true;
			}
		}

		return false;
	}

	private function permissionsRole($role) {
		return ($this->user->isInRole($role)) ? true : false;
	}

	protected function getPermissionsSection($section) {
		$userSignedDao = $this->em->getDao(User::getClassName());
		$userSigned = $userSignedDao->find($this->user->id);

		$permissionsDao = $this->em->getDao(Permissions::getClassName());
		$permissions = $permissionsDao->findBy(['user' => $userSigned, 'section' => $section]);

		if (isset($permissions[0])) {
			return $permissions[0];
		}

		return (object) ['section' => (string) $section, 'allowRead' => false, 'allowWrite' => false, 'allowDelete' => false];
	}

	protected function notYetImplemented() {
		$this->flashMessage('POZOR! Tato funkce ještě není zcela implementována!', 'danger');
	}

}
