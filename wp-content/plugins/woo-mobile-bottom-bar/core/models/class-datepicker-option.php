<?php

namespace MABEL_WCBB\Core\Models
{

	class Datepicker_Option extends Option
	{

		public $options;

		public function __construct( $id, $title, $value, $extra_info = null, $dependency = null, array $options )
		{
			parent::__construct($id,$value,$title,$extra_info,$dependency);
			$this->options = $options;
			return $this;
		}

	}

}