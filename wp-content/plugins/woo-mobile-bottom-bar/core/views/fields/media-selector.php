<?php
/** @var \MABEL_WCBB\Core\Models\MediaSelector_Option $option */
if(!defined('ABSPATH')){
	die;
}
$id = $option->name === null ? $option->id : $option->name;
?>
<div class="mabel-media-selector" data-for="<?php _e($id); ?>">

	<div class="mabel-media-preview" style="<?php if(empty($option->value)) _e('display:none;'); ?>">
		<img src="<?php _e($option->value); ?>">
	</div>

	<a class="mabel-btn" href="#">
		<?php _e($option->button_text); ?>
	</a>

	<input
		type="hidden"
		name="<?php _e($id); ?>"
		value="<?php _e($option->value); ?>"
		class="mabel-formm-element"
	/>

	<?php
	if(isset($option->extra_info))
		echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
	?>
</div>
