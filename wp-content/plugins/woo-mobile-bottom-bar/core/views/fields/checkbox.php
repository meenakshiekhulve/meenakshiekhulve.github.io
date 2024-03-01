<?php
/** @var \MABEL_WCBB\Core\Models\Checkbox_Option $option */
if(!defined('ABSPATH')){
	die;
}

?>
<input type="hidden" name="<?php echo $option->name === null ? $option->id : $option->name; ?>" value="false" class="skip-dependency" />
<input
	class="mabel-form-element"
	type="checkbox"
	name="<?php echo $option->name === null ? $option->id : $option->name; ?>"
	value="true"
	<?php if(in_array($option->value,array('true','1',true),true)) echo ' checked '; ?>
	id="ckb-<?php echo $option->id; ?>"
	<?php echo !empty($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
	<?php echo $option->get_extra_data_attributes(); ?>
/>

<label for="ckb-<?php echo $option->id ?>">
	<?php echo esc_html($option->label); ?>
</label>

<?php
if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . $option->extra_info .'</div>';
?>
