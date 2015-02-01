<?php

namespace Test\App;

use Nette,
	Tester,
	Tester\Assert,
	App\Passwords;

$container = require_once __DIR__ . '/../bootstrap.php';

class TestPasswords extends Tester\TestCase {

	private $container;

	function __construct(Nette\DI\Container $container) {
		$this->container = $container;
	}

	function setUp() {

	}

	function testHash() {
		Assert::true(is_string(Passwords::hash('testPasswords123')));
	}

	/**
	 * @throws Nette\InvalidArgumentException
	 */
	function testHashException() {
		Passwords::hash('testPassword123', array('cost' => 1));
	}

	function getVerifyArgs() {
		return array(
			array('testPasswords123'),
			array('wefdERQ9_s(7žš+.f-s')
		);
	}

	/**
	 * @dataProvider getVerifyArgs
	 */
	function testVerify($password) {
		Assert::true(Passwords::verify($password[0], Passwords::hash($password[0])));
	}

	function testNeedsRehash() {
		$password = 'wefdERQ9_s(7žš+.f-s';

		Assert::false(Passwords::needsRehash(Passwords::hash($password)));
	}

}

id(new TestPasswords($container))->run();
