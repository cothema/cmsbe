<?php

namespace App;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

/**
 * Router factory.
 */
class RouterFactory {

    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter() {
        !defined('SECURED') && define('SECURED', FALSE);
        $sslForce = (SECURED && !LOCALHOST && !DEV_MODE) ? Route::SECURED : FALSE;

        $router = new RouteList();
        $router[] = new Route('<locale=cz cz|en|nl>/<presenter>/<action>[/<id>]', 'Homepage:default', $sslForce);
        return $router;
    }

}
