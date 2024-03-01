<?php
/** @var \MABEL_WCBB\Core\Models\Choicepicker_Option $option */
if(!defined('ABSPATH')){die;}
$id = $option->name === null ? $option->id : $option->name;
?>
<div class="mabel-mc-wrapper" data-id="<?php echo $id; ?>">

	<input
		type="hidden"
		name="<?php echo $id; ?>"
		value="<?php echo $option->values_to_key_list(); ?>"
		class="mabel-formm-element"
		<?php echo $option->get_extra_data_attributes(); ?>
	/>

	<div class="mabel-mc-chosen">
		<em class="infotext" style="<?php if(!empty($option->value)) _e('display:none'); ?>">
			<?php _e("Choose from the items below", \MABEL_WCBB\Core\Common\Managers\Config_Manager::$slug); ?>
		</em>

	</div>

	<div class="mabel-mc-options">

		<?php
			foreach($option->possible_values as $title => $options){
				echo '<span class="mabel-mc-title">'.$title.'</span>';
				foreach($options as $key => $value) {
					echo '<span class="mabel-mc-option" data-id="'.$key.'">'.(empty($value) ? 'n/a' : $value).'</span>';
				}
			}
		?>
	</div>
</div>

<?php
if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . esc_html($option->extra_info) .'</div>';
?>