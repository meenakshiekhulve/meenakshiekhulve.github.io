<?php

namespace MABEL_WCBB\Core\Models
{

	/**
	 * Class Option_Dependency
	 * @package MABEL_WCBB\Core\Models
	 * Contains dependency info: a field is only visible when the depending field's value is set.
	 */

	class Option_Dependency
	{
		public $element_id;

		public $value;

		public $not_empty;

		public function __construct($element_id, $value, $not_empty = false)
		{
			$this->element_id = $element_id;
			$this->value = $value;
			$this->not_empty = $not_empty;
		}
	}
}