<?php

namespace MABEL_WCBB\Core\Common
{

	use MABEL_WCBB\Core\Common\Managers\Config_Manager;
	use MABEL_WCBB\Core\Models\Autocomplete_Option;
	use MABEL_WCBB\Core\Models\Checkbox_Option;
	use MABEL_WCBB\Core\Models\Choicepicker_Option;
	use MABEL_WCBB\Core\Models\ColorPicker_Option;
	use MABEL_WCBB\Core\Models\Container_Option;
	use MABEL_WCBB\Core\Models\Custom_Option;
	use MABEL_WCBB\Core\Models\Datepicker_Option;
	use MABEL_WCBB\Core\Models\Dropdown_Option;
	use MABEL_WCBB\Core\Models\Editor_Option;
	use MABEL_WCBB\Core\Models\MediaSelector_Option;
	use MABEL_WCBB\Core\Models\Number_And_Choice_option;
	use MABEL_WCBB\Core\Models\Number_Option;
	use MABEL_WCBB\Core\Models\Option;
	use MABEL_WCBB\Core\Models\Pro_option;
	use MABEL_WCBB\Core\Models\Range_Option;
	use MABEL_WCBB\Core\Models\Text_Option;

	class Html
	{
		/**
		 * Echo a view
		 * @param $view
		 * @param $model
		 */
		public static function partial($view,$model)
		{
			ob_start();
			include Config_Manager::$dir . $view . '.php';
			echo ob_get_clean();
		}

		/**
		 * return a view.
		 * @param $view
		 * @param $model
		 *
		 * @return string
		 */
		public static function view($view,$model)
		{
			ob_start();
			include Config_Manager::$dir . 'code/views/' . $view . '.php';
			return ob_get_clean();
		}

		public static function option(Option $option)
		{
			$field_dir = Config_Manager::$dir . 'core/views/fields/';

			if($option instanceof Checkbox_Option) {
				return require $field_dir . 'checkbox.php';
			}

			if($option instanceof Dropdown_Option) {
				return require $field_dir . 'dropdown.php';
			}

			// Needs to be checked before Text_Option as it derives from it.
			if($option instanceof Number_Option) {
				return require $field_dir . 'number.php';
			}

			if($option instanceof Pro_option)
				return require $field_dir .'pro-option.php';

			if($option instanceof Text_Option) {
				return require $field_dir . 'textbox.php';
			}

			if($option instanceof ColorPicker_Option) {
				return require $field_dir . 'colorpicker.php';
			}

			if($option instanceof Range_Option) {
				return require $field_dir . 'rangeslider.php';
			}

			if($option instanceof Editor_Option) {
				return require $field_dir . 'editor.php';
			}

			if($option instanceof MediaSelector_Option) {
				return require $field_dir . 'media-selector.php';
			}

			if($option instanceof Choicepicker_Option) {
				return require $field_dir . 'choice-picker.php';
			}

			if($option instanceof Datepicker_Option) {
				return require $field_dir . 'datepicker.php';
			}

			if($option instanceof Autocomplete_Option) {
				return require $field_dir . 'autocomplete.php';
			}

			if($option instanceof Custom_Option) {
				$data = $option->data;
				$slug = Config_Manager::$slug;
				return  require Config_Manager::$dir . 'admin/views/' . $option->template . '.php';
			}

			if($option instanceof Container_Option) {
				return require $field_dir . 'container-option.php';
			}

			if($option instanceof Number_And_Choice_option) {
				return require $field_dir . 'number-and-choice.php';
			}

		}

	}
}