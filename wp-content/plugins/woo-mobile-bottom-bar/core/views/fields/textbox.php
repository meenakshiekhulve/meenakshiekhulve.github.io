<?php
/** @var \MABEL_WCBB\Core\Models\Text_Option $option */
use MABEL_WCBB\Core\Common\Managers\Config_Manager;
if(!defined('ABSPATH')){
	die;
}

?>

<?php if(!$option->is_textarea) { ?>

	<input
		class="widefat mabel-form-element"
		type="text"
		name="<?php echo $option->name === null ? $option->id : $option->name; ?>"
		value="<?php echo htmlspecialchars($option->value);?>"
		placeholder="<?php echo $option->placeholder; ?>"
	    <?php echo !empty($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
		<?php echo $option->get_extra_data_attributes(); ?>
	/>
<?php }else { ?>
	<textarea
		class="widefat mabel-form-element"
		name="<?php echo $option->name === null ? $option->id : $option->name; ?>"
		placeholder="<?php echo $option->placeholder; ?>"
		<?php echo !empty($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
		<?php echo $option->get_extra_data_attributes(); ?>
	><?php echo htmlspecialchars($option->value);?></textarea>
<?php
}
	$option->display_help();
	if(isset($option->extra_info))
		echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>
