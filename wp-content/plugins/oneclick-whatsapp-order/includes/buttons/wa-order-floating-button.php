<?php
// Helper function
function wa_order_is_floating_tooltip_enabled()
{
	return get_option('wa_order_floating_tooltip_enable', 'no') === 'yes';
}

// Display Floating Button
function display_floating_button()
{
	global $wa_base;
	if (!isset($wa_base)) {
		return;
	}
	if (wa_order_is_floating_tooltip_enabled()) {
		// If tooltip is enabled, don't display the standard floating button
		return;
	}
	$floating = get_option('wa_order_floating_button');
	if ($floating !== 'yes') {
		return;
	}
	$floating_position = get_option('wa_order_floating_button_position', 'left');
	$custom_message = urlencode(get_option('wa_order_floating_message', ''));
	$floating_target = get_option('wa_order_floating_target', '_blank');
	$wanumberpage = get_option('wa_order_selected_wa_number_floating', '');
	$postid = get_page_by_path($wanumberpage, OBJECT, 'wa-order-numbers');
	if (!$postid) {
		return;
	}
	$phonenumb = get_post_meta($postid->ID, 'wa_order_phone_number_input', true);
	if (!$phonenumb) {
		return;
	}
	$include_source = get_option('wa_order_floating_source_url', 'no');
	$src_label = get_option('wa_order_floating_source_url_label', 'From URL:');
	$source_url = $include_source === 'yes' ? urlencode(home_url(add_query_arg(null, null))) : '';
	$floating_message = $custom_message . ($include_source === 'yes' ? "\r\n\r\n*" . $src_label . "* " . $source_url : '');
	$floating_link = "https://{$wa_base}.whatsapp.com/send?phone=$phonenumb&text=$floating_message";
?>
	<a id="sendbtn" class="floating_button" href="<?php echo esc_url($floating_link); ?>" role="button" target="<?php echo esc_attr($floating_target); ?>"></a>
<?php
}
add_action('wp_footer', 'display_floating_button');

// Floating Button CSS
function display_floating_button_css()
{
	$floating = get_option('wa_order_floating_button');
	if ($floating !== 'yes') {
		return;
	}

	$floating_position = get_option('wa_order_floating_button_position', 'left');
?>
	<style>
		.floating_button {
			<?php echo $floating_position; ?>: 20px;
		}

		@media only screen and (max-width: 480px) {
			.floating_button {
				<?php echo $floating_position; ?>: 10px !important;
			}
		}
	</style>
	<?php
}
add_action('wp_head', 'display_floating_button_css');

// Display Floating Button with Tooltip
function display_floating_tooltip()
{
	global $wa_base;
	if (!isset($wa_base)) {
		return;
	}
	if (!wa_order_is_floating_tooltip_enabled()) {
		// If tooltip is not enabled, don't display the tooltip floating button
		return;
	}
	$floating = get_option('wa_order_floating_button', 'no');
	$floating_position = get_option('wa_order_floating_button_position', 'left');
	$custom_message = urlencode(get_option('wa_order_floating_message', ''));
	$floating_target = get_option('wa_order_floating_target', '_blank');
	$wanumberpage = get_option('wa_order_selected_wa_number_floating', '');
	$postid = get_page_by_path($wanumberpage, OBJECT, 'wa-order-numbers');
	if (!$postid) {
		return;
	}
	$phonenumb = get_post_meta($postid->ID, 'wa_order_phone_number_input', true);
	if (!$phonenumb) {
		return;
	}
	$tooltip_enable = get_option('wa_order_floating_tooltip_enable', 'no');
	$tool_tip = get_option('wa_order_floating_tooltip', "Let's Chat");
	$include_source = get_option('wa_order_floating_source_url', 'no');
	$src_label = get_option('wa_order_floating_source_url_label', 'From URL:');
	$source_url = $include_source === 'yes' ? urlencode(home_url(add_query_arg(null, null))) : '';
	$floating_message = $custom_message . ($include_source === 'yes' ? "\r\n\r\n*" . $src_label . "* " . $source_url : '');
	$floating_link = "https://{$wa_base}.whatsapp.com/send?phone=$phonenumb&text=$floating_message";
	if ($floating === 'yes' && $tooltip_enable === 'yes') {
	?>
		<a id="sendbtn" href="<?php echo esc_url($floating_link); ?>" role="button" target="<?php echo esc_attr($floating_target); ?>" class="floating_button">
			<div class="label-container">
				<div class="label-text"><?php echo esc_html($tool_tip); ?></div>
			</div>
		</a>
		<style>
			.floating_button {
				<?php echo esc_html($floating_position); ?>: 20px;
			}

			.label-container {
				<?php echo esc_html($floating_position); ?>: 85px;
			}
		</style>
<?php
	}
}
add_action('wp_footer', 'display_floating_tooltip');

// Desktop & Mobile Visibities
function wa_order_adjust_floating_button_visibility()
{
	$floating_mobile = get_option('wa_order_floating_hide_mobile', 'no');
	$floating_desktop = get_option('wa_order_floating_hide_desktop', 'no');

	if ($floating_mobile === 'yes' || $floating_desktop === 'yes') {
		echo '<style>';
		if ($floating_mobile === 'yes') {
			// Hides on mobile devices
			echo '@media only screen and (max-width: 480px) { .floating_button { display: none !important; } }';
		}
		if ($floating_desktop === 'yes') {
			// Hides on desktop
			echo '@media (min-width: 481px) { .floating_button { display: none !important; } }';
		}
		echo '</style>';
	}
}
add_action('wp_footer', 'wa_order_adjust_floating_button_visibility');


// Conditionally Hide Floating Button on selected queries
function wa_order_hide_floating_button_conditionally()
{
	global $post;

	// Get the settings as arrays
	$posts_array = (array) get_option('wa_order_floating_hide_specific_posts');
	$pages_array = (array) get_option('wa_order_floating_hide_specific_pages');
	$cats_array  = (array) get_option('wa_order_floating_hide_product_cats');
	$tags_array  = (array) get_option('wa_order_floating_hide_product_tags');

	// Early exit if no conditions are set
	if (empty($posts_array) && empty($pages_array) && empty($cats_array) && empty($tags_array)) {
		return;
	}

	$should_hide = false;

	// Check conditions to hide the floating button
	if (is_product()) {
		$product = wc_get_product($post->ID);
		if (!is_null($product)) {
			if (!empty($cats_array) && has_term($cats_array, 'product_cat', $post->ID)) {
				$should_hide = true;
			}
			if (!empty($tags_array) && has_term($tags_array, 'product_tag', $post->ID)) {
				$should_hide = true;
			}
		}
	} elseif (is_page() && !empty($pages_array) && in_array($post->ID, $pages_array)) {
		$should_hide = true;
	} elseif (is_single() && !empty($posts_array) && in_array($post->ID, $posts_array)) {
		$should_hide = true;
	}

	// Apply the style if needed
	if ($should_hide) {
		echo '<style>.floating_button { display: none !important; }</style>';
	}
}
add_action('wp_head', 'wa_order_hide_floating_button_conditionally');


// Hide floating button on all posts & pages
function wa_order_hide_floating_button_posts_pages()
{
	$hide_on_posts = get_option('wa_order_floating_hide_all_single_posts') === 'yes';
	$hide_on_pages = get_option('wa_order_floating_hide_all_single_pages') === 'yes';

	if (($hide_on_posts && is_single()) || ($hide_on_pages && is_page())) {
		echo '<style>.floating_button { display: none !important; }</style>';
	}
}
add_action('wp_head', 'wa_order_hide_floating_button_posts_pages');
