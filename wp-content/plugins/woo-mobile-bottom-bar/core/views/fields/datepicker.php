<?php
/** @var \MABEL_WCBB\Core\Models\Datepicker_Option $option */
?>

<input
	type="text"
	name="<?php echo $option->name === null ? $option->id : $option->name; ?>"
	value="<?php echo htmlspecialchars($option->value);?>"
	<?php echo !empty($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
	class="widefat mabel-date-picker mabel-form-element"
	<?php echo $option->get_extra_data_attributes(); ?>
	data-options="<?php esc_attr_e(json_encode($option->options)) ?>"
/>

<?php
if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>
