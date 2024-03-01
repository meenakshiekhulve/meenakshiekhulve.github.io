<?php

namespace MABEL_WCBB\Core\Models {

	class MediaSelector_Option extends Option
	{
		public $button_text;

		public function __construct($id, $value, $title, $button_text, $extra = null, $dependency = null)
		{
			parent::__construct($id,$value,$title,$extra,$dependency);
			$this->button_text = $button_text;
		}

	}
}