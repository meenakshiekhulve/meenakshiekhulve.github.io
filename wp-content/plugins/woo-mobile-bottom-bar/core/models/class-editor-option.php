<?php

namespace MABEL_WCBB\Core\Models {

	class Editor_Option extends Option
	{
		public $options;
		public $content;

		public function __construct($id, $title, $content, array $options,
			$extra_info = null, $dependency = null)
		{
			parent::__construct($id, null, $title, $extra_info, $dependency);

			$this->options = $options;
			$this->content = $content ? $content : '';

			if($dependency !== null)
				add_filter('the_editor', array($this,'edit_wp_editor'));

			return $this;
		}

		public function edit_wp_editor($markup) {
			if (stripos($markup, 'id="'.$this->name == null? $this->id : $this->name.'"') !== false) {
				$markup = str_replace('<textarea', '<textarea data-dependency="'.htmlspecialchars(json_encode($this->dependency,ENT_QUOTES)).'"', $markup);
			}
			return $markup;
		}
	}
}