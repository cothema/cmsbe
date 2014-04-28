<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use Nette\DateTime;
use App\RestMenu;

/**
 * Homepage presenter.
 */
class MenuPresenter extends BasePresenter
{

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $em;

    public function renderDefault()
    {
        $menu = $this->em->getDao(RestMenu\RestMenu::getClassName());
        $intro = $menu->find(1)->getIntro();
        $this->template->menuIntro = $intro;

        $menuListDao = $this->em->getDao(RestMenu\DayMenu::getClassName());
        $menuDayList = $menuListDao->findAll();

        $this->template->menuDayList = $menuDayList;
    }

    protected function createComponentMenuAddForm()
    {
        $form = new Form;

        $form->addGroup('Základní informace:');
        $form->addDatePicker('date', 'Datum od:')
                ->setRequired('Prosím, vyberte datum.')
                ->setDefaultValue(new DateTime('now'))
                ->addRule(Form::RANGE, 'Zadané datum rezervace je z minulosti.', [new DateTime('now'), null]);

        $form->addDatePicker('dateto', 'Datum do (nepovinné):')
                ->addRule(Form::RANGE, 'Zadané datum rezervace je z minulosti.', [new DateTime('now'), null]);

        $form->addSelect('publicated', 'Zveřejněno:', ['Ano', 'Ne']);

        $form->addGroup('1. chod:');

        $form->addText('chod1', 'Typ:');

        $form->addText('opt11', 'Možnost 1:');

        $form->addGroup('2. chod:');

        $form->addText('chod2', 'Typ:');

        $form->addText('opt21', 'Možnost 1:');
        $form->addText('opt22', 'Možnost 2:');
        $form->addText('opt23', 'Možnost 3:');

        $form->addGroup('3. chod:');

        $form->addText('chod3', 'Typ:');

        $form->addText('opt31', 'Možnost 1:');

        $form->setCurrentGroup(null);

        $form->addSubmit('send', 'Přidat nové denní menu')->getControlPrototype()->class('btn btn-success');

        $form->onSuccess[] = $this->menuAddFormSucceeded;
        return $form;
    }

    protected function createComponentMenuIntroForm()
    {
        $form = new Form;

        $menu = $this->em->getDao(RestMenu\RestMenu::getClassName());
        $intro = $menu->find(1)->getIntro();

        $form
                ->addTextArea('intro', 'Popisek:')
                ->setDefaultValue($intro)
                ->getControlPrototype()
                ->class("textareafull form-control");

        $form->addSubmit('send', 'Upravit popisek denních menu')->getControlPrototype()->class('btn btn-success');

        $form->onSuccess[] = $this->menuIntroFormSucceeded;
        return $form;
    }

    public function menuAddFormSucceeded($form)
    {
        $values = $form->getValues(true);

        $this->flashMessage('Denní menu bylo úspěšně přidáno.');
        $this->redirect('Menu:');
    }

    public function menuIntroFormSucceeded($form)
    {
        $values = $form->getValues(true);

        $menu = $this->em->getDao(RestMenu\RestMenu::getClassName());
        $menuEntity = $menu->find(1);
        $menuEntity->setIntro($values['intro']);

        $this->em->persist($menuEntity);
        $this->em->flush();

        $this->flashMessage('Popisek denních menu byl úspěšně upraven.', 'success');
        $this->redirect('Menu:');
    }

}
