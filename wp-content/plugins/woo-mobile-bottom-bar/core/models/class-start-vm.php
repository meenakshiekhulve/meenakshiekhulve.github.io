<?php

namespace MABEL_WCBB\Core\Models
{
	class Start_VM
	{
		/**
		 * @var string the plugin's settings key
		 */
		public $settings_key;

		/**
		 * @var Option_Section[]
		 */
		public $sections;

		/**
		 * @var string the plugin slug
		 */
		public $slug;

		/**
		 * @var Hidden_Option[]
		 */
		public $hidden_settings;

		public function __construct()
		{
			$this->hidden_settings = array();
			$this->sections = array();
		}
	}
}