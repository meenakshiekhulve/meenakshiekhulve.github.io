<?php
	/** @var \MABEL_WCBB\Core\Models\Autocomplete_Option $option */
?>
<div class="mabel-autocomplete-wrapper <?php echo $option->name === null ? $option->id : $option->name; ?>" data-action="<?php _e($option->ajax_action) ?>">
	<input type="hidden"
	       name="<?php echo $option->name === null ? $option->id : $option->name; ?>"
	       value="<?php echo $option->value; ?>"
		<?php echo $option->get_extra_data_attributes(); ?>
	/>
	<div>
	<input
		style="background:white;border:none;width:50px;margin:0;padding:0;"
		type="text"
		placeholder="Search..."
		class="mabel-formm-element"
	/>
	</div>
</div>

<?php
if(isset($option->extra_info))
	echo '<div class="p-t-1 extra-info">' . $option->extra_info .'</div>';
?>
