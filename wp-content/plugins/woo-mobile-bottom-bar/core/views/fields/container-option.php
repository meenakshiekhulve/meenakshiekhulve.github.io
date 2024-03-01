<?php
	use \MABEL_WCBB\Core\Common\Html;
	/** @var \MABEL_WCBB\Core\Models\Container_Option $option */
?>
<div
	class="mabel-accordion mabel-form-element"
	name="<?php echo $option->name === null ? $option->id : $option->name; ?>"
	<?php echo !empty($option->dependency) ? 'data-dependency="' . htmlspecialchars(json_encode($option->dependency,ENT_QUOTES)) . '"':''; ?>
>
	<button class="mabel-accordion-btn"><?php echo $option->button_text; ?></button>
	<div style="display: none;">
		<table class="form-table">
			<?php
				foreach($option->options as $o)
				{
					echo '<tr>';
					if(!empty($o->title))
						echo '<th scope="row">'.$o->title.'</th>';
					echo '<td '.(empty($o->title) ? 'colspan="2"' : '').'>';
						Html::option($o);
					echo '</td>';
				}
			?>
		</table>
	</div>
</div>