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

if(isset($debugIPs)) {
	$configurator->setDebugMode($debugIPs);
}
$configurator->enableDebugger(DIR_ROOT . '/log');

$configurator->setTempDirectory(DIR_ROOT . '/temp');

$configurator->createRobotLoader()
        ->addDirectory(DIR_VENDOR . '/cothema/')
        ->addDirectory(DIR_ROOT . '/vendor-manual/')
        ->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(DIR_ROOT . '/app/config/config.local.neon');

/* Doctrine */
Kdyby\Annotations\DI\AnnotationsExtension::register($configurator);
Kdyby\Console\DI\ConsoleExtension::register($configurator);
Kdyby\Events\DI\EventsExtension::register($configurator);
Kdyby\Doctrine\DI\OrmExtension::register($configurator);
/* end: Doctrine */
Kdyby\Translation\DI\TranslationExtension::register($configurator);

$container = $configurator->createContainer();

return $container;
