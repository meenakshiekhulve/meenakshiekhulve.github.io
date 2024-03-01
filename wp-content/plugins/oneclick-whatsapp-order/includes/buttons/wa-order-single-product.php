<?php
// Default button position single product page
$wa_order_position = get_option('wa_order_single_product_button_position', 'after_atc');

// Start processing the WhatsApp button
// Revamped in version 1.0.5
function wa_order_add_button_plugin()
{
	// Check if the button should be displayed
	if (get_option('wa_order_option_enable_single_product') !== 'yes') {
		return;
	}

	global $product, $wa_base;

	// Fetch phone number
	$phone = wa_order_get_phone_number($product->get_id());
	if (!$phone) {
		return; // Exit if no phone number is set
	}

	// Product details
	$product_url = get_permalink($product->get_id());
	$title = $product->get_name();
	$price = wc_price(wc_get_price_including_tax($product));
	$format_price = wp_strip_all_tags($price); // Strip HTML tags

	// Decode HTML entities in the price
	$decoded_price = html_entity_decode($format_price, ENT_QUOTES, 'UTF-8');

	// Settings
	$button_text = get_post_meta($product->get_id(), '_wa_order_button_text', true) ?: get_option('wa_order_option_text_button', 'Buy via WhatsApp');
	$target = get_option('wa_order_option_target', '_blank');
	$gdpr_status = get_option('wa_order_gdpr_status_enable', 'no');
	$gdpr_message = do_shortcode(stripslashes(get_option('wa_order_gdpr_message')));

	// URL Encoding
	$custom_message = urlencode(get_option('wa_order_option_message', 'Hello, I want to buy:'));
	$encoded_title = urlencode($title);
	$encoded_product_url = urlencode($product_url);
	$encoded_price_label = urlencode(get_option('wa_order_option_price_label', 'Price'));
	$encoded_url_label = urlencode(get_option('wa_order_option_url_label', 'URL'));
	$encoded_thanks = urlencode(get_option('wa_order_option_thank_you_label', 'Thank you!'));
	$encoded_price = urlencode($decoded_price);

	// Exclude price from the message if the option is checked
	$exclude_price = get_option('wa_order_exclude_price', 'no');
	$message_content = $custom_message . "%0D%0A%0D%0A*$encoded_title*";
	if ($exclude_price !== 'yes') {
		$message_content .= "%0D%0A*$encoded_price_label:* $encoded_price";
	}
	if (get_option('wa_order_exclude_product_url') !== 'yes') {
		$message_content .= "%0D%0A*$encoded_url_label:* $encoded_product_url";
	}
	$message_content .= "%0D%0A$encoded_thanks";

	// WhatsApp URL
	$button_url = "https://$wa_base.whatsapp.com/send?phone=$phone&text=$message_content";

	// Button HTML
	$button_html = "<a href=\"$button_url\" class=\"wa-order-class\" role=\"button\" target=\"$target\"><button type=\"button\" class=\"wa-order-button single_add_to_cart_button button alt\">$button_text</button></a>";

	// GDPR compliance
	if ($gdpr_status === 'yes') {
		$gdpr_script = "
        <script>
            function WAOrder() {
                var phone = '" . esc_js($phone) . "',
                    wa_message = '" . esc_js($custom_message) . "',
                    button_url = '" . esc_url($button_url) . "',
                    target = '" . esc_attr($target) . "';
            }
        </script>
        <style>
            .wa-order-button,
            .wa-order-button .wa-order-class {
                display: none !important;
            }
        </style>
    ";

		$button_position = get_option('wa_order_single_product_button_position', 'after_atc');
		$button_id = "sendbtn" . ($button_position === "after_atc" ? "2" : "");
		$button_class = "single_add_to_cart_button wa-order-button-" . ($button_position === "after_atc" ? "after-atc" : ($button_position === "under_atc" ? "under-atc" : "shortdesc"));

		$button_html = "
        $gdpr_script
        <label class=\"wa-button-gdpr2\">
            <a href=\"$button_url\" class=\"gdpr_wa_button\" role=\"button\" target=\"$target\">
                <button type=\"button\" class=\"gdpr_wa_button_input $button_class button alt\" disabled=\"disabled\" onclick=\"WAOrder();\">
                    $button_text
                </button>
            </a>
        </label>
        <div class=\"wa-order-gdprchk\">
            <input type=\"checkbox\" name=\"wa_order_gdpr_status_enable\" class=\"css-checkbox wa_order_input_check\" id=\"gdprChkbx\" />
            <label for=\"gdprChkbx\" class=\"label-gdpr\">$gdpr_message</label>
        </div>
        <script type=\"text/javascript\">
            document.getElementById('gdprChkbx').addEventListener('click', function (e) {
                var buttons = document.querySelectorAll('.gdpr_wa_button_input');
                buttons.forEach(function(button) {
                    button.disabled = !e.target.checked;
                });
            });
        </script>
    ";
	}
	echo $button_html;
}
// Determine the position of the button and add the action accordingly
$button_position = get_option('wa_order_single_product_button_position', 'after_atc');
$hook = 'woocommerce_after_add_to_cart_button'; // Default hook
switch ($button_position) {
	case 'under_atc':
		$hook = 'woocommerce_after_add_to_cart_form';
		break;
	case 'after_shortdesc':
		$hook = 'woocommerce_before_add_to_cart_form';
		break;
}
add_action($hook, 'wa_order_add_button_plugin', 5);

