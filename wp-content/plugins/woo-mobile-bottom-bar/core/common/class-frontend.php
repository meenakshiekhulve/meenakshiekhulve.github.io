<?php

namespace MABEL_WCBB\Core\Common
{

	class Frontend extends Presentation_Base
	{
		public function __construct()
		{
			parent::__construct();
			$this->add_script_variable('ajaxurl',admin_url('admin-ajax.php'));
			add_action( 'wp_enqueue_scripts', array($this, 'register_styles'));
			add_action( 'wp_enqueue_scripts', array($this, 'register_scripts'));
		}

	}
}