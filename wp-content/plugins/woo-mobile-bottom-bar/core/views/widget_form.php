<?php

/** @var \MABEL_WCBB\Core\Common\Managers\Widget_Options_Manager $option_manager */

?>

<div class="widget-options">
	<?php
		foreach($option_manager->options as $option)
		{
			/** @var \MABEL_WCBB\Core\Models\Option $option */
			echo '<p>';
				echo '<label>' .$option->title. '</label>';
				echo '<div>';
					$option_manager->display_field(array('option' => $option));
				echo '</div>';
			echo '</p>';
		}
	?>
</div>
