<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI\Form;
use Nette\DateTime;
use App;

/**
 * AboutWebapp presenter.
 */
class AboutWebappPresenter extends BasePresenter
{

    public function renderDefault()
    {
        $navbar = [];
        $navbar[] = (object)['name' => 'O aplikaci'];
        
        $this->template->navbar = $navbar;
    }

}
