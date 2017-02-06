<?php

namespace App\Presenters;

/**
 * @author     Milos Havlicek <miloshavlicek@gmail.com>
 *
 * AboutWebapp Presenter
 */
final class AboutWebappPresenter extends BasePresenter
{

    public function renderDefault()
    {
        $navbar   = [];
        $navbar[] = (object) ['name' => 'O aplikaci'];

        $this->template->navbar = $navbar;
    }
}