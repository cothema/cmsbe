<?php

namespace App;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;

/**
 * Router factory.
 */
class RouterFactory
{

    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter()
    {
        !defined('SECURED') && define('SECURED', FALSE);
        $sslForce = (SECURED && !LOCALHOST && !DEV_MODE);
        $mask     = $sslForce ? 'https://' : '//';

        $router   = new RouteList();
        $router[] = new Route($mask.'<locale=cz cz|en|nl>/<presenter>/<action>[/<id>]',
            'Homepage:default');
        return $router;
    }
}