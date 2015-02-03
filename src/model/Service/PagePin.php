<?php

namespace Cothema\CMSBE\Service;

use App\ORM\Sys\Pinned;

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
	function __construct(& $presenter, \Kdyby\Doctrine\EntityManager $em) {
		$this->presenter = $presenter;
		$this->em = $em;
	}

	/**
	 *
	 * @return array
	 */
	function pinIt() {
		$pinned = new Pinned;
		$pinned->user = $this->presenter->user->id;
		$pinned->page = $this->presenter->getAction(TRUE);

		$dao = $this->em->getRepository(BEMenu::getClassName());
		$menuItem = $dao->findBy(['nLink' => $this->getName() . ':' . $this->action]);

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
	function handleUnpinIt() {
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
	function isPinned() {
		$dao = $this->em->getRepository(Pinned::getClassName());
		$pinned = $dao->findBy(['user' => $this->presenter->user->id, 'page' => $this->presenter->getAction(TRUE)]);

		return isset($pinned[0]) ? TRUE : FALSE;
	}

	/**
	 *
	 * @return boolean
	 */
	function isPinable() {
		$params = $this->presenter->request->getParameters();

		return ($this->presenter->getName() !== 'Homepage' && empty($params['id'])) ? TRUE : FALSE;
	}

}
