<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use Nette\DateTime;
use App\RestMenu;

/**
 * RestMenu presenter.
 */
class RestMenuPresenter extends BasePresenter
{

    public function renderDefault()
    {
        $menu = $this->em->getDao(RestMenu\RestMenu::getClassName());
        $intro = $menu->find(1)->getIntro();
        $this->template->menuIntro = $intro;

        $menuListDao = $this->em->getDao(RestMenu\MenuDay::getClassName());
        $menuDayList = $menuListDao->findBy([], ['dateFrom' => 'DESC']);

        $this->template->menuDayList = $menuDayList;
        $this->template->getFormattedMenu = function($fId) {
            return RestMenu\MenuDayRepository::getFormattedMenu($fId, $this->em);
        };
    }

    protected function createComponentMenuEditForm()
    {
        $menuPartsDao = $this->em->getDao(RestMenu\MenuPart::getClassName());
        $menuParts = $menuPartsDao->findBy([], ['lineOrder' => 'ASC']);

        if (isset($this->params['id']))
            $id = $this->params['id'];
        else {
            $id = intval($this->request->post['menuId']);
        }
        $menuDbDao = $this->em->getDao(RestMenu\MenuDay::getClassName());
        $menuDb = $menuDbDao->find($id);

        $menuPartsMapped = [];
        foreach ($menuParts as $menuPartsOne) {
            $menuPartsMapped[$menuPartsOne->id] = $menuPartsOne->name;
        }

        $form = new Form;
        $form->addGroup('Základní informace:');

        $form->addHidden('menurest', 1);

        $form->addHidden('menuId', $id);

        $form->addText('menuIdx', 'ID')
                ->setDisabled()
                ->setDefaultValue($id)
                ->getControlPrototype()
                ->class("form-control");

        $form->addDatePicker('datefrom', 'Datum od:')
                ->setRequired('Prosím, vyberte "datum od".')
                ->setDefaultValue($menuDb->dateFrom)
                ->getControlPrototype()
                ->class("form-control");

        $form->addDatePicker('dateto', 'Datum do:')
                ->setRequired('Prosím, vyberte "datum do".')
                ->setDefaultValue($menuDb->dateTo)
                ->addRule(Form::RANGE, 'Zadané "datum do" je menší než "datum od".', [$form['datefrom'], null])
                ->getControlPrototype()
                ->class("form-control");

        $form->addSelect('publicated', 'Zveřejněno:', [1 => 'Ano', 0 => 'Ne'])
                ->setDefaultValue($menuDb->public)
                ->getControlPrototype()
                ->class("form-control");

        $menuPartsDbDao = $this->em->getDao(RestMenu\MenuPart::getClassName());
        $menuPartsDb = $menuPartsDbDao->findBy([], ['lineOrder' => 'ASC']);

        $i = 1;
        foreach ($menuPartsDb as $menuPartsDbOne) {
            $form->addHidden('partX' . $i, $menuPartsDbOne->id);
            $this->menuEditFormPart($form, $id, $menuPartsDbOne->id, $menuPartsDbOne->name, $i, 2);
            $form->setCurrentGroup(null);
            $i++;
        }

        $form->addSubmit('send', 'Uložit denní menu')->getControlPrototype()->class('btn btn-success');

        $form->onSuccess[] = $this->menuEditFormSucceeded;
        return $form;
    }

    public function menuEditFormSucceeded($form)
    {
        $val = $form->getValues(true);

        $menuDayDbDao = $this->em->getDao(RestMenu\MenuDay::getClassName());
        $menuDay = $menuDayDbDao->find($val['menuId']);

        if (!$menuDay) {
            throw new \Exception('Menu ID not found.');
        }

        $menuDay->dateFrom = $val['datefrom'];
        $menuDay->dateTo = $val['dateto'];
        $menuDay->public = $val['publicated'];

        $menuRestDao = $this->em->getDao(RestMenu\RestMenu::getClassName());
        $menuRest = $menuRestDao->find($val['menurest']);
        $menuDay->restMenu = $menuRest;

        $this->em->persist($menuDay);

        $menuFoodsDelDbDao = $this->em->getDao(RestMenu\MenuFood::getClassName());
        $menuFoodsDelDb = $menuFoodsDelDbDao->findBy(['menuDay' => $menuDay->id]);

        foreach ($menuFoodsDelDb as $menuFoodsDelDbOne) {
            $this->em->remove($menuFoodsDelDbOne);
        }

        for ($i = 1; isset($val['partX' . $i]); $i++) {
            $menuPartDao = $this->em->getDao(RestMenu\MenuPart::getClassName());
            $menuPart = $menuPartDao->find($val['partX' . $i]);

            for ($j = 1; isset($val['optX' . $i . 'X' . $j]); $j++) {
                if (trim($val['optX' . $i . 'X' . $j]) != '') {
                    $menuFood = new RestMenu\MenuFood;
                    $menuFood->name = $val['optX' . $i . 'X' . $j];
                    $menuFood->lineOrder = $j;
                    $menuFood->menuDay = $menuDay;
                    $menuFood->menuPart = $menuPart;
                    $this->em->persist($menuFood);
                }
            }
        }

        $this->em->flush();

        $this->flashMessage('Denní menu bylo úspěšně uloženo.', 'success');
        $this->redirect('RestMenu:');
    }

