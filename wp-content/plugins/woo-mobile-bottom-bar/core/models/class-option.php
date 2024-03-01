<?php

namespace MABEL_WCBB\Core\Models
{

	use MABEL_WCBB\Core\Common\Linq\Enumerable;
	use MABEL_WCBB\Core\Common\Managers\Config_Manager;

	class Option
	{
		/**
		 * @var string the id of the field. Must be the same as the database option name.
		 */
		public $id;

		public $title;

		public $value;

		public $extra_info;

		/** @var  Help */
		public $help;

		public $name;

		/**
		 * @var Option_Dependency[] show/hide element based on dependency.
		 */
		public $dependency;

		public $data_attributes;

		public function __construct($id, $value, $title, $extra_info = null, $dependency = array())
		{
			$this->value = $value;
			$this->title = $title;
			$this->id = $id;
			$this->extra_info = $extra_info;
			$this->dependency = $dependency;
			$this->data_attributes = array();
		}

		public function get_extra_data_attributes(){
			return join(' ', Enumerable::from($this->data_attributes)->select(function($v,$k){
				return 'data-'.$k.'="'.$v.'"';
			})->toArray());
		}

		public function display_help()
		{
			if($this->help == null) return;
			// for use in include
			$help = $this->help;
			include Config_Manager::$dir . 'core/views/fields/help.php';
		}
	}
}