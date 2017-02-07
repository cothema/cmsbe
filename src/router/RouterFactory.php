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
        $sslForce     = (SECURED && !LOCALHOST && !DEV_MODE);
        $scheme       = $sslForce ? 'https://' : '//';
        $urlFirstPart = $scheme.'%host%/%basePath%/';

        $router   = new RouteList();
        $router[] = new Route($urlFirstPart.'<locale=cz>/<presenter>/<action>[/<id>]',
            'Homepage:default');
        return $router;
    }
}