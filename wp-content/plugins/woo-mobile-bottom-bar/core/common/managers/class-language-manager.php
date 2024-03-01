<?php

namespace MABEL_WCBB\Core\Common\Managers
{

	if(!defined('ABSPATH')){die;}

	/**
	 * Loads the translations in 'languages' folder.
	 * Class Language
	 * @package MABEL_WCBB\Includes\Core
	 */
	class Language_Manager
	{
		protected $language_folder = 'languages';

		public function __construct()
		{
			add_action('plugins_loaded', array($this, 'load_text_domain'));
		}

		public function load_text_domain()
		{
			load_plugin_textdomain(
				Config_Manager::$slug,
				false,
				plugin_basename(Config_Manager::$slug) .'/'. $this->language_folder . '/'
			);
		}
	}
}
