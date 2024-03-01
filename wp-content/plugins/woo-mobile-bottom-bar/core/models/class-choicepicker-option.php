<?php

namespace MABEL_WCBB\Core\Models
{

	use MABEL_WCBB\Core\Common\Linq\Enumerable;

	class Choicepicker_Option extends Option
	{

		/**
		 * @var array
		 */
		public $possible_values;

		public function __construct( $id, $title, $selected_values,$values, $extra_info = null, $dependency = null )
		{
			parent::__construct($id,$selected_values,$title,$extra_info,$dependency);
			$this->possible_values = $values;
			return $this;
		}

		public function values_to_key_list() {
			return join(';',$this->value);
		}

		public function find_value_of_key($key) {

			return isset($this->possible_values[$key]) ? $this->possible_values[$key] : $this->special_values[$key];
		}

	}

}