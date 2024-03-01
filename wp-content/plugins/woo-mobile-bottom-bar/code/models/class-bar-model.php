<?php

namespace MABEL_WCBB\Code\Models
{
	class Bar_Model {

		public $fgcolor;
		public $bgcolor;
		public $cart_url;
		public $account_url;
		public $cart_count;

		public function __construct() {
			$this->fgcolor = 'white';
			$this->bgcolor = '#2C2D33';
		}

	}
}