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

	function testVerify() {
		$password = array('testPasswords123', 'wefdERQ9_s(7žš+.f-s');

		foreach ($password as $passwordOne) {
			Assert::true(Passwords::verify($passwordOne, Passwords::hash($passwordOne)));
		}
	}

	function testNeedsRehash() {
		$password = 'wefdERQ9_s(7žš+.f-s';

		Assert::false(Passwords::needsRehash(Passwords::hash($password)));
	}

}

id(new TestPasswords($container))->run();
