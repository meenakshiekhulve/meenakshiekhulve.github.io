<?php

namespace MABEL_WCBB\Core\Common
{

	use MABEL_WCBB\Core\Common\Linq\Enumerable;
	use MABEL_WCBB\Core\Common\Managers\Config_Manager;
	use MABEL_WCBB\Core\Models\Inline_Style;

	class Presentation_Base
	{
		private $script_dependencies;
		private $scripts;
		/**
		 * @var Inline_Style[]
		 */
		private $inline_styles;
		private $styles;
		private $styles_for_later;
		/**
		 * @var array with key, value pairs to send to the frontend.
		 */
		private $script_variables;

		public $frontend_js_var;

		public function __construct()
		{
			$this->script_dependencies = array();
			$this->scripts = array();
			$this->styles = array();
			$this->inline_styles = array();
			$this->styles_for_later = array();
			$this->script_variables = array();
			$this->frontend_js_var = 'mabel_script_vars';
		}

		/**
		 * @param $id
		 * @param $file
		 * @param string|array $dependencies
		 */
		public function add_script($id, $file)
		{
			$this->scripts[$id] = $file;
		}

		public function add_style($id, $file)
		{
			$this->styles[$id] = $file;
		}

		public function add_inline_style(Inline_Style $style)
		{
			array_push($this->inline_styles, $style);
		}

		public function add_script_variable($key, $val)
		{
			$this->script_variables[$key] = $val;
		}

		public function add_script_dependencies($dependencies)
		{
			if(is_string($dependencies))
				array_push($this->script_dependencies, $dependencies);
			elseif(is_array($dependencies))
				$this->script_dependencies = array_merge($this->script_dependencies,$dependencies);
		}

		public function register_scripts()
		{
			foreach ($this->scripts as $id => $file)
			{
				wp_enqueue_script($id, Config_Manager::$url . $file, $this->script_dependencies, Config_Manager::$version, false);
			}

			if(sizeof($this->scripts) > 0 && sizeof($this->script_variables) > 0) {
				wp_localize_script(Config_Manager::$slug, $this->frontend_js_var, $this->script_variables);
			}
		}

		// Register a style but don't include it in the website yet.
		// This can be done at a later time then, if a condition is true (or a shortcode is used)
		public function add_styles_but_dont_publish_yet($id,$file) {
			$this->styles_for_later[$id] = $file;
		}

		// Immediatellt register the script for use on the website.

		public function register_styles()
		{
			foreach($this->styles as $id => $file)
			{
				wp_enqueue_style(
					$id,
					Config_Manager::$url . $file,
					array(),
					Config_Manager::$version,
					'all'
				);
			}

			foreach($this->inline_styles as $style)
			{
				$str = join('',Enumerable::from($style->styles)->select(function($v,$k){
					return $k .':'.$v.';';
				})->toArray());

				wp_add_inline_style( $style->handle, $style->rule . '{' . wp_strip_all_tags($str) . '}' );
			}

			foreach($this->styles_for_later as $id => $file)
			{
				wp_register_style($id,Config_Manager::$url . $file, array(), Config_Manager::$version);
			}
		}

		public function add_ajax_function($name,$component,$callable,$frontend = true,$backend = true)
		{
			if($frontend)
				add_action('wp_ajax_nopriv_' . $name,array($component,$callable));
			if($backend)
				add_action('wp_ajax_' . $name,array($component,$callable));
		}
	}
}
