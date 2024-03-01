<?php

namespace MABEL_WCBB\Core\Models
{

	class Container_Option extends Option
	{
		/**
		 * @var Option[] sub-options.
		 */
		public $options;

		public $button_text;

		public function __construct($title, $button_text,$extra_info = null,array $dependency = null)
		{
			parent::__construct(uniqid(),null,$title,$extra_info,$dependency);
			$this->options = array();
			$this->button_text = $button_text;
		}
	}
}