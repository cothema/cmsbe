<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App;
use App\Cothema\Admin;

/**
 * Custom presenter.
 */
class CustomPresenter extends BasePresenter {

	public function renderDefault() {
		$navbar = [];
		$navbar[] = (object) ['name' => 'Nastavení', 'link' => 'Settings:BasicInfo'];
		$navbar[] = (object) ['name' => 'Přizpůsobení'];

		$this->template->navbar = $navbar;
	}

	protected function createComponentCustomForm() {
		$customsDao = $this->em->getDao(Admin\Custom::getClassName());
		$customs = $customsDao->findBy([], ['id' => 'ASC']);

		$form = new Nette\Application\UI\Form;

		foreach ($customs as $customsOne) {
			$custUserDao = $this->em->getDao(Admin\UserCustom::getClassName());
			$custUser = $custUserDao->findBy(['custom' => $customsOne->id, 'user' => $this->getUser()->id]);

			if ($customsOne->type == 'yn') {
				$formIt = $form->addSelect('CUV' . $customsOne->alias, $customsOne->name . ':', [1 => 'Ano', 0 => 'Ne']);
			} else {
				$formIt->addText('CUV' . $customsOne->alias, $customsOne->name . ':');
			}

			if (isset($custUser[0]->custVal)) {
				$formIt->setDefaultValue($custUser[0]->custVal);
			} else {
				$formIt->setDefaultValue($customsOne->defVal);
			}

			$formIt->getControlPrototype()->class('form-control');
		}

		$form->addSubmit('send', 'Uložit')
				->getControlPrototype()->class('btn btn-success');

		$form->onSuccess[] = $this->customFormSucceeded;
		return $form;
	}

	public function customFormSucceeded($form) {
		$values = $form->getValues(true);

		foreach ($values as $valuesKey => $valuesOne) {

			$prefix = 'CUV';

			if ($this->startsWith($valuesKey, $prefix)) {
				$customsOneDao = $this->em->getDao(Admin\Custom::getClassName());
				$customsOne = $customsOneDao->findBy(['alias' => substr($valuesKey, strlen($prefix))]);

				if (!isset($customsOne[0])) {
					throw new \Exception('ID of user customization is not set!');
				} else {
					$customsUserOneDao = $this->em->getDao(Admin\UserCustom::getClassName());
					$customsUserOne = $customsUserOneDao->findBy(['user' => $this->getUser()->id, 'custom' => $customsOne[0]->id]);

					if ($customsOne[0]->defVal == $valuesOne) {
						if (isset($customsUserOne[0])) {
							$this->em->remove($customsUserOne[0]);
						}
					} elseif (!isset($customsUserOne[0])) {
						$custNew = new Admin\UserCustom;
						$custNew->custVal = $valuesOne;
						$custNew->custom = $customsOne[0]->id;
						$custNew->user = $this->getUser()->id;

						$this->em->persist($custNew);
					} elseif ($customsUserOne[0]->custVal != $valuesOne) {
						$customsUserOne[0]->custVal = $valuesOne;

						$this->em->persist($customsUserOne[0]);
					} else {
						// everything is OK (form val == user defined val)
					}
				}
			}
		}

		$this->em->flush();

		$this->flashMessage('Uživatelské nastavení bylo úspěšně uloženo.', 'success');
		$this->redirect('this');
	}

	public function startsWith($haystack, $needle) {
		return $needle === "" || strpos($haystack, $needle) === 0;
	}

	public function endsWith($haystack, $needle) {
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}

}
