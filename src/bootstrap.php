<?php

use Nette\Forms\Container;
use Nextras\Forms\Controls;

Container::extensionMethod('addOptionList', function (Container $container, $name, $label = NULL, array $items = NULL) {
	return $container[$name] = new Controls\OptionList($label, $items);
});
Container::extensionMethod('addMultiOptionList', function (Container $container, $name, $label = NULL, array $items = NULL) {
	return $container[$name] = new Controls\MultiOptionList($label, $items);
});
Container::extensionMethod('addDatePicker', function (Container $container, $name, $label = NULL) {
	return $container[$name] = new Controls\DatePicker($label);
});
Container::extensionMethod('addDateTimePicker', function (Container $container, $name, $label = NULL) {
	return $container[$name] = new Controls\DateTimePicker($label);
});
Container::extensionMethod('addTypeahead', function(Container $container, $name, $label = NULL, $callback = NULL) {
	return $container[$name] = new Controls\Typeahead($label, $callback);
});

$configurator = new Nette\Configurator;

if (isset($debugIPs)) {
	$configurator->setDebugMode($debugIPs);
}
$configurator->enableDebugger(DIR_ROOT . '/log', isset($debugMail) ? $debugMail : NULL);

$configurator->setTempDirectory(DIR_ROOT . '/temp');

if (!isset($robotLoaderDirs)) {
	$robotLoaderDirs = [];
}
$robotLoaderDirs[] = DIR_VENDOR . '/cothema/';
$robotLoaderDirs[] = DIR_ROOT . '/vendor-manual/';

$robotLoader = $configurator->createRobotLoader();

foreach ($robotLoaderDirs as $robotLoaderDirsOne) {
	$robotLoader->addDirectory($robotLoaderDirsOne);
}

$robotLoader->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(DIR_ROOT . '/app/config/config.local.neon');

$container = $configurator->createContainer();

\Tracy\Debugger::getLogger()->emailSnooze = isset($debugMailSnooze) ? $debugMailSnooze : '30 minutes';

return $container;
