<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use Nette\DateTime;
use App;

/**
 * Homepage presenter.
 */
class AboutUsPresenter extends BasePresenter
{

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $em;

    public function renderDefault()
    {
        $articlesDao = $this->em->getDao(App\AboutUs::getClassName());

        $loadArticles = $articlesDao->findAll();
        foreach ($loadArticles as $loadArticlesOne) {

            $mapped = [];
            $mapped['heading'] = $loadArticlesOne->getHeading();
            $mapped['shortText'] = $loadArticlesOne->getShortText();
            $mapped['id'] = $loadArticlesOne->getId();

            $articles[] = (object) $mapped;
        }

        $this->template->articles = $articles;
    }

    public function renderEdit()
    {
        if (!isset($this->params['editId'])) {
            $this->redirect('AboutUs:');
        } else {
            $textDao = $this->em->getDao(App\AboutUs::getClassName());
            $text = $textDao->find($this->params['editId']);
            if (!$text) {
                $this->flashMessage('Požadovaný text se nenachází v databázi.');
                $this->redirect('AboutUs:');
            }
        }
    }

    protected function createComponentAboutUsEditForm()
    {
        $form = new Form;

        $textDao = $this->em->getDao(App\AboutUs::getClassName());
        $text = $textDao->find($this->params['editId']);

        $form
                ->addText('heading', 'Nadpis:')
                ->setDefaultValue($text->heading)
                ->getControlPrototype()
                ->class('form-control');

        $form
                ->addTextArea('shortText', 'Text:')
                ->setDefaultValue($text->shortText)
                ->getControlPrototype()
                ->class("textareafull form-control");

        $form->addSubmit('send', 'Uložit změny')->getControlPrototype()->class('btn btn-success');

        $form->onSuccess[] = $this->aboutUsEditFormSucceeded;
        return $form;
    }

    public function aboutUsEditFormSucceeded($form)
    {
        $values = $form->getValues(true);

        $menu = $this->em->getDao(App\AboutUs::getClassName());
        $menuEntity = $menu->find(1);
        $menuEntity->setIntro($values['intro']);

        $this->em->persist($menuEntity);
        $this->em->flush();

        $this->flashMessage('Popisek denních menu byl úspěšně upraven.');
        $this->redirect('Menu:');
    }

}
