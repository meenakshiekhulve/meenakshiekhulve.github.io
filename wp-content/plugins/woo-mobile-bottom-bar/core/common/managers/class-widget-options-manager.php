<?php

namespace MABEL_WCBB\Core\Common\Managers
{

	use MABEL_WCBB\Core\Models\Dropdown_Option;
	use MABEL_WCBB\Core\Models\Option;
	use MABEL_WCBB\Core\Models\Option_Dependency;
	use MABEL_WCBB\Core\Models\Text_Option;

	/**
	 * Register all options for a widget
	 * Class Options_Manager
	 * @package MABEL_WCBB\Core
	 */
	class Widget_Options_Manager extends Abstract_Options_Manager
	{
		/**
		 * @var Option[]
		 */
		public $options;

		public function __construct()
		{
			$this->options = array();
		}

		public function add_text_option($field_id,$field_title, $value, $placeholder = null, $extra_info = null,Option_Dependency $dependency = null)
		{
			$option = new Text_Option($field_id, $field_title, $value,$placeholder,$extra_info,$dependency);
			array_push($this->options, $option);
		}

		public function add_dropdown_option($field_id, $field_title, array $options,
			$selected_value = null, $extra_info = null, Option_Dependency $dependency = null,$pre_text = null, $post_text = null)
		{
			$option = new Dropdown_Option($field_id, $field_title, $options,
				$selected_value,$extra_info, $dependency, $pre_text, $post_text);
			array_push($this->options, $option);
		}
	}
}