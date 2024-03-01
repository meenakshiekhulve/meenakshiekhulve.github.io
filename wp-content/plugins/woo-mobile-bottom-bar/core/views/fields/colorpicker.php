<?php
/** @var \MABEL_WCBB\Core\Models\ColorPicker_Option $option */
?>

<input
	type="text"
	name="<?php echo $option->name === null ? $option->id : $option->name; ?>"
	value="<?php echo htmlspecialchars($option->value);?>"
	<?php echo !empty($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
	class="color-picker mabel-form-element"
/>

<?php
	if(isset($option->extra_info))
		echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>