<?php

namespace App\Presenters;

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
