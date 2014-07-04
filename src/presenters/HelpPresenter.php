<?php

namespace App\Presenters;

use Nette,
    App\Model;
use App;
use Nette\Application\UI\Form;

/**
 * Help presenter.
 */
class HelpPresenter extends BasePresenter
{
    
    public function renderDefault() {
        
        $navbar = [];
        $navbar[] = (object)['name' => 'Pomoc'];
        
        $this->template->navbar = $navbar;
        $this->template->yourIp = $_SERVER['REMOTE_ADDR'];
    }
    
}