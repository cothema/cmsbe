<?php

namespace Cothema\CMSBE\Service;

use App\ORM\Sys\Pinned;
use App\BEMenu;

/**
 * @author     Miloš Havlíček <miloshavlicek@gmail.com>
 */
class PagePin extends \Nette\Object {

	private $em;
	private $presenter;

	/**
	 *
	 * @param object $presenter
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(& $presenter, \Kdyby\Doctrine\EntityManager $em) {
		$this->presenter = $presenter;
		$this->em = $em;
	}

	/**
	 *
	 * @return array
	 */
	public function pinIt() {
		$pinned = new Pinned($this, $this->em);
		$pinned->user = $this->presenter->user->id;
		$pinned->page = $this->presenter->getAction(TRUE);

		$dao = $this->em->getRepository(BEMenu::getClassName());
		$menuItem = $dao->findBy(['nLink' => $this->presenter->getName() . ':' . $this->presenter->action]);

		$pinnedPageName = '';
		if (isset($menuItem[0])) {
			$pinnedPageName = $pinned->title = $menuItem[0]->name;
			$pinned->faIcon = $menuItem[0]->faIcon;
		}

		$this->em->persist($pinned);
		$this->em->flush();

		return ['title' => $pinnedPageName];
	}

	/**
	 * return void
	 */
	public function unpinIt() {
		$pinnedDao = $this->em->getRepository(Pinned::getClassName());
		$pinned = $pinnedDao->findBy(['user' => $this->presenter->user->id, 'page' => $this->presenter->getAction(TRUE)]);

		foreach ($pinned as $pinnedOne) {
			$this->em->remove($pinnedOne);
		}

		$this->em->flush();
	}

	/**
	 *
	 * @return boolean
	 */
	public function isPinned() {
		$dao = $this->em->getRepository(Pinned::getClassName());
		$pinned = $dao->findBy(['user' => $this->presenter->user->id, 'page' => $this->presenter->getAction(TRUE)]);

		return isset($pinned[0]) ? TRUE : FALSE;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isPinable() {
		$params = $this->presenter->request->getParameters();

		return ($this->presenter->getName() !== 'Homepage' && empty($params['id'])) ? TRUE : FALSE;
	}

}
