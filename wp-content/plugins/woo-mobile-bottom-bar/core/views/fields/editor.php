<?php
/** @var \MABEL_WCBB\Core\Models\Editor_Option $option */
?>

<?php
wp_editor($option->content,$option->name == null? $option->id : $option->name,array_merge(array(
	'textarea_rows' => 15,
	'media_buttons' => false,
), $option->options));

if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>