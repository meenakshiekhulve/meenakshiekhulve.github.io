<?php
/** @var \MABEL_WCBB\Core\Models\Hidden_Option $option */
?>

<input class="mabel-formm-element" type="hidden" name="<?php echo $option->name === null ? $option->id : $option->name; ?>" value="<?php echo $option->value; ?>" <?php echo $option->get_extra_data_attributes(); ?> />