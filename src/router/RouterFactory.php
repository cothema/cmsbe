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
		$secured = in_array($_SERVER['REMOTE_ADDR'],['127.0.0.1','::1']) ? FALSE : Route::SECURED;
		
		$router = new RouteList();
		$router[] = new Route('<locale=cz cz|en|nl>/<presenter>/<action>[/<id>]', 'Homepage:default', SECURED ? $secured : FALSE);
		return $router;
	}

}
