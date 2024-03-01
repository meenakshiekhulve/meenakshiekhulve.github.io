<?php

namespace MABEL_WCBB\Core\Models
{

	class Text_Option extends Option
	{

		/**
		 * @var string the field's placeholder.
		 */
		public $placeholder;

		/**
		 * @var string the field's value.
		 */
		public $value;

		/**
		 * @var string
		 */
		public $pre_text;

		/**
		 * @var string
		 */
		public $post_text;

		public $is_textarea;

		public function __construct( $id, $title, $value = null, $placeholder = null, $extra_info = null,
			$dependency = null, $pre_text = null, $post_text = null, Help $help = null, $is_textarea = false )
		{
			parent::__construct( $id, $value, $title, $extra_info, $dependency );
			$this->pre_text = $pre_text;
			$this->post_text = $post_text;
			$this->placeholder = $placeholder;
			$this->is_textarea = $is_textarea;
			$this->help = $help;
			return $this;
		}



	}

}