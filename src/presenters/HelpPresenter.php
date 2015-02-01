<?php

namespace App\Presenters;

use App;

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