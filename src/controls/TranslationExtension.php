<?php

namespace Cothema\CMSBE\Translation;

/**
 * @author Milos Havlicek <havlicek@cothema.com>
 */
class TranslationExtension extends \Kdyby\Translation\DI\TranslationExtension {

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration() {
		$config = $this->getConfig();

		if (isset($config['cothema']['modules'])) {
			foreach ($config['cothema']['modules'] as $module) {
				$this->defaults['dirs'][] = DIR_VENDOR . '/' . $module . '/src/lang';
			}
		}

		parent::loadConfiguration();
	}

}
