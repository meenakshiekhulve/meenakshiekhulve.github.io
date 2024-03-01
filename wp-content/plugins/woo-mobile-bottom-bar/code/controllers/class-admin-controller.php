<?php

namespace MABEL_WCBB\Code\Controllers
{

	use MABEL_WCBB\Core\Common\Admin;
	use MABEL_WCBB\Core\Common\Managers\Config_Manager;
	use MABEL_WCBB\Core\Common\Managers\Options_Manager;
	use MABEL_WCBB\Core\Common\Managers\Settings_Manager;
	use MABEL_WCBB\Core\Models\ColorPicker_Option;

	if(!defined('ABSPATH')){die;}

	class Admin_Controller extends Admin
	{
		private $slug;

		public function __construct()
		{
			parent::__construct(new Options_Manager());
			$this->slug = Config_Manager::$slug;
			$this->add_script_dependencies('wp-color-picker');
			$this->add_style('wp-color-picker',null);
		}

		public function init_admin_page() {
			$this->options_manager->add_section('settings', __('Settings',$this->slug), 'admin-settings', true);

			$this->options_manager->add_option('settings',new ColorPicker_Option(
				'fgcolor',
				Settings_Manager::get_setting('fgcolor'),
				__('Icons color', $this->slug)
			));
			$this->options_manager->add_option('settings',new ColorPicker_Option(
				'bgcolor',
				Settings_Manager::get_setting('bgcolor'),
				__('Background color', $this->slug)
			));
		}

	}
}