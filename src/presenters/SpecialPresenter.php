<?php

namespace App\Presenters;

use Nette,
    App\Model;
use App;

/**
 * Homepage presenter.
 */
class SpecialPresenter extends BasePresenter
{

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $em;

    public function renderDefault()
    {
        $offersDao = $this->em->getDao(App\SpecialOffer::getClassName());
        $offers = $offersDao->findAll();

        $this->template->offers = $offers;
    }

    public function handleEnable($offerId)
    {
        $offersDao = $this->em->getDao(App\SpecialOffer::getClassName());
        $offer = $offersDao->find($offerId);
        $offer->setPublicable(true);

        $this->em->persist($offer);
        $this->em->flush();

        $this->flashMessage('U speciální nabídky č. ' . $offerId . ' bylo úspěšně povoleno zveřejnění.', 'success');
        $this->redirect('this');
    }

    public function handleDisable($offerId)
    {
        $offersDao = $this->em->getDao(App\SpecialOffer::getClassName());
        $offer = $offersDao->find($offerId);
        $offer->setPublicable(false);

        $this->em->persist($offer);
        $this->em->flush();

        $this->flashMessage('U speciální nabídky č. ' . $offerId . ' bylo úspěšně zakázáno zveřejnění.', 'success');
        $this->redirect('this');
    }

}
