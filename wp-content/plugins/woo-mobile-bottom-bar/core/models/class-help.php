<?php

namespace MABEL_WCBB\Core\Models
{

	class Help
	{
		public $id;

		public $link_title;

		public $title;

		public $template;

		public function __construct($title, $link_title, $template)
		{
			$this->link_title = $link_title;
			$this->template = $template;
			$this->title = $title;
			$this->id = uniqid();
		}
	}
}