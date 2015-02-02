<?php

namespace Test\App;

use Nette,
	Tester,
	Tester\Assert;

$container = require_once __DIR__ . '/../../bootstrap.php';

class TestInitialize extends Tester\TestCase {

	private $container;

	function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}

	function setUp() {

	}

	function getInitializeAllArgs() {
		return [
			['\App\BEMenu'],
			['\App\OtherWebsite'],
			['\App\OtherWebsiteGroup'],
			['\App\Webinfo'],
			['\App\Cothema\Admin\Custom'],
			['\App\Cothema\Admin\LogActivity'],
			['\App\Cothema\Admin\Nameday'],
			['\App\Cothema\Admin\UserCustom'],
			['\App\ORM\Sys\Pinned']
		];
	}

	/**
	 * @dataProvider getInitializeAllArgs
	 */
	function testInitializeAll($class) {
		Assert::type($class, new $class);
	}

}

id(new TestInitialize($container))->run();
