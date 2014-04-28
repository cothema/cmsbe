<?php

namespace App\Presenters;

use Nette,
    App\Model;
use App;
use Nette\Application\UI\Form;

/**
 * Settings presenter.
 */
class SettingsPresenter extends BasePresenter
{

    public function renderBasicInfo()
    {
        $this->permissions('superadmin');
    }

    public function createComponentBasicSettingsForm()
    {
        $form = new Form;

        $dao = $this->em->getDao(App\Webinfo::getClassName());
        $res = $dao->find(1);

        $form->addText('webName', 'Název')
                ->setDefaultValue($res->webName)
                ->getControlPrototype()
                ->class("form-control");
        $form->addText('website', 'URL webu')
                ->setDefaultValue($res->website)
                ->getControlPrototype()
                ->class("form-control");
        $form->addText('webAdmin', 'URL administrace')
                ->setDefaultValue($res->webAdmin)
                ->getControlPrototype()
                ->class("form-control");
        $form->addSubmit('send', 'Uložit')->getControlPrototype()->class('btn btn-success');

        return $form;
    }

}
