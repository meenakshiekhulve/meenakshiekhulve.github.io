<?php
	use MABEL_WCBB\Core\Common\Managers\Config_Manager;
	/** @var \MABEL_WCBB\Core\Models\Help $help */

?>
<div class="p-t-1">
	<div style="display: none;" id="help-<?php echo $help->id; ?>">
		<div style="padding:20px;">
			<?php include Config_Manager::$dir . 'admin/views/' .$help->template; ?>
		</div>
	</div>
	<a title="<?php echo $help->title; ?>" href="#TB_inline?width=600&height=550&inlineId=help-<?php echo $help->id; ?>" class="primary thickbox">
		<?php echo $help->link_title == null ?
			__('More info', Config_Manager::$slug) :
			__($help->link_title, Config_Manager::$slug);
		?>
	</a>
</div>