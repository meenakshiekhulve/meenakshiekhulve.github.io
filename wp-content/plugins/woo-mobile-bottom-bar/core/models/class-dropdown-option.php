<?php

namespace MABEL_WCBB\Core\Models
{

	class Dropdown_Option extends Option
	{

		/**
		 * @var array with options as key => label.
		 */
		public $options;

		/**
		 * @var string
		 */
		public $pre_text;

		/**
		 * @var string
		 */
		public $post_text;

		public function __construct($id, $title, array $options, $selected_option = null,
			$extra_info = null,$dependency = null, $pre_text = null, $post_text=null)
		{

			parent::__construct($id, $selected_option, $title, $extra_info, $dependency);

			$this->pre_text = $pre_text;
			$this->post_text = $post_text;
			$this->options = $options;
			return $this;
		}

	}

}