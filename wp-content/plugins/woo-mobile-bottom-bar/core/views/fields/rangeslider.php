<?php
/** @var \MABEL_WCBB\Core\Models\Range_Option $option */
if(!defined('ABSPATH')){ die; }
?>

<input
	style="opacity: 0;"
	class="mabel-formm-element"
	name="<?php echo $option->name === null ? $option->id : $option->name; ?>"
	type="range"
	min="<?php echo $option->min; ?>"
	max="<?php echo $option->max; ?>"
	step="<?php echo $option->step; ?>"
	value="<?php echo $option->value; ?>"
	<?php echo $option->get_extra_data_attributes(); ?>
/>

<?php
	if(isset($option->extra_info))
		echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>

