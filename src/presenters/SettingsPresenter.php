<?php

namespace App\Presenters;

use Nette,
    App\Model;
use App;

/**
 * Settings presenter.
 */
class SettingsPresenter extends BasePresenter
{

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $em;

    public function renderBasicInfo()
    {
        $this->permissions('superadmin');
    }

}
