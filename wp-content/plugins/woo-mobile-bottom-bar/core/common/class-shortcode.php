<?php

namespace MABEL_WCBB\Core\Common
{
	
	class Shortcode
	{

		/**
		 * @var string the shortcode tag to use it.
		 */
		private $shortcode_tag;

		/**
		 * @var array default attribute values for the shortcode
		 */
		private $defaults;

		/**
		 * @var string template file.
		 */
		private $view;

		/**
		 * @var callable the function to create the model.
		 */
		private $data_loader;

		/**
		 * Shortcode constructor.
		 *
		 * @param $shortcode_tag string
		 * @param $view string template string.
		 * @param $data_loader callable loads the data for the view. Must return an array.
		 * @param $defaults array
		 */
		public function __construct($shortcode_tag, $view, $data_loader, array $defaults = array())
		{
			$this->shortcode_tag = $shortcode_tag;
			$this->defaults = $defaults;
			$this->view = $view;
			$this->data_loader = $data_loader;

			add_shortcode($shortcode_tag, array($this, 'render_shortcode'));
		}

		public function render_shortcode($attributes, $content, $code)
		{
			$attributes = (array)$attributes;

			// We don't use WP's shortcode_atts because we don't want to set defaults all the time.
			// TODO: add filter, like in shortcode_atts function.
			$attributes = array_merge($this->defaults,$attributes);

			$this->sanitize_attribures( $attributes );

			$model = null;

			if(!is_null($this->data_loader))
				$model = call_user_func_array($this->data_loader,array($attributes, $content, $code));

			if($this->view !== null)
				return Html::view($this->view, $model);
			else return $model;

		}

		/**
		 * Sanitize attributes: convert strings to booleans.
		 * @param array $attributes
		 */
		private function sanitize_attribures(array &$attributes)
		{
			foreach($attributes as $key => $value){

				if($value === 'true' )
					$attributes[$key] = true;

				if($value === 'false')
					$attributes[$key] = false;
			}
		}
	}

}