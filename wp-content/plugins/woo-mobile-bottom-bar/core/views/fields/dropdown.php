<?php
/** @var \MABEL_WCBB\Core\Models\Dropdown_Option $option */
if(!defined('ABSPATH')){
	die;
}

$has_pre_text = false;

if(isset($option->pre_text)) {
	echo '<span>' . esc_html($option->pre_text) . '</span>';
	$has_pre_text = true;
}
?>

<select
	class="widefat mabel-form-element"
	<?php echo $has_pre_text ? 'style="padding:0 10px;width:auto;"' : ''; ?>
	name="<?php echo $option->name === null ? $option->id : $option->name; ?>"
	<?php echo !empty($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
	<?php echo $option->get_extra_data_attributes(); ?>
>
	<?php
		foreach($option->options as $key=>$value){
			$selected = $key == $option->value;
			echo '<option ' . ($selected?'selected':'') .' value="'.$key.'">'.$value.'</option>';
		}
	?>
</select>

<?php
if(isset($option->post_text))
	echo '<span>' . $option->post_text . '</span>';
?>

<?php

if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . $option->extra_info . '</div>';
