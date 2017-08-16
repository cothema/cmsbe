<?php

use Nette\Forms\Container;
use Nextras\Forms\Controls;

if (!defined('DIR_ROOT')) {
    throw new \Exception('DIR_ROOT constant is not defined!');
}
!defined('DIR_APP') && define('DIR_APP', DIR_ROOT . '/app');
!defined('DIR_WWW') && define('DIR_WWW', DIR_ROOT . '/www');
!defined('DIR_CONFIG') && define('DIR_CONFIG', DIR_APP . '/config');
!defined('DIR_TEMP') && define('DIR_TEMP', DIR_ROOT . '/temp');
!defined('DIR_LOG') && define('DIR_LOG', DIR_ROOT . '/log');
!defined('LOCALHOST') && define('LOCALHOST', (isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1'])) ? false : true);
!defined('DEV_MODE') && define('DEV_MODE', is_file(DIR_CONFIG . '/DEV_MODE'));

// CURL constants
!defined('CURLOPT_CONNECTTIMEOUT') && define('CURLOPT_CONNECTTIMEOUT', '300');
!defined('CURLOPT_TIMEOUT') && define('CURLOPT_TIMEOUT', '0');
!defined('CURLOPT_HTTPHEADER') && define('CURLOPT_HTTPHEADER', null);
!defined('CURLINFO_HEADER_OUT') && define('CURLINFO_HEADER_OUT', null);
!defined('CURLOPT_HEADER') && define('CURLOPT_HEADER', '0');
!defined('CURLOPT_RETURNTRANSFER') && define('CURLOPT_RETURNTRANSFER', '1');

Container::extensionMethod('addOptionList', function (Container $container, $name, $label = null, array $items = null) {
    return $container[$name] = new Controls\OptionList($label, $items);
});
Container::extensionMethod('addMultiOptionList', function (Container $container, $name, $label = null, array $items = null) {
    return $container[$name] = new Controls\MultiOptionList($label, $items);
});
Container::extensionMethod('addDatePicker', function (Container $container, $name, $label = null) {
    return $container[$name] = new Controls\DatePicker($label);
});
Container::extensionMethod('addDateTimePicker', function (Container $container, $name, $label = null) {
    return $container[$name] = new Controls\DateTimePicker($label);
});
Container::extensionMethod('addTypeahead', function (Container $container, $name, $label = null, $callback = null) {
    return $container[$name] = new Controls\Typeahead($label, $callback);
});

$configurator = new Nette\Configurator;

if (DEV_MODE === true) {
    $configurator->setDebugMode(true);
} elseif (isset($debugIPs)) {
    $configurator->setDebugMode($debugIPs);
}

$configurator->enableDebugger(DIR_ROOT . '/log', isset($debugMail) ? $debugMail : null);

$configurator->setTempDirectory(DIR_ROOT . '/temp');

if (!isset($robotLoaderDirs)) {
    $robotLoaderDirs = [];
}
$robotLoaderDirs[] = DIR_VENDOR . '/cothema/';
$robotLoaderDirs[] = DIR_ROOT . '/app/';

$robotLoader = $configurator->createRobotLoader();

foreach ($robotLoaderDirs as $robotLoaderDirsOne) {
    $robotLoader->addDirectory($robotLoaderDirsOne);
}

$robotLoader->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
is_file(DIR_ROOT . '/app/config/config.neon') && $configurator->addConfig(DIR_ROOT . '/app/config/config.neon');
$configurator->addConfig(DIR_ROOT . '/app/config/config.local.neon');

$container = $configurator->createContainer();

\Tracy\Debugger::getLogger()->emailSnooze = isset($debugMailSnooze) ? $debugMailSnooze : '30 minutes';

return $container;
