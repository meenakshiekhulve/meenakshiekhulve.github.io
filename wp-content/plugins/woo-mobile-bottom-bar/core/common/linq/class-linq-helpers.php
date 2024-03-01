<?php

namespace MABEL_WCBB\Core\Common\Linq {

	use Exception;

	class Linq_Helpers
	{
		public static function create_lambda ($closure, $closureArgs = null)
		{
			if(!is_string($closure)) return $closure;

			$posDollar = strpos($closure, '$');
			if ($posDollar !== false) {
				$posArrow = strpos($closure, '=>', $posDollar);
				if ($posArrow !== false) {
					$args = trim(substr($closure, 0, $posArrow), "() \r\n\t");
					$code = substr($closure, $posArrow + 3);
				}
				else {
					$args = '$' . str_replace(',', '=null,$', $closureArgs) . '=null';
					$code = $closure;
				}
				$code = trim($code, " \r\n\t");
				if (strlen($code) > 0 && $code[0] != '{')
					$code = "return {$code};";
				$fun = create_function($args, $code);
				if (!$fun)
					throw new Exception("Cannot parse lambda.");
				return $fun;
			}
			return null;
		}
	}
}