<?php

namespace MABEL_WCBB\Core\Models
{
	class Option_Section
	{
		public $title;

		public $icon;

		public $id;

		public $active;

		/**
		 * @var Option[] The options belonging to this section.
		 */
		private $options;

		public function __construct($id, $title, $icon, $active = false)
		{
			$this->options = array();
			$this->id = $id;
			$this->title = $title;
			$this->icon = $icon;
			$this->active = $active;
		}

		public function add_option(Option $option)
		{
			array_push($this->options, $option);
		}

		public function get_options()
		{
			return $this->options;
		}

		public function has_options()
		{
			return count($this->options) > 0;
		}
	}
}