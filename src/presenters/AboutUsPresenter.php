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
        if (!isset($this->params['id'])) {
            $this->redirect('AboutUs:');
        } else {
            $textDao = $this->em->getDao(App\AboutUs::getClassName());
            $text = $textDao->find($this->params['id']);
            if (!$text) {
                $this->flashMessage('Požadovaný text se nenachází v databázi.');
                $this->redirect('AboutUs:');
            }
        }
    }

    protected function createComponentAboutUsEditForm()
    {
        $form = new Form;

        if (isset($this->params['id']))
            $id = $this->params['id'];
        else
            $id = intval($this->request->post['id']);

        $textDao = $this->em->getDao(App\AboutUs::getClassName());
        $text = $textDao->find($id);

        $form
                ->addHidden('id')
                ->setValue($id);
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
        $menuEntity = $menu->find($values['id']);
        $menuEntity->setHeading($values['heading']);
        $menuEntity->setShortText($values['shortText']);

        $this->em->persist($menuEntity);
        $this->em->flush();

        $this->flashMessage('Text byl úspěšně uložen.', 'success');
        $this->redirect('AboutUs:');
    }

}
