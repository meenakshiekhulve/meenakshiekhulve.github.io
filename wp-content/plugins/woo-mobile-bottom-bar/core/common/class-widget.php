<?php

namespace MABEL_WCBB\Core\Common
{

	use MABEL_WCBB\Core\Common\Managers\Config_Manager;
	use MABEL_WCBB\Core\Common\Managers\Widget_Options_Manager;
	use WP_Widget;

	class Widget extends WP_Widget
	{
		private $shortcode;

		private $widget_id;

		private $fields;

		private $option_manager;

		/**
		 * @var string to display instead of the form.
		 */
		public $warning;

		public function __construct($id, $title, $description, $shortcode, Widget_Options_Manager $options)
		{
			$this->shortcode = $shortcode;
			$this->widget_id = $id;
			$this->fields = array();
			$this->option_manager = $options;
			parent::__construct($id, $title, array('description' => $description));
		}

		public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$argument_list = array();

			foreach($instance as $k => $v) {
				array_push($argument_list, $k . '="' .$v . '"' );
			}

			echo do_shortcode('[' . $this->shortcode . ' ' .join(' ', $argument_list) . ']');

			echo $args['after_widget'];
		}


		public function form( $instance )
		{
			if($this->warning){
				echo $this->warning;
			}else{

				// Add all saved values to the options
				foreach ($this->option_manager->options as $option){
					$option->value = $instance[$option->id];
					$option->name = $this->get_field_name($option->id);
				}

				ob_start();

				// for template
				$option_manager = $this->option_manager;

				include Config_Manager::$dir . 'core/views/widget_form.php';

				echo ob_get_clean();
			}
		}

	}
}