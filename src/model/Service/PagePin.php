<?php

namespace Cothema\CMSBE\Service;

use App\ORM\Sys\Pinned;
use App\BEMenu;
use Doctrine\ORM\EntityManagerInterface;
use Nette;

/**
 * @author     Miloš Havlíček <miloshavlicek@gmail.com>
 */
class PagePin
{

    /** @var EntityManagerInterface */
    private $em;
    private $presenter;

    use Nette\SmartObject;

    /**
     *
     * @param object $presenter
     * @param EntityManagerInterface $em
     */
    public function __construct($presenter, EntityManagerInterface $em)
    {
        $this->presenter = $presenter;
        $this->em = $em;
    }

    /**
     *
     * @return array
     */
    public function pinIt()
    {
        $pinned = new Pinned($this, $this->em);
        $pinned->user = $this->presenter->user->id;
        $pinned->page = $this->presenter->getAction(true);

        $dao = $this->em->getRepository(BEMenu::class);
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
    public function unpinIt()
    {
        $pinnedDao = $this->em->getRepository(Pinned::class);
        $pinned = $pinnedDao->findBy(['user' => $this->presenter->user->id, 'page' => $this->presenter->getAction(true)]);

        foreach ($pinned as $pinnedOne) {
            $this->em->remove($pinnedOne);
        }

        $this->em->flush();
    }

    /**
     *
     * @return boolean
     */
    public function isPinned()
    {
        $dao = $this->em->getRepository(Pinned::class);
        $pinned = $dao->findBy(['user' => $this->presenter->user->id, 'page' => $this->presenter->getAction(true)]);

        return isset($pinned[0]) ? true : false;
    }

    /**
     *
     * @return boolean
     */
    public function isPinable()
    {
        $params = $this->presenter->request->getParameters();

        return ($this->presenter->getName() !== 'Homepage' && empty($params['id'])) ? true : false;
    }
}
