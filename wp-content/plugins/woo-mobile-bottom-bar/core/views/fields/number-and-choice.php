<?php
/** @var \MABEL_WCBB\Core\Models\Number_And_Choice_option $option */
if(!defined('ABSPATH')){
	die;
}
?>
<div style="display: inline-block;">
	<?php \MABEL_WCBB\Core\Common\Html::option($option->number_option); ?>
</div>
<div style="display: inline-block;">
	<?php \MABEL_WCBB\Core\Common\Html::option($option->dropdown_option); ?>
</div>

<?php
if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>