// Single product custom metabox
// Hide button checkbox
function wa_order_execute_metabox_value()
{
	// Check if WooCommerce is active
	if (!function_exists('is_product')) {
		return;
	}

	// Check if it's a product page
	if (!is_product()) {
		return;
	}

	// Get the current post object
	$post = get_post();

	// Check if the WhatsApp button should be hidden
	if (get_post_meta($post->ID, '_hide_wa_button', true) == 'yes') {
		// Ensure the action function exists
		if (function_exists('wa_order_add_button_plugin')) {
			remove_action('woocommerce_after_add_to_cart_button', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_after_add_to_cart_form', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_before_add_to_cart_form', 'wa_order_add_button_plugin', 5);
		}
	}
}
add_action('wp_head', 'wa_order_execute_metabox_value');


// Hide WA button based on categories & tags
add_action('wp_head', 'wa_order_hide_single_taxonomies');
function wa_order_hide_single_taxonomies()
{
	// Check if WooCommerce is active
	if (!function_exists('is_product')) {
		return;
	}

	// Only proceed if on a product page
	if (!is_product()) {
		return;
	}

	// Retrieve the current product ID
	global $post;
	$product_id = $post->ID;

	// Get the category and tag options
	$option_cats = get_option('wa_order_option_exlude_single_product_cats', []);
	$option_tags = get_option('wa_order_option_exlude_single_product_tags', []);

	// Check if the product belongs to specified categories
	if (!empty($option_cats) && has_term($option_cats, 'product_cat', $product_id)) {
		wa_order_remove_button_actions();
		return;
	}

	// Check if the product has specified tags
	if (!empty($option_tags) && has_term($option_tags, 'product_tag', $product_id)) {
		wa_order_remove_button_actions();
		return;
	}
}

function wa_order_remove_button_actions()
{
	remove_action('woocommerce_after_add_to_cart_button', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_after_add_to_cart_form', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_before_add_to_cart_form', 'wa_order_add_button_plugin', 5);
}

// Hide ATC button checkbox
add_action('woocommerce_before_single_product', 'wa_order_check_and_hide_atc_button');
function wa_order_check_and_hide_atc_button()
{
	global $product;

	if (is_a($product, 'WC_Product') && get_post_meta($product->get_id(), '_hide_atc_button', true) === 'yes') {
		// add_filter('woocommerce_is_purchasable', '__return_false');

		// Directly output CSS to hide ATC button
		add_action('wp_footer', function () {
			echo '<style>
                    .single-product button[name="add-to-cart"] {
                        display: none !important;
                    }
                  </style>';
		});
	}
}

// New way to remove Add to Cart button
add_action('wp_head', 'wa_order_remove_atc_button', 5);
function wa_order_remove_atc_button()
{
	// Ensure WooCommerce is active
	if (!class_exists('WooCommerce')) {
		return;
	}
	// Get the option and check if ATC button should be hidden
	$hide_atc_button = get_option('wa_order_option_remove_cart_btn', 'no');
	if ($hide_atc_button === 'yes') {
		// Disable purchasability
		add_filter('woocommerce_is_purchasable', '__return_false');
		// Add inline CSS to hide ATC button
		add_action('wp_footer', 'wa_order_remove_atc_button_css');
	}
}
function wa_order_remove_atc_button_css()
{
?>
	<style>
		.single_add_to_cart_button,
		.woocommerce-variation-add-to-cart button[type="submit"] {
			display: none !important;
		}
	</style>
<?php
}

// Force show Add to Cart button product metabox
add_action('wp_head', 'wa_order_force_show_atc_button', 10);
function wa_order_force_show_atc_button()
{
	// Ensure WooCommerce is active
	if (!class_exists('WooCommerce')) {
		return;
	}

	global $post;
	if (is_product()) {
		$force_show_atc = get_post_meta($post->ID, '_force_show_atc_button', true);
		if ($force_show_atc === 'yes') {
			// Re-enable purchasability if it was disabled
			add_filter('woocommerce_is_purchasable', '__return_true');

			// Remove inline CSS that hides the ATC button
			remove_action('wp_footer', 'wa_order_remove_atc_button_css');
		}
	}
}

// New way to remove elements on single product page
add_action('wp_head', 'wa_order_function_remove_elements');
function wa_order_function_remove_elements()
{
	$hide_price = get_option('wa_order_option_remove_price');
	$hide_button = get_option('wa_order_option_remove_btn');
	$hide_button_mobile = get_option('wa_order_option_remove_btn_mobile');

	// Collect CSS rules in a variable
	$custom_css = "";

	if ($hide_price === 'yes') {
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
		$custom_css .= "
            .product-summary .woocommerce-Price-amount,
            .product-summary p.price {
                display: none !important;
            }
        ";
	}

	if ($hide_button === 'yes') {
		$custom_css .= "
            @media screen and (min-width: 768px) {
                .wa-order-button,
                .gdpr_wa_button_input,
                .wa-order-gdprchk,
                button.gdpr_wa_button_input:disabled,
                button.gdpr_wa_button_input {
                    display: none !important;
                }
            }
        ";
	}

	if ($hide_button_mobile === 'yes') {
		$custom_css .= "
            @media screen and (max-width: 768px) {
                .wa-order-button,
                .gdpr_wa_button_input,
                .wa-order-gdprchk,
                button.gdpr_wa_button_input:disabled,
                button.gdpr_wa_button_input {
                    display: none !important;
                }
            }
        ";
	}

	// Echo the CSS if it's not empty
	if (!empty($custom_css)) {
		echo "<style>" . $custom_css . "</style>";
	}
}
