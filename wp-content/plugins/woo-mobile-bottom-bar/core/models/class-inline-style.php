<?php

namespace MABEL_WCBB\Core\Models
{
	class Inline_Style
	{
		public $handle;
		public $rule;

		/**
		 * @var array
		 */
		public $styles;

		public function __construct($handle,$rule,$styles = array())
		{
			$this->handle = $handle;
			$this->rule = $rule;
			$this->styles = $styles;
		}
	}
}