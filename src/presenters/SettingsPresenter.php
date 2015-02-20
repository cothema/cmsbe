<?php

namespace App\Presenters;

use App;
use Nette\Application\UI\Form;

/**
 * Settings Presenter
 */
class SettingsPresenter extends BasePresenter {

	public function renderBasicInfo() {
		$this->permissions('superadmin');

		$navbar = [];
		$navbar[] = (object) ['name' => 'Nastavení'];
		$navbar[] = (object) ['name' => 'Základní informace'];

		$this->template->navbar = $navbar;
	}

	public function createComponentBasicSettingsForm() {
		$form = new Form;

		$dao = $this->em->getDao(App\Webinfo::getClassName());
		$res = $dao->find(1);

		$form->addText('webName', 'Název')
				->setDefaultValue($res->webName)
				->getControlPrototype()
				->class("form-control mediumwidth");
		$form->addText('company', 'Název společnosti')
				->setDefaultValue($res->company)
				->getControlPrototype()
				->class("form-control mediumwidth");
		$form->addSelect('systype', 'Druh aplikace', ['webapp' => 'Webové stránky', 'is' => 'Informační systém'])
				->setDefaultValue($res->systype)
				->getControlPrototype()
				->class("form-control mediumwidth");
		$form->addText('website', 'URL webu')
				->setDefaultValue($res->website)
				->getControlPrototype()
				->class("form-control morewidth");
		$form->addText('webAdmin', 'URL administrace')
				->setDefaultValue($res->webAdmin)
				->getControlPrototype()
				->class("form-control morewidth");
		$form->addText('urlStats', 'URL statistik návštěvnosti')
				->setDefaultValue($res->urlStats)
				->getControlPrototype()
				->class("form-control morewidth");
		$form->addSubmit('send', 'Uložit')->getControlPrototype()->class('btn btn-success');

		$form->onSuccess[] = $this->basicSettingsFormSucceeded;

		return $form;
	}

	public function basicSettingsFormSucceeded($form) {
		$val = $form->getValues(true);

		$settingsDao = $this->em->getDao(\App\Webinfo::getClassName());
		$settings = $settingsDao->find(1);

		$settings->webName = trim($val['webName']);
		$settings->company = trim($val['company']);
		$settings->website = trim($val['website']);
		$settings->webAdmin = trim($val['webAdmin']);
		$settings->systype = trim($val['systype']);

		if (trim($val['urlStats']) === '') {
			$settings->urlStats = null;
		} else {
			$settings->urlStats = trim($val['urlStats']);
		}

		$this->em->persist($settings);
		$this->em->flush();

		$this->flashMessage('Základní informace byly úspěšně uloženy.', 'success');
		$this->redirect('this');
	}

}
