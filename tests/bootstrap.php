<?php

require __DIR__ . '/../vendor/autoload.php';

if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

function id($val) {
	return $val;
}

$configurator = new Nette\Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/../src/temp');
$configurator->createRobotLoader()
		->addDirectory(__DIR__ . '/../src/model')
		->register();

//$configurator->addConfig(__DIR__ . '/../config/config.neon');
//$configurator->addConfig(__DIR__ . '/../app/config/config.local.neon');
return $configurator->createContainer();