    protected function createComponentMenuAddForm()
    {
        $menuPartsDao = $this->em->getDao(RestMenu\MenuPart::getClassName());
        $menuParts = $menuPartsDao->findBy([], ['lineOrder' => 'ASC']);

        $menuPartsMapped = [];
        foreach ($menuParts as $menuPartsOne) {
            $menuPartsMapped[$menuPartsOne->id] = $menuPartsOne->name;
        }

        $form = new Form;
        $form->addGroup('Základní informace:');

        $form->addHidden('menurest', 1);

        $form->addDatePicker('datefrom', 'Datum od:')
                ->setRequired('Prosím, vyberte "datum od".')
                ->setDefaultValue(new DateTime('now'))
                ->getControlPrototype()
                ->class("form-control");

        $form->addDatePicker('dateto', 'Datum do:')
                ->setRequired('Prosím, vyberte "datum do".')
                ->setDefaultValue(new DateTime('now'))
                ->addRule(Form::RANGE, 'Zadané "datum do" je menší než "datum od".', [$form['datefrom'], null])
                ->getControlPrototype()
                ->class("form-control");

        $form->addSelect('publicated', 'Zveřejněno:', [1 => 'Ano', 0 => 'Ne'])
                ->getControlPrototype()
                ->class("form-control");

        $this->menuAddFormPart($form, '1. chod', '1', [$menuPartsMapped, 2], 2);

        $this->menuAddFormPart($form, '2. chod', '2', [$menuPartsMapped, 3], 4);

        $this->menuAddFormPart($form, '3. chod', '3', [$menuPartsMapped, 4], 2);

        $form->setCurrentGroup(null);

        $form->addSubmit('send', 'Přidat nové denní menu')->getControlPrototype()->class('btn btn-success');

        $form->onSuccess[] = $this->menuAddFormSucceeded;
        return $form;
    }

    private function menuAddFormPart($form, $name, $order, array $parts, $options)
    {
        $form->addGroup($name . ':');

        $partElem = $form->addSelect('partX' . $order, 'Typ:', $parts[0]);

        if (isset($parts[1])) {
            $partElem->setDefaultValue($parts[1]);
        }

        $partElem->getControlPrototype()
                ->class("mediumwidth form-control");

        for ($i = 1; $i <= $options; $i++) {
            $form->addText('optX' . $order . 'X' . $i, 'Možnost ' . $i . ':')->getControlPrototype()
                    ->class("morewidth form-control");
        }

        return $form;
    }

    private function menuEditFormPart($form, $menuDayId, $partId, $name, $order, $optionsExtra)
    {
        $foodDbDao = $this->em->getDao(RestMenu\MenuFood::getClassName());
        $foodDb = $foodDbDao->findBy(['menuDay' => $menuDayId, 'menuPart' => $partId], ['lineOrder' => 'ASC']);

        $form->addGroup($name . ':');

        $i = 1;
        foreach ($foodDb as $foodDbOne) {
            $form->addText('optX' . $order . 'X' . $i, 'Možnost ' . $i . ':')
                    ->setDefaultValue($foodDbOne->name)
                    ->getControlPrototype()
                    ->class("morewidth form-control");

            $i++;
        }

        $options = $i + $optionsExtra - 1;

        for (; $i <= $options; $i++) {
            $form->addText('optX' . $order . 'X' . $i, 'Možnost ' . $i . ':')
                    ->getControlPrototype()
                    ->class("morewidth form-control");
        }

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
        $val = $form->getValues(true);
        $menuDay = new RestMenu\MenuDay;
        $menuDay->dateFrom = $val['datefrom'];
        $menuDay->dateTo = $val['dateto'];
        $menuDay->public = $val['publicated'];

        $menuRestDao = $this->em->getDao(RestMenu\RestMenu::getClassName());
        $menuRest = $menuRestDao->find($val['menurest']);
        $menuDay->restMenu = $menuRest;

        $this->em->persist($menuDay);

        for ($i = 1; isset($val['partX' . $i]); $i++) {
            $menuPartDao = $this->em->getDao(RestMenu\MenuPart::getClassName());
            $menuPart = $menuPartDao->find($val['partX' . $i]);

            for ($j = 1; isset($val['optX' . $i . 'X' . $j]); $j++) {
                if (trim($val['optX' . $i . 'X' . $j]) != '') {
                    $menuFood = new RestMenu\MenuFood;
                    $menuFood->name = $val['optX' . $i . 'X' . $j];
                    $menuFood->lineOrder = $j;
                    $menuFood->menuDay = $menuDay;
                    $menuFood->menuPart = $menuPart;
                    $this->em->persist($menuFood);
                }
            }
        }

        $this->em->flush();

        $this->flashMessage('Denní menu bylo úspěšně přidáno.', 'success');
        $this->redirect('RestMenu:');
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

    public function handleUnPublic($id)
    {
        $menuDayDao = $this->em->getDao(RestMenu\MenuDay::getClassName());
        $menuDay = $menuDayDao->find($id);
        $menuDay->public = 0;

        $this->em->persist($menuDay);
        $this->em->flush();

        $this->flashMessage('Denní menu bylo úspěšně zneveřejněno.', 'success');
    }

    public function handlePublic($id)
    {
        $menuDayDao = $this->em->getDao(RestMenu\MenuDay::getClassName());
        $menuDay = $menuDayDao->find($id);
        $menuDay->public = 1;

        $this->em->persist($menuDay);
        $this->em->flush();

        $this->flashMessage('Denní menu bylo úspěšně zveřejněno.', 'success');
    }

    public function handleDelete($id)
    {
        $menuDayDao = $this->em->getDao(RestMenu\MenuDay::getClassName());
        $menuDay = $menuDayDao->find($id);

        $this->em->remove($menuDay);
        $this->em->flush();

        $this->flashMessage('Denní menu bylo úspěšně smazáno.', 'success');
    }

}
