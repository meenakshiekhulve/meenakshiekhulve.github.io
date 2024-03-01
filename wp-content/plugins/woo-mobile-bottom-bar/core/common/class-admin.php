<?php

namespace MABEL_WCBB\Core\Common
{
	use MABEL_WCBB\Core\Common\Managers\Config_Manager;
	use MABEL_WCBB\Core\Common\Managers\Options_Manager;
	use MABEL_WCBB\Core\Models\Start_VM;

	abstract class Admin extends Presentation_Base
	{
		public $options_manager;
		public $add_mediamanager_scripts;
		private static $notices = array();

		public function __construct(Options_Manager $options_manager)
		{
			parent::__construct();
			$this->add_mediamanager_scripts = false;
			$this->options_manager = $options_manager;

			$this->add_script_dependencies('jquery');

			$this->add_script(Config_Manager::$slug,'admin/js/admin.min.js');
			$this->add_style(Config_Manager::$slug,'admin/css/admin.min.css');

			add_action('admin_menu', array($this, 'add_menu'));
			add_filter('plugin_action_links_' . Config_Manager::$plugin_base, Array($this, 'add_settings_link'));

			add_action( 'admin_init',array($this, 'init_settings'));

			if(isset($_GET['page']) && $_GET['page'] === Config_Manager::$slug)
			{
				add_action( 'admin_enqueue_scripts', array($this, 'register_styles'));
				add_action( 'admin_enqueue_scripts', array($this, 'register_scripts'));
				add_action('admin_init', array($this,'init_admin_page'));
				add_action('admin_notices', array($this,'show_admin_notices'));
			}
		}

		public function show_admin_notices() {
			$notices = self::$notices;

			foreach( $notices as $notice ) {
				echo '<div class="notice is-dismissible notice-'.$notice['class'].'"><p>'.$notice['message'].'</p></div>';
			}

		}

		public abstract function init_admin_page();

		public function add_settings_link( $links )
		{
			$my_links = array(
				'<a href="' . admin_url( 'options-general.php?page=' .Config_Manager::$slug ) . '">' .__('Settings' , Config_Manager::$slug). '</a>',
			);
			return array_merge( $links, $my_links );
		}

		public function add_menu()
		{
			$page = add_options_page('', Config_Manager::$name, 'manage_options', Config_Manager::$slug, array($this,'display_settings'));
		}

		public function init_settings()
		{
			register_setting( Config_Manager::$slug , Config_Manager::$settings_key );
		}

		public function display_settings()
		{
			$model = new Start_VM();
			$model->settings_key = Config_Manager::$settings_key;
			$model->sections = $this->options_manager->get_sections();
			$model->hidden_settings = $this->options_manager->get_hidden_settings();
			$model->slug = Config_Manager::$slug;

			ob_start();
			include Config_Manager::$dir . 'core/views/start.php';
			echo ob_get_clean();
		}

		public function register_styles() {
			if(isset($_GET['page']) && $_GET['page'] == Config_Manager::$slug)
				parent::register_styles();
		}

		public function register_scripts()
		{
			if(isset($_GET['page']) && $_GET['page'] == Config_Manager::$slug)
				parent::register_scripts();
			if($this->add_mediamanager_scripts){
				wp_enqueue_media();
			}
		}
	}
}