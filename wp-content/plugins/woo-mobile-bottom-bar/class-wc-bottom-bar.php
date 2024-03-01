<?php

namespace MABEL_WCBB
{

	use MABEL_WCBB\Code\Controllers\Public_Controller;
	use MABEL_WCBB\Core\Common\Managers\Config_Manager;
	use MABEL_WCBB\Core\Common\Managers\Language_Manager;
	use MABEL_WCBB\Core\Common\Managers\Settings_Manager;
	use MABEL_WCBB\Code\Controllers\Admin_Controller;

	if(!defined('ABSPATH')){die;}

	class WC_Bottom_Bar
	{
		/**
		 * @var Language_Manager language manager.
		 */
		protected $language_manager;

		/**
		 * Business_Hours_Indicator constructor.
		 *
		 * @param $dir string
		 * @param $url string
		 * @param $slug string
		 * @param $version string
		 */
		public function __construct($dir, $url, $plugin_base, $name, $version, $settings_key)
		{
			// Init meta info.
			Config_Manager::init($dir, $url, $plugin_base, $version, $settings_key, $name);
		}

		public function run() {

			// Init translations.
			$this->language_manager = new Language_Manager();

			// Init settings with defaults.
			Settings_Manager::init(array(
				'fgcolor' => '#ffffff',
				'bgcolor' => '#2C2D33',
			));

			// Kick off admin page.
			if(is_admin())
				new Admin_Controller();
			// Kick off public side of things.
			new Public_Controller();
		}
	}
}