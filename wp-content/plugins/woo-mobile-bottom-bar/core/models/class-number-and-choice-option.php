<?php

namespace MABEL_WCBB\Core\Models {

	class Number_And_Choice_option extends Option {

		/**
		 * @var Number_Option $number_option
		 */
		public $number_option;

		/**
		 * @var Dropdown_Option $dropdown_option
		 */
		public $dropdown_option;

		public function __construct($title, Number_Option $number_option, Dropdown_Option $dropdown_option, $extra = null, $dependency = null)
		{
			parent::__construct(null,null,$title,$extra,$dependency);
			$this->number_option = $number_option;
			$this->dropdown_option = $dropdown_option;
			$this->dropdown_option->dependency = $dependency;
			$this->number_option->dependency = $dependency;
		}

	}
}