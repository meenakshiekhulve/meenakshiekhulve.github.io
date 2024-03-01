<?php
if(!defined('ABSPATH')){die;}
use \MABEL_WCBB\Core\Common\Managers\Config_Manager;
use \MABEL_WCBB\Core\Common\Html;
/** @var \MABEL_WCBB\Core\Models\Start_VM $model */

add_thickbox();
?>
<div class="mabel-loading" style="display: none;"></div>
<div class="padding-t">
	<div class="mabel-container">
		<div class="mabel-row">
			<div class="mabel-nine mabel-columns">
				<h2 class="mabel-nav-tab-wrapper">
					<?php
						foreach($model->sections as $section)
						{
							echo
								'<a data-tab="options-'.$section->id.'" href="#" class="mabel-nav-tab'.($section->active === true? '  mabel-nav-tab-active':'').'">
										<i class="dashicons dashicons-'.$section->icon.'"></i>
										<span>'.__($section->title, $model->slug).'</span>
									</a>';
						}
						do_action($model->slug . '-add-tabs');
					?>
				</h2>
				<form action="options.php" id="<?php echo $model->slug; ?>-form" method="POST">
					<?php
					settings_fields( $model->slug );
					foreach($model->sections as $section)
					{
						echo '<div class="mabel-tab tab-options-'.$section->id.'" '.($section->active === true? '':'style="display:none;"').'>';
						if($section->has_options())
						{
							echo '<table class="form-table">';
							foreach($section->get_options() as $o)
							{
								echo '<tr>';
								if(!empty($o->title))
									echo '<th scope="row">'.$o->title.'</th>';
								echo '<td>';
								Html::option($o);
								echo '</td></tr>';
							}
							echo '</table>';
						}

						do_action($model->slug . '-add-section-content-' . $section->id);

						echo '<div class="p-t-2">
										<span class="all-settings-saved"><i class="icon-check icon-15"></i> '.__('All settings saved', $model->slug). '</span>
										<span style="display:none;" class="saving-settings">Saving settings...</span>
							     </div>';
						echo '</div>';

					}

					do_action($model->slug . '-add-panels');

					foreach($model->hidden_settings as $option)
					{
						include Config_Manager::$dir . 'core/views/fields/hidden.php';
					}
					?>
				</form>
			</div>

			<div class="mabel-three mabel-columns">
				<div style="display: none;" class="mabel-sidebar sidebar-main" data-sidebar-for="main">
					<?php
					do_action($model->slug . '-render-sidebar');
					?>
				</div>
				<?php
				foreach($model->sections as $section)
				{
					echo '<div style="display: none;" class="mabel-sidebar sidebar-' .$section->id. '" data-sidebar-for="options-' .$section->id. '">';
					do_action($model->slug . '-render-sidebar-'.$section->id);
					echo '</div>';
				}
				?>
			</div>
		</div>
	</div>
</div>

<?php
do_action($model->slug . '-add-content');
?>
<div
	data-context
	data-settings-key="<?php echo $model->settings_key ?>"
	data-slug="<?php echo $model->slug ?>"
	data-admin-ajax-url="<?php echo admin_url('admin-ajax.php'); ?>">
</div>