<?php

namespace MABEL_WCBB\Core\Models
{

	class Autocomplete_Option extends Option
	{

		public $ajax_action;

		public function __construct( $id, $title, $values, $ajax_action, $extra_info = null, $dependency = null )
		{
			parent::__construct( $id, $values, $title, $extra_info, $dependency );
			$this->ajax_action = $ajax_action;
			return $this;
		}

	}

}