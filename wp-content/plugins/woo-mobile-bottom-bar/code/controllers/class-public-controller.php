<?php

namespace MABEL_WCBB\Code\Controllers
{

	use MABEL_WCBB\Code\Models\Bar_Model;
	use MABEL_WCBB\Core\Common\Frontend;
	use MABEL_WCBB\Core\Common\Html;
	use MABEL_WCBB\Core\Common\Managers\Config_Manager;
	use MABEL_WCBB\Core\Common\Managers\Settings_Manager;

	if(!defined('ABSPATH')){die;}

	class Public_Controller extends Frontend
	{
		public function __construct()
		{
			parent::__construct();

			$this->add_script_dependencies('jquery');
			$this->add_script(Config_Manager::$slug,'public/js/public.min.js');
			$this->frontend_js_var = 'wcbbVars';
			$this->add_style(Config_Manager::$slug,'public/css/public.min.css');

			add_action('wp_footer',array($this,'add_bar_to_footer'));
			add_filter( 'woocommerce_add_to_cart_fragments', array($this,'add_cart_count_fragment'));

		}

		public function add_cart_count_fragment($fragments) {
			$fragments['span.wcbb-count'] = '<span class="wcbb-count">' . WC()->cart->get_cart_contents_count() . '</span>';
			return $fragments;
		}

		public function add_bar_to_footer(){
			$model = new Bar_Model();

			$model->bgcolor = Settings_Manager::get_setting('bgcolor');
			$model->fgcolor = Settings_Manager::get_setting('fgcolor');
			$model->cart_url = wc_get_cart_url();
			$model->account_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
			$model->cart_count = WC()->cart->get_cart_contents_count();

			Html::partial('code/views/bar', $model);
		}
	}
}