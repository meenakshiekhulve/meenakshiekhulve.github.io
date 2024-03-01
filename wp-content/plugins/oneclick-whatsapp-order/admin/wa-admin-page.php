<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * @package     OneClick Chat to Order
 * @author      Walter Pinem
 * @link        https://walterpinem.me
 * @link        https://www.seniberpikir.com/oneclick-wa-order-woocommerce/
 * @copyright   Copyright (c) 2019, Walter Pinem, Seni Berpikir
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Admin Page
 */

// WA Number Post Type Submenu
function wa_order_add_number_submenu()
{
    add_submenu_page('wa-order', 'OneClick Chat to Order Options', 'Global Settings', 'manage_options', 'admin.php?page=wa-order&tab=welcome');
    add_submenu_page('wa-order', 'WhatsApp Numbers', 'WhatsApp Numbers', 'manage_options', 'edit.php?post_type=wa-order-numbers');
    add_submenu_page('wa-order', 'Add Number', 'Add New Number', 'manage_options', 'post-new.php?post_type=wa-order-numbers');
};
add_action('admin_menu', 'wa_order_add_number_submenu');
// Build plugin admin setting page
function wa_order_add_admin_page()
{
    // Generate Chat to Order Admin Page
    add_menu_page('OneClick Chat to Order Options', 'Chat to Order', 'manage_options', 'wa-order', 'wa_order_create_admin_page', plugin_dir_url(dirname(__FILE__)) . '/assets/images/wa-icon.svg', 98);
    // Begin building
    add_action('admin_init', 'wa_order_register_settings');
}
add_action('admin_menu', 'wa_order_add_admin_page');
// Array mapping for better customizations
// Since version 1.0.5
function wa_order_register_settings()
{
    $settings = wa_order_get_settings();
    foreach ($settings as $group => $group_settings) {
        foreach ($group_settings as $setting_name => $sanitization_callback) {
            register_setting($group, $setting_name, $sanitization_callback);
        }
    }
}
// Sanitization callback that can handle arrays of data properly
// Since version 1.0.5
function wa_order_sanitize_array($input)
{
    if (is_array($input)) {
        return array_map('sanitize_text_field', $input);
    }
    return [];
}
// Sanitization callback function for WP color picker
// Since version 1.0.5
function wa_order_sanitize_rgba_color($color)
{
    // Handling RGBA color format
    if (preg_match('/^rgba\(\d{1,3},\s?\d{1,3},\s?\d{1,3},\s?(0|1|0?\.\d+)\)$/', trim($color))) {
        return $color;
    }
    // Handling RGB color format (convert to HEX)
    elseif (preg_match('/^rgb\(\d{1,3},\s?\d{1,3},\s?\d{1,3}\)$/', trim($color))) {
        return wa_order_rgb_to_hex($color);
    }
    // Handling HEX color format
    return sanitize_hex_color($color);
}
function wa_order_rgb_to_hex($rgb)
{
    // Convert RGB to HEX color format
    list($r, $g, $b) = sscanf($rgb, "rgb(%d, %d, %d)");
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

// Revamped admin settings registrations
// Since version 1.0.5
function wa_order_get_settings()
{
    return [
        /*
        ******************************************** Basic tab options ****************************************
        */
        'wa-order-settings-group-button-config' => [
            'wa_order_selected_wa_number_single_product' => 'sanitize_text_field',
            'wa_order_option_dismiss_notice_confirmation' => 'sanitize_checkbox',
            // 'wa_order_whatsapp_base_url' => 'sanitize_text_field', Removed in 1.0.5 version
            'wa_order_single_product_button_position' => 'sanitize_text_field',
            'wa_order_option_enable_single_product' => 'sanitize_checkbox',
            'wa_order_option_message' => 'sanitize_textarea_field',
            'wa_order_option_text_button' => 'sanitize_text_field',
            'wa_order_option_target' => 'sanitize_checkbox',
            'wa_order_exclude_price' => 'sanitize_checkbox',
            'wa_order_exclude_product_url' => 'sanitize_checkbox',
            'wa_order_option_quantity_label' => 'sanitize_text_field',
            'wa_order_option_price_label' => 'sanitize_text_field',
            'wa_order_option_url_label' => 'sanitize_text_field',
            'wa_order_option_total_amount_label' => 'sanitize_text_field',
            'wa_order_option_payment_method_label' => 'sanitize_text_field',
            'wa_order_option_thank_you_label' => 'sanitize_text_field',
        ],
        /*
        ******************************************** Display tab options ****************************************
        */
        'wa-order-settings-group-display-options' => [
            'wa_order_bg_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_bg_hover_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_txt_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_txt_hover_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_btn_box_shdw' => 'wa_order_sanitize_rgba_color',
            'wa_order_bshdw_horizontal' => 'sanitize_text_field',
            'wa_order_bshdw_vertical' => 'sanitize_text_field',
            'wa_order_bshdw_blur' => 'sanitize_text_field',
            'wa_order_bshdw_spread' => 'sanitize_text_field',
            'wa_order_bshdw_position' => 'sanitize_text_field',
            'wa_order_btn_box_shdw_hover' => 'wa_order_sanitize_rgba_color',
            'wa_order_bshdw_horizontal_hover' => 'sanitize_text_field',
            'wa_order_bshdw_vertical_hover' => 'sanitize_text_field',
            'wa_order_bshdw_blur_hover' => 'sanitize_text_field',
            'wa_order_bshdw_spread_hover' => 'sanitize_text_field',
            'wa_order_bshdw_position_hover' => 'sanitize_text_field',
            'wa_order_option_remove_btn' => 'sanitize_checkbox',
            'wa_order_option_remove_btn_mobile' => 'sanitize_checkbox',
            'wa_order_option_remove_price' => 'sanitize_checkbox',
            'wa_order_option_remove_cart_btn' => 'sanitize_checkbox',
            'wa_order_option_remove_quantity' => 'sanitize_checkbox',
            'wa_order_option_exlude_single_product_cats' => 'wa_order_sanitize_array',
            'wa_order_option_exlude_single_product_tags' => 'wa_order_sanitize_array',
            'wa_order_single_button_margin_top' => 'sanitize_text_field',
            'wa_order_single_button_margin_right' => 'sanitize_text_field',
            'wa_order_single_button_margin_bottom' => 'sanitize_text_field',
            'wa_order_single_button_margin_left' => 'sanitize_text_field',
            'wa_order_single_button_padding_top' => 'sanitize_text_field',
            'wa_order_single_button_padding_right' => 'sanitize_text_field',
            'wa_order_single_button_padding_bottom' => 'sanitize_text_field',
            'wa_order_single_button_padding_left' => 'sanitize_text_field',
            'wa_order_display_option_shop_loop_hide_desktop' => 'sanitize_checkbox',
            'wa_order_display_option_shop_loop_hide_mobile' => 'sanitize_checkbox',
            'wa_order_option_exlude_shop_product_cats' => 'wa_order_sanitize_array',
            'wa_order_exlude_shop_product_cats_archive' => 'sanitize_checkbox',
            'wa_order_option_exlude_shop_product_tags' => 'wa_order_sanitize_array',
            'wa_order_exlude_shop_product_tags_archive' => 'sanitize_checkbox',
            'wa_order_display_option_cart_hide_desktop' => 'sanitize_checkbox',
            'wa_order_display_option_cart_hide_mobile' => 'sanitize_checkbox',
            'wa_order_display_option_checkout_hide_desktop' => 'sanitize_checkbox',
            'wa_order_display_option_checkout_hide_mobile' => 'sanitize_checkbox',
            'wa_order_option_convert_phone_order_details' => 'sanitize_checkbox'
        ],
        /*
    ******************************************** GDPR tab options ****************************************
    */
        'wa-order-settings-group-gdpr' => [
            'wa_order_gdpr_status_enable' => 'sanitize_checkbox',
            'wa_order_gdpr_message' => 'sanitize_textarea_field',
            'wa_order_gdpr_privacy_page' => 'sanitize_text_field'
        ],
        /*
    ******************************************** Floating Button tab options ****************************************
    */
        'wa-order-settings-group-floating' => [
            'wa_order_selected_wa_number_floating' => 'sanitize_text_field',
            'wa_order_floating_button' => 'sanitize_checkbox',
            'wa_order_floating_button_position' => 'sanitize_text_field',
            'wa_order_floating_message' => 'sanitize_textarea_field',
            'wa_order_floating_target' => 'sanitize_checkbox',
            'wa_order_floating_tooltip_enable' => 'sanitize_checkbox',
            'wa_order_floating_tooltip' => 'sanitize_text_field',
            'wa_order_floating_hide_mobile' => 'sanitize_checkbox',
            'wa_order_floating_hide_desktop' => 'sanitize_checkbox',
            'wa_order_floating_source_url' => 'sanitize_checkbox',
            'wa_order_floating_source_url_label' => 'sanitize_text_field',
            'wa_order_floating_hide_all_single_posts' => 'sanitize_text_field',
            'wa_order_floating_hide_all_single_pages' => 'sanitize_text_field',
            'wa_order_floating_hide_specific_posts' => 'wa_order_sanitize_array',
            'wa_order_floating_hide_specific_pages' => 'wa_order_sanitize_array',
            'wa_order_floating_hide_product_cats' => 'wa_order_sanitize_array',
            'wa_order_floating_hide_product_tags' => 'wa_order_sanitize_array'
        ],
        /*
    ******************************************** Shortcode tab options ****************************************
    */
        'wa-order-settings-group-shortcode' => [
            'wa_order_selected_wa_number_shortcode' => 'sanitize_text_field',
            'wa_order_shortcode_message' => 'sanitize_textarea_field',
            'wa_order_shortcode_text_button' => 'sanitize_text_field',
            'wa_order_shortcode_target' => 'sanitize_checkbox'
        ],
        /*
    ******************************************** Cart page tab options ****************************************
    */
        'wa-order-settings-group-cart-options' => [
            'wa_order_selected_wa_number_cart' => 'sanitize_text_field',
            'wa_order_option_add_button_to_cart' => 'sanitize_checkbox',
            'wa_order_option_cart_custom_message' => 'sanitize_textarea_field',
            'wa_order_option_cart_button_text' => 'sanitize_text_field',
            'wa_order_option_cart_hide_checkout' => 'sanitize_checkbox',
            'wa_order_option_cart_hide_product_url' => 'sanitize_checkbox',
            'wa_order_option_cart_open_new_tab' => 'sanitize_checkbox',
            'wa_order_option_cart_enable_variations' => 'sanitize_checkbox'
        ],
        /*
    ******************************************** Thank You page tab options ****************************************
    */
        'wa-order-settings-group-order-completion' => [
            'wa_order_selected_wa_number_thanks' => 'sanitize_text_field',
            'wa_order_option_thank_you_redirect_checkout' => 'sanitize_checkbox',
            'wa_order_option_enable_button_thank_you' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_title' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_subtitle' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_button_text' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_custom_message' => 'sanitize_textarea_field',
            'wa_order_option_custom_thank_you_include_order_date' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_order_number' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_order_number_label' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_open_new_tab' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_customer_details_label' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_include_sku' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_inclue_coupon' => 'sanitize_checkbox',
            'wa_order_option_custom_thank_you_coupon_label' => 'sanitize_text_field'
        ],
        /*
    ******************************************** Shop page tab options ****************************************
    */
        'wa-order-settings-group-shop-loop' => [
            'wa_order_selected_wa_number_shop' => 'sanitize_text_field',
            'wa_order_option_enable_button_shop_loop' => 'sanitize_checkbox',
            'wa_order_option_hide_atc_shop_loop' => 'sanitize_checkbox',
            'wa_order_option_button_text_shop_loop' => 'sanitize_text_field',
            'wa_order_option_custom_message_shop_loop' => 'sanitize_textarea_field',
            'wa_order_option_shop_loop_hide_product_url' => 'sanitize_checkbox',
            'wa_order_option_shop_loop_exclude_price' => 'sanitize_checkbox',
            'wa_order_option_shop_loop_open_new_tab' => 'sanitize_checkbox'
        ],
    ];
}
// Delete option upon deactivation
function wa_order_deactivation()
{
    // delete_option( 'wa_order_option_phone_number' ); // Old phone number option
    delete_option('wa_order_selected_wa_number'); // New phone number option
    delete_option('wa_order_option_dismiss_notice_confirmation');
    delete_option('wa_order_whatsapp_base_url'); // Removed 'wa_order_whatsapp_base_url' in 1.0.5 version
    delete_option('wa_order_single_product_button_position');
    delete_option('wa_order_option_enable_single_product');
    delete_option('wa_order_option_message');
    delete_option('wa_order_option_text_button');
    delete_option('wa_order_option_target');
    delete_option('wa_order_exclude_product_url');
    delete_option('wa_order_option_remove_btn');
    delete_option('wa_order_option_remove_btn_mobile');
    delete_option('wa_order_option_remove_price');
    delete_option('wa_order_option_remove_cart_btn');
    delete_option('wa_order_option_remove_quantity');
    delete_option('wa_order_option_exlude_single_product_cats');
    delete_option('wa_order_option_exlude_single_product_tags');
    delete_option('wa_order_single_button_margin_top');
    delete_option('wa_order_single_button_margin_right');
    delete_option('wa_order_single_button_margin_bottom');
    delete_option('wa_order_single_button_margin_left');
    delete_option('wa_order_single_button_padding_top');
    delete_option('wa_order_single_button_padding_right');
    delete_option('wa_order_single_button_padding_bottom');
    delete_option('wa_order_single_button_padding_left');
    delete_option('wa_order_exlude_shop_product_cats_archive');
    delete_option('wa_order_exlude_shop_product_tags_archive');
    delete_option('wa_order_display_option_shop_loop_hide_desktop');
    delete_option('wa_order_display_option_shop_loop_hide_mobile');
    delete_option('wa_order_btn_box_shdw');
    delete_option('wa_order_bshdw_horizontal');
    delete_option('wa_order_bshdw_vertical');
    delete_option('wa_order_bshdw_blur');
    delete_option('wa_order_bshdw_spread');
    delete_option('wa_order_bshdw_position');
    delete_option('wa_order_option_exlude_shop_product_cats');
    delete_option('wa_order_option_exlude_shop_product_tags');
    delete_option('wa_order_display_option_cart_hide_desktop');
    delete_option('wa_order_display_option_cart_hide_mobile');
    delete_option('wa_order_display_option_checkout_hide_desktop');
    delete_option('wa_order_display_option_checkout_hide_mobile');
    delete_option('wa_order_option_convert_phone_order_details');
    delete_option('wa_order_gdpr_status_enable');
    delete_option('wa_order_gdpr_message');
    delete_option('wa_order_gdpr_privacy_page');
    delete_option('wa_order_floating_button');
    delete_option('wa_order_floating_button_position');
    delete_option('wa_order_floating_message');
    delete_option('wa_order_floating_target');
    delete_option('wa_order_floating_tooltip_enable');
    delete_option('wa_order_floating_tooltip');
    delete_option('wa_order_floating_hide_mobile');
    delete_option('wa_order_floating_hide_desktop');
    delete_option('wa_order_floating_source_url');
    delete_option('wa_order_floating_source_url_label');
    delete_option('wa_order_floating_hide_all_single_posts');
    delete_option('wa_order_floating_hide_all_single_pages');
    delete_option('wa_order_floating_hide_specific_posts');
    delete_option('wa_order_floating_hide_specific_pages');
    delete_option('wa_order_floating_hide_product_cats');
    delete_option('wa_order_floating_hide_product_tags');
    delete_option('wa_order_shortcode_message');
    delete_option('wa_order_shortcode_text_button');
    delete_option('wa_order_shortcode_target');
    delete_option('wa_order_option_add_button_to_cart');
    delete_option('wa_order_option_cart_custom_message');
    delete_option('wa_order_option_cart_button_text');
    delete_option('wa_order_option_cart_hide_checkout');
    delete_option('wa_order_option_cart_hide_product_url');
    delete_option('wa_order_option_cart_open_new_tab');
    delete_option('wa_order_option_cart_enable_variations');
    delete_option('wa_order_option_quantity_label');
    delete_option('wa_order_option_price_label');
    delete_option('wa_order_option_url_label');
    delete_option('wa_order_option_total_amount_label');
    delete_option('wa_order_option_payment_method_label');
    delete_option('wa_order_option_thank_you_label');
    delete_option('wa_order_option_thank_you_redirect_checkout');
    delete_option('wa_order_option_enable_button_thank_you');
    delete_option('wa_order_option_custom_thank_you_title');
    delete_option('wa_order_option_custom_thank_you_subtitle');
    delete_option('wa_order_option_custom_thank_you_button_text');
    delete_option('wa_order_option_custom_thank_you_custom_message');
    delete_option('wa_order_option_custom_thank_you_include_order_date');
    delete_option('wa_order_option_custom_thank_you_order_number');
    delete_option('wa_order_option_custom_thank_you_order_number_label');
    delete_option('wa_order_option_custom_thank_you_open_new_tab');
    delete_option('wa_order_option_custom_thank_you_customer_details_label');
    delete_option('wa_order_option_custom_thank_you_include_sku');
    delete_option('wa_order_option_custom_thank_you_inclue_coupon');
    delete_option('wa_order_option_custom_thank_you_coupon_label');
    delete_option('wa_order_option_enable_button_shop_loop');
    delete_option('wa_order_option_hide_atc_shop_loop');
    delete_option('wa_order_option_button_text_shop_loop');
    delete_option('wa_order_option_custom_message_shop_loop');
    delete_option('wa_order_option_shop_loop_hide_product_url');
    delete_option('wa_order_option_shop_loop_exclude_price');
    delete_option('wa_order_option_shop_loop_open_new_tab');
}
register_deactivation_hook(__FILE__, 'wa_order_deactivation');
// Begin Building the Admin Tabs
function wa_order_create_admin_page()
{
    // Define the valid tabs
    $valid_tabs = [
        'button_config', 'floating_button', 'display_option',
        'shop_page', 'cart_button', 'thanks_page',
        'gdpr_notice', 'generate_shortcode', 'tutorial_support', 'welcome'
    ];
    // Sanitize and validate the 'tab' parameter
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'welcome';
    if (!in_array($active_tab, $valid_tabs)) {
        $active_tab = 'welcome'; // default to the 'welcome' tab
    }
?>
    <div class="wrap OCWAORDER_pluginpage_title">
        <h1><?php _e('OneClick Chat to Order', 'oneclick-wa-order'); ?></h1>
        <hr>
        <h2 class="nav-tab-wrapper">
            <a href="?page=wa-order&tab=welcome" class="nav-tab <?php echo esc_attr($active_tab == 'welcome') ? 'nav-tab-active' : ''; ?>"><?php _e('Welcome', 'oneclick-wa-order'); ?></a>
            <a href="edit.php?post_type=wa-order-numbers" class="nav-tab <?php echo esc_attr($active_tab == 'phone-numbers') ? 'nav-tab-active' : ''; ?>"><?php _e('Numbers', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=button_config" class="nav-tab <?php echo esc_attr($active_tab == 'button_config') ? 'nav-tab-active' : ''; ?>"><?php _e('Basic', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=floating_button" class="nav-tab <?php echo esc_attr($active_tab == 'floating_button') ? 'nav-tab-active' : ''; ?>"><?php _e('Floating', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=display_option" class="nav-tab <?php echo esc_attr($active_tab == 'display_option') ? 'nav-tab-active' : ''; ?>"><?php _e('Display Options', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=shop_page" class="nav-tab <?php echo esc_attr($active_tab == 'shop_page') ? 'nav-tab-active' : ''; ?>"><?php _e('Shop', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=cart_button" class="nav-tab <?php echo esc_attr($active_tab == 'cart_button') ? 'nav-tab-active' : ''; ?>"><?php _e('Cart', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=thanks_page" class="nav-tab <?php echo esc_attr($active_tab == 'thanks_page') ? 'nav-tab-active' : ''; ?>"><?php _e('Checkout', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=gdpr_notice" class="nav-tab <?php echo esc_attr($active_tab == 'gdpr_notice') ? 'nav-tab-active' : ''; ?>"><?php _e('GDPR', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=generate_shortcode" class="nav-tab <?php echo esc_attr($active_tab == 'generate_shortcode') ? 'nav-tab-active' : ''; ?>"><?php _e('Shortcode', 'oneclick-wa-order'); ?></a>
            <a href="?page=wa-order&tab=tutorial_support" class="nav-tab <?php echo esc_attr($active_tab == 'tutorial_support') ? 'nav-tab-active' : ''; ?>"><?php _e('Support', 'oneclick-wa-order'); ?></a>
        </h2>
        <?php if ($active_tab == 'generate_shortcode') { ?>
            <?php wp_enqueue_script('wa_order_js_admin'); ?>
            <h2 class="section_wa_order"><?php _e('Generate Shortcode', 'oneclick-wa-order'); ?></h2>
            <p>
                <?php _e('Use shortcode to display OneClick Chat to Order\'s WhatsApp button anywhere on your site. There are two options; global and dynamic, which can be used based on your needs.', 'oneclick-wa-order'); ?>
                <br />
            </p>
            <hr />
            <h3 class="section_wa_order"><?php _e('Shortcode Generator', 'oneclick-wa-order'); ?></h3>
            <p>
                <?php _e('Create a dynamic shortcode using below generator.', 'oneclick-wa-order'); ?>
                <br />
            </p>
            <hr />
            <form>
                <!-- Shortcode Generator -->
                <table class="form-table">
                    <tbody>
                        <!-- Dropdown WA Number -->
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown_shortcode_generator(
                                    array(
                                        'name'      => 'wa_order_phone_numbers_dropdown_shortcode_generator',
                                        'selected'  => esc_attr(get_option('wa_order_selected_wa_number_shortcode')),
                                    )
                                ); ?>
                                <p class="description">
                                    <?php echo esc_html__('WhatsApp number is required. Please set it on', 'oneclick-wa-order') . ' <a href="edit.php?post_type=wa-order-numbers"><strong>' . esc_html__('Numbers', 'oneclick-wa-order') . '</strong></a> ' . esc_html__('tab.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="WAbuttonText"><b><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" id="WAbuttonText" name="WAbuttonText" onChange="generateWAshortcode();" class="wa_order_input" placeholder="<?php echo esc_attr__('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>" id="WAcustomMessage" name="WAcustomMessage" onChange="generateWAshortcode();"></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Enter custom message, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Hello, I need to know more about', 'oneclick-wa-order') . '</code>'; ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Open in New Tab? -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label for="WAnewTab"><?php echo esc_html__('Open in New Tab?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <select name="WAnewTab" id="WAnewTab" onChange="generateWAshortcode();">
                                    <option value="no"><?php echo esc_html__('No', 'oneclick-wa-order'); ?></option>
                                    <option value="yes"><?php echo esc_html__('Yes', 'oneclick-wa-order'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <!-- Copy Shortcode -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php echo esc_html__('Copy Shortcode', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea class="wa_order_input_areatext" rows="5" id="generatedShortcode" onclick="this.setSelectionRange(0, this.value.length)"></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Copy above shortcode and paste it anywhere.', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <hr />
            <!-- End - Shortcode Generator -->
            <!-- Start Global Shortcode -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-shortcode'); ?>
                <?php do_settings_sections('wa-order-settings-group-shortcode'); ?>
                <h3 class="section_wa_order"><?php echo esc_html__('Global Shortcode', 'oneclick-wa-order'); ?></h3>
                <p>
                    <?php echo esc_html__('You need to click the', 'oneclick-wa-order') . ' <b>' . esc_html__('Save Changes', 'oneclick-wa-order') . '</b> ' . esc_html__('button below in order to use the', 'oneclick-wa-order') . ' <code>[wa-order]</code> ' . esc_html__('shortcode.', 'oneclick-wa-order'); ?>
                </p>
                <table class="form-table">
                    <tbody>
                        <!-- Dropdown WA Number -->
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_shortcode',
                                        'selected'  => esc_attr(get_option('wa_order_selected_wa_number_shortcode')),
                                    )
                                ); ?>
                                <p class="description">
                                    <?php echo esc_html__('WhatsApp number is required. Please set it on', 'oneclick-wa-order') . ' <a href="edit.php?post_type=wa-order-numbers"><strong>' . esc_html__('Numbers', 'oneclick-wa-order') . '</strong></a> ' . esc_html__('tab.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_shortcode_text_button" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_shortcode_text_button')); ?>" placeholder="<?php echo esc_attr__('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_shortcode_message" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_shortcode_message')); ?></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Enter custom message, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Hello, I need to know more about', 'oneclick-wa-order') . '</code>'; ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Copy Shortcode -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_copy_label" for="wa_order_copy"><b><?php echo esc_html__('Copy Shortcode', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input style="letter-spacing: 1px;" class="wa_order_shortcode_input" onClick="this.setSelectionRange(0, this.value.length)" value="[wa-order]" readonly />
                            </td>
                        </tr>
                        <!-- Open in New Tab? -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target"><b><?php echo esc_html__('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_shortcode_target" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_shortcode_target'), '_blank'); ?>>
                                <?php echo esc_html__('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
            <!-- End - Shortcode Tab Setting Page -->
        <?php } elseif ($active_tab == 'button_config') { ?>
            <!-- Basic Configurations -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-button-config'); ?>
                <?php do_settings_sections('wa-order-settings-group-button-config'); ?>
                <!-- Basic Configuration tab -->
                <h2 class="section_wa_order"><?php _e('Confirmation', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php _e('Make sure that you have added at least one WhatsApp number to dismiss the admin notice. Please <a href="edit.php?post_type=wa-order-numbers"><strong>set it here</strong></a> to get started. <a href="https://walterpinem.me/projects/oneclick-chat-to-order-mutiple-numbers-feature/?utm_source=admin-notice&utm_medium=admin-dashboard&utm_campaign=OneClick-Chat-to-Order" target="_blank"><strong>Learn more</strong></a>.', 'oneclick-wa-order'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Dismiss Notice', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_dismiss_notice_confirmation" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_dismiss_notice_confirmation'), 'yes'); ?>>
                                <?php _e('Check this if you have added at least one WhatsApp number.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php /** Removed in 1.0.5 version
                <h2 class="section_wa_order"><?php _e('WhatsApp Base URL', 'oneclick-wa-order'); ?></h2>
                <p class="description">
                    <?php _e('Just in case, if the WhatsApp link cannot be opened in a mobile device, you can choose <code>api</code> instead of <code>web</code> for desktop (default is <code>web</code> for desktop and <code>api</code> for mobile).', 'oneclick-wa-order'); ?>
                </p>
                <hr>
                <table class="form-table">
                    <tbody>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn">
                                    <strong><?php _e('Choose Base URL', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <select name="wa_order_whatsapp_base_url" id="wa_order_whatsapp_base_url" class="wa_order-admin-select2">
                                    <option value="web" <?php selected(get_option('wa_order_whatsapp_base_url'), 'web'); ?>><?php _e('web (default)', 'oneclick-wa-order'); ?></option>
                                    <option value="api" <?php selected(get_option('wa_order_whatsapp_base_url'), 'api'); ?>><?php _e('api', 'oneclick-wa-order'); ?></option>
                                </select>
                                <p class="description">
                                    <?php _e('It\'s only applicable for desktop.', 'oneclick-wa-order'); ?>
                                </p>
                                <br>
                            </td>
                        <tr>
                    </tbody>
                </table>
                <hr>  */ ?>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php _e('Single Product Page', 'oneclick-wa-order'); ?></h2>
                        <p>
                            <?php _e('These configurations will be only effective on single product page.', 'oneclick-wa-order'); ?>
                            <br />
                        </p>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Display Button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_enable_single_product" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_enable_single_product'), 'yes'); ?>>
                                <?php _e('This will display WhatsApp button on single product page', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- Dropdown WA Number -->
                        <tr>
                            <th scope="row">
                                <label>
                                    <?php _e('WhatsApp Number', 'oneclick-wa-order') ?>
                                </label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_single_product',
                                        'selected'  => (get_option('wa_order_selected_wa_number_single_product')),
                                    )
                                )
                                ?>
                                <p class="description">
                                    <?php _e('WhatsApp number is <strong style="color:red;">required</strong>. Please set it on <a href="edit.php?post_type=wa-order-numbers"><strong>Numbers</strong></a> tab.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- END - Dropdown WA Number -->
                        <!-- Dropdown Button Position -->
                        <tr>
                            <th scope="row">
                                <label for="wa_order_single_product_button_position"><?php echo __('Button Position', 'oneclick-wa-order') ?></label>
                            </th>
                            <td>
                                <select name="wa_order_single_product_button_position" id="wa_order_single_product_button_position" class="wa_order-admin-select2">
                                    <option value="after_atc" <?php selected(get_option('wa_order_single_product_button_position'), 'after_atc'); ?>><?php _e('After Add to Cart Button (Default)', 'oneclick-wa-order'); ?></option>
                                    <option value="under_atc" <?php selected(get_option('wa_order_single_product_button_position'), 'under_atc'); ?>><?php _e('Under Add to Cart Button', 'oneclick-wa-order'); ?></option>
                                    <option value="after_shortdesc" <?php selected(get_option('wa_order_single_product_button_position'), 'after_shortdesc'); ?>><?php _e('After Short Description', 'oneclick-wa-order'); ?></option>
                                </select>
                                <p class="description">
                                    <?php _e('Choose where to put the WhatsApp button on single product page.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- END - Dropdown Button Position -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_owo"><b><?php _e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_message" class="wa_order_input_areatext" rows="5" placeholder="<?php _e('e.g. Hello, I want to buy:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_message')); ?></textarea>
                                <p class="description">
                                    <?php _e('Fill this form with custom message, e.g. <code>Hello, I want to buy:</code>', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php _e('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_text_button" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_text_button')); ?>" placeholder="<?php _e('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target"><b><?php _e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_target" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_option_target'), '_blank'); ?>>
                                <?php _e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php _e('Exclusion', 'oneclick-wa-order'); ?></h2>
                        <p><?php _e('The following option is only for the output message you\'ll receieve on WhatsApp. To hide some elements, please go to <a href="admin.php?page=wa-order&tab=display_option"><strong>Display Options</strong></a> tab.', 'oneclick-wa-order'); ?></p>
                        <tr class="wa_order_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_price"><b><?php _e('Exclude Price?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_exclude_price" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exclude_price'), 'yes'); ?>>
                                <?php _e('Yes, exclude price in WhatsApp message.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_price"><b><?php _e('Remove Product URL?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_exclude_product_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exclude_product_url'), 'yes'); ?>>
                                <?php _e('This will remove product URL from WhatsApp message sent from single product page.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php _e('Text Translations', 'oneclick-wa-order'); ?></h2>
                        <p><?php _e('You can translate the following strings which will be included in the sent message. By default, the labels are used in the message. You can translate or change them below accordingly.', 'oneclick-wa-order'); ?></p>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php _e('Quantity', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_quantity_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_quantity_label', 'Quantity')); ?>" placeholder="<?php _e('e.g. Quantity', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php _e('Price', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_price_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_price_label', 'Price')); ?>" placeholder="<?php _e('e.g. Price', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php _e('URL', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_url_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_url_label', 'URL')); ?>" placeholder="<?php _e('e.g. Link', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php _e('Total Amount', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_total_amount_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_total_amount_label', 'Total Price')); ?>" placeholder="<?php _e('e.g. Total Amount', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php _e('Payment Method', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_payment_method_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_payment_method_label', 'Payment Method')); ?>" placeholder="<?php _e('e.g. Payment via', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php _e('Thank you!', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_thank_you_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_thank_you_label', 'Thank you!')); ?>" placeholder="<?php _e('e.g. Thank you in advance!', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'floating_button') { ?>
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-floating'); ?>
                <?php do_settings_sections('wa-order-settings-group-floating'); ?>
                <!-- Floating Button -->
                <h2 class="section_wa_order"><?php _e('Floating Button', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php _e('Enable / disable a floating WhatsApp button on your entire pages. You can configure the floating button below.', 'oneclick-wa-order'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Display Floating Button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_button" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_button'), 'yes'); ?>>
                                <?php _e('This will show floating WhatsApp Button', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- Dropdown WA Number -->
                        <tr>
                            <th scope="row">
                                <label>
                                    <?php _e('WhatsApp Number', 'oneclick-wa-order') ?>
                                </label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_floating',
                                        'selected'  => (get_option('wa_order_selected_wa_number_floating')),
                                    )
                                )
                                ?>
                                <p class="description">
                                    <?php _e('WhatsApp number is <strong style="color:red;">required</strong>. Please set it on <a href="edit.php?post_type=wa-order-numbers"><strong>Numbers</strong></a> tab.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- END- Dropdown WA Number -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php _e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_floating_message" class="wa_order_input_areatext" rows="5" placeholder="<?php _e('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_floating_message')); ?></textarea>
                                <p class="description">
                                    <?php _e('Enter custom message, e.g. <code>Hello, I need to know more about</code>', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target"><b><?php _e('Show Source Page URL?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_source_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_source_url'), 'yes'); ?>>
                                <?php _e('This will include the URL of the page where the button is clicked in the message.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="wa_order_floating_source_url_label"><b><?php _e('Source Page URL Label', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_floating_source_url_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_floating_source_url_label')); ?>" placeholder="<?php _e('From URL:', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php _e('Add a label for the source page URL. <code>e.g. From URL:</code>', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target"><b><?php _e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_target" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_floating_target'), '_blank'); ?>>
                                <?php _e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Floating Button Display Options -->
                <table class="form-table">
                    <tbody>
                        <hr />
                        <h2 class="section_wa_order"><?php _e('Display Options', 'oneclick-wa-order'); ?></h2>
                        <p>
                            <?php _e('Configure where and how you\'d like the floating button to be displayed..', 'oneclick-wa-order'); ?>
                            <br />
                        </p>
                        <hr />
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label>
                                    <?php _e('Floating Button Position', 'oneclick-wa-order') ?>
                                </label>
                            </th>
                            <td>
                                <input type="radio" name="wa_order_floating_button_position" value="left" <?php checked('left', get_option('wa_order_floating_button_position'), true); ?>> <?php _e('Left', 'oneclick-wa-order'); ?>
                                <input type="radio" name="wa_order_floating_button_position" value="right" <?php checked('right', get_option('wa_order_floating_button_position'), true); ?>> <?php _e('Right', 'oneclick-wa-order'); ?>
                                <?php _e('Right', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn">
                                    <strong><?php _e('Display Tooltip?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_tooltip_enable" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_tooltip_enable'), 'yes'); ?>>
                                <?php _e('This will show a custom tooltip', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="floating_tooltip">
                                    <strong><?php _e('Button Tooltip', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_floating_tooltip" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_floating_tooltip')); ?>" placeholder="<?php _e('e.g. Let\'s Chat', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php _e('Use this to greet your customers. The tooltip container size is very <br>limited so make sure to make it as short as possible.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target">
                                    <strong><?php _e('Hide Floating Button on Mobile?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_mobile'), 'yes'); ?>>
                                <?php _e('This will hide Floating Button on Mobile.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target">
                                    <strong><?php _e('Hide Floating Button on Desktop?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_desktop'), 'yes'); ?>>
                                <?php _e('This will hide Floating Button on Desktop.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- Hide floating button on all posts -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target">
                                    <strong><?php _e('Hide Floating Button on All Single Posts?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_hide_all_single_posts" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_all_single_posts'), 'yes'); ?>>
                                <?php _e('This will hide Floating Button on all single posts.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- END - Hide floating button on all posts -->
                        <!-- Hide floating button on all pages -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_target_label" for="wa_order_target">
                                    <strong><?php _e('Hide Floating Button on All Single Posts?', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_floating_hide_all_single_pages" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_all_single_pages'), 'yes'); ?>>
                                <?php _e('This will hide Floating Button on all single pages.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- END - Hide floating button on all pages -->
                        <!-- Multiple posts selection -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn">
                                    <strong><?php _e('Hide Floating Button on Selected Post(s)', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <?php wp_enqueue_script('wa_order_js_select2'); ?>
                                <?php wp_enqueue_script('wa_order_js_admin'); ?>
                                <?php wp_enqueue_style('wa_order_selet2_style'); ?>
                                <select multiple="multiple" name="wa_order_floating_hide_specific_posts[]" class="postform octo-post-filter" style="width: 50%;">
                                    <?php
                                    global $post;
                                    $option = get_option('wa_order_floating_hide_specific_posts');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'post_type'        => 'post',
                                        'orderby'          => 'title',
                                        'order'            => 'ASC',
                                        'post_status'      => 'publish',
                                        'posts_per_page'   => -1
                                    );
                                    $posts = get_posts($args);
                                    foreach ($posts as $post) {
                                        $selected = in_array($post->ID, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($post->ID); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html(ucwords($post->post_title)); ?>
                                        </option>
                                    <?php
                                    } //endforeach
                                    ?>
                                </select>
                                <p><?php _e('You can hide the floating button on the selected post(s).', 'oneclick-wa-order'); ?></p><br>
                            </td>
                        </tr>
                        <!-- END - Multiple posts selection -->
                        <!-- Multiple pages selection -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn">
                                    <strong><?php _e('Hide Floating Button on Selected Page(s)', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_floating_hide_specific_pages[]" class="postform octo-page-filter" style="width: 50%;">
                                    <?php
                                    global $post;
                                    $option = get_option('wa_order_floating_hide_specific_pages');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'post_type'        => 'page',
                                        'orderby'          => 'title',
                                        'order'            => 'ASC',
                                        'post_status'      => 'publish',
                                        'posts_per_page'   => -1
                                    );
                                    $pages = get_posts($args);
                                    foreach ($pages as $page) {
                                        $selected = in_array($page->ID, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($page->ID); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html(ucwords($page->post_title)); ?>
                                        </option>
                                    <?php
                                    } //endforeach
                                    ?>
                                </select>
                                <p><?php _e('You can hide the floating button on the selected page(s).', 'oneclick-wa-order'); ?></p><br>
                            </td>
                        </tr>
                        <!-- END - Multiple pages selection -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn">
                                    <strong><?php _e('Hide Floating Button on Products in Categories', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_floating_hide_product_cats[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_floating_hide_product_cats');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'taxonomy' => 'product_cat',
                                        'orderby'  => 'name'
                                    );
                                    $categories = get_categories($args);
                                    foreach ($categories as $category) {
                                        $selected = in_array($category->term_id, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html(ucwords($category->cat_name)) . ' (' . esc_html($category->category_count) . ')'; ?>
                                        </option>
                                    <?php
                                    } //endforeach
                                    ?>
                                </select>
                                <p><?php _e('You can hide the floating button on products in the selected categories.', 'oneclick-wa-order'); ?></p>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide Floating Button on Products in Tags', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_floating_hide_product_tags[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_floating_hide_product_tags');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'taxonomy' => 'product_tag',
                                        'orderby'  => 'name'
                                    );
                                    $tag_query = get_terms($args);
                                    foreach ($tag_query as $term) {
                                        $selected = in_array($term->term_id, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($term->term_id); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html(ucwords($term->name)) . ' (' . esc_html($term->count) . ')'; ?>
                                        </option>
                                    <?php
                                    } //endforeach
                                    ?>
                                </select>
                                <p>
                                    <?php _e('You can hide the floating button on products in the selected tags.', 'oneclick-wa-order');
                                    ?>
                                    <br />
                                </p>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END - Floating Button Display Options -->
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'display_option') { ?>
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-display-options'); ?>
                <?php do_settings_sections('wa-order-settings-group-display-options'); ?>
                <?php wp_enqueue_script('wa_order_js_select2'); ?>
                <?php wp_enqueue_style('wp-color-picker'); ?>
                <?php wp_enqueue_style('wa_order_selet2_style'); ?>
                <?php wp_enqueue_script('wp-color-picker-alpha'); ?>
                <?php wp_enqueue_script('wp-color-picker-init'); ?>
                <?php wp_enqueue_script('wa_order_js_admin'); ?>
                <h2 class="section_wa_order"><?php _e('Display Options', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php _e('Here, you can configure some options for hiding elements to convert customers phone number into clickable WhatsApp link.', 'oneclick-wa-order'); ?>
                    <br />
                </p>
                <hr>
                <!-- Button Colors - Display Options -->
                <table class="form-table">
                    <tbody>
                        <h3 class="section_wa_order"><?php _e('Button Colors', 'oneclick-wa-order'); ?></h3>
                        <p><?php _e('Customize the WhatsApp button appearance however you like.', 'oneclick-wa-order'); ?></p>
                        <!-- Button Background Color -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Background Color', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <?php
                                $bg = get_option('wa_order_bg_color');
                                if (empty($bg)) {
                                    $bg = 'rgba(37, 211, 102, 1)';
                                }
                                ?>
                                <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(37, 211, 102, 1)" name="wa_order_bg_color" value="<?php echo esc_attr($bg); ?>" />
                            </td>
                        </tr>
                        <!-- Button Background Hover Color -->
                        <tr class="wa_order_option_remove_quantity">
                            <th scope="row">
                                <label class="wa_order_option_remove_quantity" for="wa_order_option_remove_quantity"><b><?php _e('Background Hover Color', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <?php
                                $bg_hover = get_option('wa_order_bg_hover_color');
                                if (empty($bg_hover)) {
                                    $bg_hover = 'rgba(37, 211, 102, 1)';
                                }
                                ?>
                                <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(37, 211, 102, 1)" name="wa_order_bg_hover_color" value="<?php echo esc_attr($bg_hover); ?>" />
                            </td>
                        </tr>
                        <!-- Button Text Color -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Text Color', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <?php
                                $txt = get_option('wa_order_txt_color');
                                if (empty($txt)) {
                                    $txt = 'rgba(255, 255, 255, 1)';
                                }
                                ?>
                                <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(255, 255, 255, 1)" name="wa_order_txt_color" value="<?php echo esc_attr($txt); ?>" />
                            </td>
                        </tr>
                        <!-- Button Text Hover Color -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php _e('Text Hover Color', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <?php
                                $txt_hover = get_option('wa_order_txt_hover_color');
                                if (empty($txt_hover)) {
                                    $txt_hover = 'rgba(255, 255, 255, 1)';
                                }
                                ?>
                                <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(255, 255, 255, 1)" name="wa_order_txt_hover_color" value="<?php echo esc_attr($txt_hover); ?>" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <!-- Button Box Shadow -->
                <table class="form-table">
                    <tbody>
                        <h3 class="section_wa_order"><?php _e('Button Box Shadow Color', 'oneclick-wa-order'); ?></h3>
                        <p><?php _e('Customize the box shadow color for the WhatsApp button.', 'oneclick-wa-order'); ?></p>
                        <!-- Button Box Shadow Settings -->
                        <?php
                        $bshdw_hz = get_option('wa_order_bshdw_horizontal', '0');
                        $bshdw_v = get_option('wa_order_bshdw_vertical', '4');
                        $bshdw_b = get_option('wa_order_bshdw_blur', '7');
                        $bshdw_s = get_option('wa_order_bshdw_spread', '0');
                        $bshdw_color = get_option('wa_order_btn_box_shdw', 'rgba(0,0,0,0.25)');
                        $bshdw_h_h = get_option('wa_order_bshdw_horizontal_hover', '0');
                        $bshdw_v_h = get_option('wa_order_bshdw_vertical_hover', '4');
                        $bshdw_b_h = get_option('wa_order_bshdw_blur_hover', '7');
                        $bshdw_s_h = get_option('wa_order_bshdw_spread_hover', '0');
                        $bshdw_color_hover = get_option('wa_order_btn_box_shdw_hover', 'rgba(0,0,0,0.25)');
                        ?>
                        <!-- Normal State Box Shadow -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><strong><?php _e('Box Shadow', 'oneclick-wa-order'); ?></strong></label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_horizontal" type="number" name="wa_order_bshdw_horizontal" value="<?php echo esc_attr($bshdw_hz); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Horizontal', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_vertical" type="number" name="wa_order_bshdw_vertical" value="<?php echo esc_attr($bshdw_v); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Vertical', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_blur" type="number" name="wa_order_bshdw_blur" value="<?php echo esc_attr($bshdw_b); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Blur', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_spread" type="number" name="wa_order_bshdw_spread" value="<?php echo esc_attr($bshdw_s); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Spread', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-color-control">
                                        <input id="wa_order_btn_box_shdw" type="text" class="color-picker" data-alpha-enabled="true" name="wa_order_btn_box_shdw" value="<?php echo esc_attr($bshdw_color); ?>" />
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- Hover State Box Shadow -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><strong><?php _e('Box Shadow Hover', 'oneclick-wa-order'); ?></strong></label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_horizontal_hover" type="number" name="wa_order_bshdw_horizontal_hover" value="<?php echo esc_attr($bshdw_h_h); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Horizontal', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_vertical_hover" type="number" name="wa_order_bshdw_vertical_hover" value="<?php echo esc_attr($bshdw_v_h); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Vertical', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_blur_hover" type="number" name="wa_order_bshdw_blur_hover" value="<?php echo esc_attr($bshdw_b_h); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Blur', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_bshdw_spread_hover" type="number" name="wa_order_bshdw_spread_hover" value="<?php echo esc_attr($bshdw_s_h); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Spread', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-color-control">
                                        <input id="wa_order_btn_box_shdw_hover" type="text" class="color-picker" data-alpha-enabled="true" name="wa_order_btn_box_shdw_hover" value="<?php echo esc_attr($bshdw_color_hover); ?>" />
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- Box Shadow Position -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php _e('Position', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="radio" name="wa_order_bshdw_position" value="outline" <?php checked('outline', get_option('wa_order_bshdw_position'), true); ?>>
                                <?php _e('Outline', 'oneclick-wa-order'); ?>
                                <input type="radio" name="wa_order_bshdw_position" value="inset" <?php checked('inset', get_option('wa_order_bshdw_position'), true); ?>>
                                <?php _e('Inset', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- Box Shadow Hover Position -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php _e('Hover Position', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="radio" name="wa_order_bshdw_position_hover" value="outline" <?php checked('outline', get_option('wa_order_bshdw_position_hover'), true); ?>>
                                <?php _e('Outline', 'oneclick-wa-order'); ?>
                                <input type="radio" name="wa_order_bshdw_position_hover" value="inset" <?php checked('inset', get_option('wa_order_bshdw_position_hover'), true); ?>>
                                <?php _e('Inset', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END of Button Customizations - Display Options -->
                <hr>
                <!-- Single Product Page Display Options -->
                <table class="form-table">
                    <tbody>
                        <h3 class="section_wa_order"><?php _e('Single Product Page', 'oneclick-wa-order'); ?></h3>
                        <p><?php _e('The following options will be only effective on single product page.', 'oneclick-wa-order'); ?></p>
                        <!-- Hide Button on Desktop -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_btn" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_btn'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <!-- Hide Button on Mobile -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_btn_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_btn_mobile'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_option_remove_quantity">
                            <th scope="row">
                                <label class="wa_order_option_remove_quantity" for="wa_order_option_remove_quantity"><b><?php _e('Hide Product Quantity Option?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_quantity" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_quantity'), 'yes'); ?>>
                                <?php _e('This will hide product quantity option field.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php _e('Hide Price in Product Page?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_price" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_price'), 'yes'); ?>>
                                <?php _e('This will hide price in Product page.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide Add to Cart button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_remove_cart_btn" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_cart_btn'), 'yes'); ?>>
                                <?php _e('This will hide Add to Cart button.', 'oneclick-wa-order'); ?>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide WA Button on Products in Categories', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_option_exlude_single_product_cats[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_option_exlude_single_product_cats');
                                    $option_array = (array) $option;
                                    $args = array('taxonomy' => 'product_cat', 'orderby' => 'name');
                                    $categories = get_categories($args);
                                    foreach ($categories as $category) {
                                        $selected = in_array($category->term_id, $option_array) ? ' selected="selected" ' : ''; ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html(ucwords($category->cat_name)) . ' (' . esc_html($category->category_count) . ')'; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <p>
                                    <?php _e('You can hide the WhatsApp button on products in the selected categories.', 'oneclick-wa-order'); ?>
                                    <br />
                                </p>
                                <br>
                            </td>
                        </tr>
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide WA Button on Products in Tags', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_option_exlude_single_product_tags[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_option_exlude_single_product_tags');
                                    $option_array = (array) $option;
                                    $tags = get_terms(['taxonomy' => 'product_tag', 'orderby' => 'name']);
                                    foreach ($tags as $tag) {
                                        $selected = in_array($tag->term_id, $option_array) ? ' selected="selected" ' : '';
                                        echo '<option value="' . esc_attr($tag->term_id) . '"' . $selected . '>';
                                        echo esc_html(ucwords($tag->name)) . ' (' . esc_html($tag->count) . ')';
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                                <p>
                                    <?php _e('You can hide the WhatsApp button on products in the selected tags.', 'oneclick-wa-order');
                                    ?>
                                    <br />
                                </p>
                                <br>
                            </td>
                        </tr>
                        <!-- Button Margin -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price">
                                    <strong><?php _e('Button Margin', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_single_button_margin_top" type="number" name="wa_order_single_button_margin_top" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_top')); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Top', 'oneclick-wa-order'); ?>
                                            <br />
                                        </p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_margin_right" type="number" name="wa_order_single_button_margin_right" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_right')); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Right', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_margin_bottom" type="number" name="wa_order_single_button_margin_bottom" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_bottom')); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_margin_left" type="number" name="wa_order_single_button_margin_left" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_left')); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Left', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- END - Button Margin -->
                        <!-- Button Padding -->
                        <tr class="wa_order_remove_price">
                            <th scope="row">
                                <label class="wa_order_price_label" for="wa_order_remove_price">
                                    <strong><?php _e('Button Padding', 'oneclick-wa-order'); ?></strong>
                                </label>
                            </th>
                            <td>
                                <ul class="boxes-control">
                                    <li class="box-control">
                                        <input id="wa_order_single_button_padding_top" type="number" name="wa_order_single_button_padding_top" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_top')); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Top', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_padding_right" type="number" name="wa_order_single_button_padding_right" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_right')); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Right', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_padding_bottom" type="number" name="wa_order_single_button_padding_bottom" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_bottom')); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                    <li class="box-control">
                                        <input id="wa_order_single_button_padding_left" type="number" name="wa_order_single_button_padding_left" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_left')); ?>" placeholder="">
                                        <p class="control-label"><?php _e('Left', 'oneclick-wa-order'); ?><br /></p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <!-- END - Button Padding -->
                    </tbody>
                </table>
                <!-- END of Single Product Page Display Options -->
                <hr>
                <!-- Shop Loop Display Options -->
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php _e('Shop Loop Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php _e('The following options will be only effective on shop loop page.', 'oneclick-wa-order'); ?></p>
                        <!-- Hide Button on Desktop -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_shop_loop_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_shop_loop_hide_desktop'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- Hide Button on Mobile -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_shop_loop_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_shop_loop_hide_mobile'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- Select Categories -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide WA Button Under Products in Categories', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_option_exlude_shop_product_cats[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_option_exlude_shop_product_cats');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'taxonomy' => 'product_cat',
                                        'orderby'  => 'name'
                                    );
                                    $categories = get_categories($args);
                                    foreach ($categories as $category) {
                                        $selected = in_array($category->term_id, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html(ucwords($category->cat_name)) . ' (' . esc_html($category->category_count) . ')'; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <p><?php _e('You can hide the WhatsApp button under products in the selected categories.', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>
                        <!-- Archive Pages Options -->
                        <tr class="wa_order_remove_add_btn">
                            <!-- For Categories -->
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Also Hide on Category Archive Page(s)?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_exlude_shop_product_cats_archive" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exlude_shop_product_cats_archive'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on the selected category archive page(s).', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- Select Tags -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide WA Button Under Products in Tags', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <select multiple="multiple" name="wa_order_option_exlude_shop_product_tags[]" class="postform octo-category-filter" style="width: 50%;">
                                    <?php
                                    $option = get_option('wa_order_option_exlude_shop_product_tags');
                                    $option_array = (array) $option;
                                    $args = array(
                                        'taxonomy' => 'product_tag',
                                        'orderby'  => 'name'
                                    );
                                    $tag_query = get_terms($args);
                                    foreach ($tag_query as $term) {
                                        $selected = in_array($term->term_id, $option_array) ? ' selected="selected" ' : '';
                                    ?>
                                        <option value="<?php echo esc_attr($term->term_id); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html(ucwords($term->name)) . ' (' . esc_html($term->count) . ')'; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <p><?php _e('You can hide the WhatsApp button under products in the selected tags.', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>
                        <!-- For Tags -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Also Hide on Tag Archive Page(s)?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_exlude_shop_product_tags_archive" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exlude_shop_product_tags_archive'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on the selected tag archive page(s).', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END of Shop Loop Display Options -->
                <hr>
                <!-- Cart Display Options -->
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php _e('Cart Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php _e('The following options will be only effective on cart page.', 'oneclick-wa-order'); ?></p>

                        <!-- Hide Button on Desktop -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_cart_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_cart_hide_desktop'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Hide Button on Mobile -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_cart_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_cart_hide_mobile'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END of Cart Display Options -->
                <hr>
                <!-- Checkout / Thank You Page Display Options -->
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php _e('Thank You Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php _e('The following options will be only effective on thank you page.', 'oneclick-wa-order'); ?></p>

                        <!-- Hide Button on Desktop -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_checkout_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_checkout_hide_desktop'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Hide Button on Mobile -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php _e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_display_option_checkout_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_checkout_hide_mobile'), 'yes'); ?>>
                                <?php _e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END of Checkout / Thank You Page Display Options -->
                <hr>
                <!-- Miscellaneous Display Options -->
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php _e('Miscellaneous', 'oneclick-wa-order'); ?></h2>
                        <p><?php _e('An additional option you might need.', 'oneclick-wa-order'); ?></p>

                        <!-- Convert Phone Number into WhatsApp in Order Details -->
                        <tr class="wa_order_remove_add_btn">
                            <th scope="row">
                                <label class="wa_order_remove_add_label" for="wa_order_convert_phone"><b><?php _e('Convert Phone Number into WhatsApp in Order Details?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_convert_phone_order_details" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_convert_phone_order_details'), 'yes'); ?>>
                                <?php _e('This will convert phone number link into WhatsApp chat link.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END of Miscellaneous Display Options -->
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'shop_page') { ?>
            <!-- Custom Shortcode -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-shop-loop'); ?>
                <?php do_settings_sections('wa-order-settings-group-shop-loop'); ?>
                <h2 class="section_wa_order"><?php _e('WhatsApp Button on Shop Page', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php _e('Add custom WhatsApp button on <strong>Shop</strong> page or product loop page right under / besides of the <strong>Add to Cart</strong> button.', 'oneclick-wa-order'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php _e('Shop Loop Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php _e('The following options will be only effective on shop loop page.', 'oneclick-wa-order'); ?></p>

                        <!-- Display Button on Shop Page -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Display button on Shop page?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_enable_button_shop_loop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_enable_button_shop_loop'), 'yes'); ?>>
                                <?php _e('This will display WhatsApp button on Shop page', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- WhatsApp Number Dropdown -->
                        <tr>
                            <th scope="row">
                                <label><?php _e('WhatsApp Number', 'oneclick-wa-order') ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_shop',
                                        'selected'  => get_option('wa_order_selected_wa_number_shop'),
                                    )
                                ) ?>
                                <p class="description">
                                    <?php _e('WhatsApp number is <strong style="color:red;">required</strong>. Please set it on <a href="edit.php?post_type=wa-order-numbers"><strong>Numbers</strong></a> tab.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Hide Add to Cart Button -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Hide Add to Cart button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_hide_atc_shop_loop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_hide_atc_shop_loop'), 'yes'); ?>>
                                <?php _e('This will only display WhatsApp button and hide the <code>Add to Cart</code> button', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php _e('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_button_text_shop_loop" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_button_text_shop_loop')); ?>" placeholder="<?php _e('e.g. Buy via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>

                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php _e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_custom_message_shop_loop" class="wa_order_input_areatext" rows="5" placeholder="<?php _e('e.g. Hello, I want to purchase:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_message_shop_loop')); ?></textarea>
                                <p class="description">
                                    <?php _e('Enter custom message, e.g. <code>Hello, I want to purchase:</code>', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>

                        <!-- Exclude Price Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Exclude Price?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_shop_loop_exclude_price" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_shop_loop_exclude_price'), 'yes'); ?>>
                                <?php _e('This will remove product price from WhatsApp message sent from Shop loop page.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Hide Product URL Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Remove Product URL?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_shop_loop_hide_product_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_shop_loop_hide_product_url'), 'yes'); ?>>
                                <?php _e('This will remove product URL from WhatsApp message sent from Shop loop page.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Open in New Tab Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_shop_loop_open_new_tab" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_option_shop_loop_open_new_tab'), '_blank'); ?>>
                                <?php _e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'cart_button') { ?>
            <!-- Custom Shortcode -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-cart-options'); ?>
                <?php do_settings_sections('wa-order-settings-group-cart-options'); ?>
                <h2 class="section_wa_order"><?php _e('WhatsApp Button on Cart Page', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php _e('Add custom WhatsApp button on <strong>Cart</strong> page right under the <strong>Proceed to Checkout</strong> button.', 'oneclick-wa-order'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wa_order"><?php _e('Cart Page', 'oneclick-wa-order'); ?></h2>
                        <p><?php _e('The following options will be only effective on cart page.', 'oneclick-wa-order'); ?></p>

                        <!-- Display Button on Cart Page -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Display button on Cart page?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_add_button_to_cart" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_add_button_to_cart'), 'yes'); ?>>
                                <?php _e('This will display WhatsApp button on Cart page', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- WhatsApp Number Dropdown -->
                        <tr>
                            <th scope="row">
                                <label><?php _e('WhatsApp Number', 'oneclick-wa-order') ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_cart',
                                        'selected'  => get_option('wa_order_selected_wa_number_cart'),
                                    )
                                ) ?>
                                <p class="description">
                                    <?php _e('WhatsApp number is <strong style="color:red;">required</strong>. Please set it on <a href="edit.php?post_type=wa-order-numbers"><strong>Numbers</strong></a> tab.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Hide Proceed to Checkout Button -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Hide Proceed to Checkout button?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_cart_hide_checkout" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_hide_checkout'), 'yes'); ?>>
                                <?php _e('This will only display WhatsApp button and hide the <code>Proceed to Checkout</code> button', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label class="wa_order_btn_txt_label" for="text_button"><b><?php _e('Text on Button', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_cart_button_text" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_cart_button_text')); ?>" placeholder="<?php _e('e.g. Complete Order via WhatsApp', 'oneclick-wa-order'); ?>">
                            </td>
                        </tr>

                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label class="wa_order_message_label" for="message_wbw"><b><?php _e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_cart_custom_message" class="wa_order_input_areatext" rows="5" placeholder="<?php _e('e.g. Hello, I want to purchase the item(s) below:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_cart_custom_message')); ?></textarea>
                                <p class="description">
                                    <?php _e('Enter custom message, e.g. <code>Hello, I want to purchase the item(s) below:</code>', 'oneclick-wa-order'); ?></p>
                            </td>
                        </tr>

                        <!-- Remove Product URL Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Remove Product URL?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_cart_hide_product_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_hide_product_url'), 'yes'); ?>>
                                <?php _e('This will remove product URL from WhatsApp message sent from Cart page.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Include Product Variation Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Include Product Variation?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_cart_enable_variations" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_enable_variations'), 'yes'); ?>>
                                <?php _e('This will include the product variation in the message. Note: Works only if the variation stored by WooCommerce, might not all.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Open in New Tab Option -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php _e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_cart_open_new_tab" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_option_cart_open_new_tab'), '_blank'); ?>>
                                <?php _e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'thanks_page') { ?>
            <!-- Checkout Thank You Page -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-order-completion'); ?>
                <?php do_settings_sections('wa-order-settings-group-order-completion'); ?>

                <h2 class="section_wa_order"><?php echo esc_html__('Thank You Page Customization', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php echo esc_html__('Add a WhatsApp button on Thank You / Order Received page. If enabled, it will add a new section under the Order Received or Thank You title and override default text by using below data, including adding a WhatsApp button to send order details.', 'oneclick-wa-order'); ?>
                    <br />
                    <strong><?php echo esc_html__('Tip:', 'oneclick-wa-order'); ?></strong> <?php echo esc_html__('You can use this to make it quick for your customers to send their own order receipt to you via WhatsApp.', 'oneclick-wa-order'); ?>
                </p>

                <table class="form-table">
                    <tbody>
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_enable_button_thank_you"><?php echo esc_html__('Enable Setting?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_enable_button_thank_you" id="wa_order_option_enable_button_thank_you" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_enable_button_thank_you')), 'yes'); ?>>
                                <?php echo esc_html__('This will override default appearance and add a WhatsApp button.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <!-- WhatsApp Number Dropdown -->
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <?php wa_order_phone_numbers_dropdown(
                                    array(
                                        'name'      => 'wa_order_selected_wa_number_thanks',
                                        'selected'  => esc_attr(get_option('wa_order_selected_wa_number_thanks')),
                                    )
                                ); ?>
                                <p class="description">
                                    <?php echo esc_html__('WhatsApp number is required. Please set it on', 'oneclick-wa-order') . ' <a href="edit.php?post_type=wa-order-numbers"><strong>' . esc_html__('Numbers', 'oneclick-wa-order') . '</strong></a> ' . esc_html__('tab.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Text on Button -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_button_text"><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_button_text" id="wa_order_option_custom_thank_you_button_text" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_button_text')); ?>" placeholder="<?php echo esc_attr__('e.g. Send Order Details', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter the text on WhatsApp button. e.g. Send Order Details', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Custom Message -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_custom_message"><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_custom_thank_you_custom_message" id="wa_order_option_custom_thank_you_custom_message" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, here\'s my order details:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_thank_you_custom_message')); ?></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Enter custom message to send along with order details. e.g. Hello, here\'s my order details:', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Custom Title -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_title"><?php echo esc_html__('Custom Title', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_title" id="wa_order_option_custom_thank_you_title" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_title')); ?>" placeholder="<?php echo esc_attr__('e.g. Thanks and You\'re Awesome', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('You can personalize the title by changing it here. This will be shown like this: [your custom title], [customer\'s first name]. e.g. Thanks and You\'re Awesome, Igor!', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Custom Subtitle -->
                        <tr class="wa_order_message">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_subtitle"><?php echo esc_html__('Custom Subtitle', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <textarea name="wa_order_option_custom_thank_you_subtitle" id="wa_order_option_custom_thank_you_subtitle" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. For faster response, send your order details by clicking below button.', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_thank_you_subtitle')); ?></textarea>
                                <p class="description">
                                    <?php echo esc_html__('Enter custom subtitle. e.g. For faster response, send your order details by clicking below button.', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>
                        <!-- Customer Details Label -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_customer_details_label"><?php echo esc_html__('Customer Details Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_customer_details_label" id="wa_order_option_custom_thank_you_customer_details_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_customer_details_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Customer Details', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for customer details. e.g. Customer Details', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Include Coupon Discount -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_inclue_coupon"><?php echo esc_html__('Include Coupon Discount?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_inclue_coupon" id="wa_order_option_custom_thank_you_inclue_coupon" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_inclue_coupon')), 'yes'); ?>>
                                <?php echo esc_html__('This will include coupon code and its deduction amount, including a label (the label must be set below if it\'s enabled).', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Coupon Label -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_coupon_label"><?php echo esc_html__('Coupon Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_coupon_label" id="wa_order_option_custom_thank_you_coupon_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_coupon_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Voucher Code', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for the coupon code. e.g. Voucher Code', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Include Order Number -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_order_number"><?php echo esc_html__('Include Order Number?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_order_number" id="wa_order_option_custom_thank_you_order_number" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_order_number')), 'yes'); ?>>
                                <?php echo esc_html__('This will include the order number including a label (the label must be set below if it\'s enabled).', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Order Number Label -->
                        <tr class="wa_order_btn_text">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_order_number_label"><?php echo esc_html__('Order Number Label', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wa_order_option_custom_thank_you_order_number_label" id="wa_order_option_custom_thank_you_order_number_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_order_number_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Order Number:', 'oneclick-wa-order'); ?>">
                                <p class="description">
                                    <?php echo esc_html__('Enter a label for the order number. e.g. Order Number:', 'oneclick-wa-order'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Include Product SKU -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_include_sku"><?php echo esc_html__('Include Product SKU?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_include_sku" id="wa_order_option_custom_thank_you_include_sku" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_include_sku')), 'yes'); ?>>
                                <?php echo esc_html__('Yes, Include Product SKU', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Include Order Date -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_include_order_date"><?php echo esc_html__('Include Order Date?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_include_order_date" id="wa_order_option_custom_thank_you_include_order_date" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_include_order_date')), 'yes'); ?>>
                                <?php echo esc_html__('Yes, Include Order Date', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>

                        <!-- Open in New Tab -->
                        <tr class="wa_order_target">
                            <th scope="row">
                                <label for="wa_order_option_custom_thank_you_open_new_tab"><?php echo esc_html__('Open in New Tab?', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_option_custom_thank_you_open_new_tab" id="wa_order_option_custom_thank_you_open_new_tab" class="wa_order_input_check" value="_blank" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_open_new_tab')), '_blank'); ?>>
                                <?php echo esc_html__('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'gdpr_notice') { ?>
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('wa-order-settings-group-gdpr'); ?>
                <?php do_settings_sections('wa-order-settings-group-gdpr'); ?>

                <h2 class="section_wa_order"><?php echo esc_html__('GDPR Notice', 'oneclick-wa-order'); ?></h2>
                <p>
                    <?php echo esc_html__('You can enable or disable the GDPR notice to make your site more GDPR compliant. The GDPR notice you configure below will be displayed right under the WhatsApp Order button. Please note that this option will only show the GDPR notice on single product page.', 'oneclick-wa-order'); ?>
                </p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('Enable GDPR Notice', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wa_order_gdpr_status_enable" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_gdpr_status_enable')), 'yes'); ?>>
                                <?php echo esc_html__('Check to Enable GDPR Notice.', 'oneclick-wa-order'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('GDPR Message', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <textarea name="wa_order_gdpr_message" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. I have read the [gdpr_link]', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_gdpr_message')); ?></textarea>
                                <p class="description">
                                    <?php printf(esc_html__('Use %s to display Privacy Policy page link.', 'oneclick-wa-order'), '<code>[gdpr_link]</code>'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label><?php echo esc_html__('Privacy Policy Page', 'oneclick-wa-order'); ?></label>
                            </th>
                            <td>
                                <?php wa_order_options_dropdown(
                                    array(
                                        'name'      => 'wa_order_gdpr_privacy_page',
                                        'selected'  => esc_attr(get_option('wa_order_gdpr_privacy_page')),
                                    )
                                ); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'tutorial_support') { ?>
            <!-- Begin creating plugin admin page -->
            <div class="wrap">
                <div class="feature-section one-col wrap about-wrap">
                    <div class="about-text">
                        <h4><?php printf(__("<strong>OneClick Chat to Order</strong> is Waiting for Your Feedback", 'oneclick-wa-order')); ?></h>
                    </div>
                    <div class="indo-about-description">
                        <?php printf(__("<strong>OneClick Chat to Order</strong> is my second plugin and it's open source. I acknowledge that there are still a lot to fix, here and there, that's why I really need your feedback. <br>Let's get in touch and show some love by <a href=\"https://wordpress.org/support/plugin/oneclick-whatsapp-order/reviews/?rate=5#new-post\" target=\"_blank\"><strong>leaving a review</strong></a>.", 'oneclick-wa-order')); ?>
                    </div>
                    <table class="tg" style="table-layout: fixed; width: 269px">
                        <colgroup>
                            <col style="width: 105px">
                            <col style="width: 164px">
                        </colgroup>
                        <tr>
                            <th class="tg-kiyi">
                                <?php _e('Author:', 'oneclick-wa-order'); ?></th>
                            <th class="tg-fymr">
                                <?php _e('Walter Pinem', 'oneclick-wa-order'); ?></th>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                                <?php _e('Website:', 'oneclick-wa-order'); ?></td>
                            <td class="tg-fymr"><a href="https://walterpinem.me/" target="_blank">
                                    <?php _e('walterpinem.me', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                            <td class="tg-fymr"><a href="https://walterpinem.me/projects/tools/" target="_blank">
                                    <?php _e('60+ Free Online Tools', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                                <?php _e('Email:', 'oneclick-wa-order'); ?></td>
                            <td class="tg-fymr"><a href="mailto:hello@walterpinem.me" target="_blank">
                                    <?php _e('hello@walterpinem.me', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi"><?php _e('More:', 'oneclick-wa-order'); ?></td>
                            <td class="tg-fymr"><a href="https://youtu.be/LuURM5vZyB8" target="_blank">
                                    <?php _e('Complete Tutorial', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi" rowspan="3"></td>
                            <td class="tg-fymr"><a href="https://walterpinem.me/projects/contact/" target="_blank">
                                    <?php _e('Support & Feature Request', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi" rowspan="3"></td>
                            <td class="tg-fymr"><a href="https://www.paypal.me/WalterPinem" target="_blank">
                                    <?php _e('Donate', 'oneclick-wa-order'); ?></a></td>
                        </tr>
                    </table>
                    <br>
                    <hr>
                    <?php echo do_shortcode("[donate]"); ?>
                    <center>
                        <p><?php printf(__("Created with ❤ and ☕ in Central Jakarta, Indonesia by <a href=\"https://walterpinem.me\" target=\"_blank\"><strong>Walter Pinem</strong></a>", 'oneclick-wa-order')); ?></p>
                    </center>
                </div>
            </div>
        <?php } elseif ($active_tab == 'welcome') { ?>
            <!-- Begin creating plugin admin page -->
            <div class="wrap">
                <div class="feature-section one-col wrap about-wrap">
                    <div class="indo-title-text">
                        <h2><?php echo wp_kses_post('Thank you for using <br><strong>OneClick Chat to Order</strong>', 'oneclick-wa-order'); ?></h2>
                        <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/oneclick-chat-to-order.png'); ?>" alt="<?php esc_attr_e('OneClick Chat to Order Logo', 'oneclick-wa-order'); ?>" />
                    </div>
                    <div class="feature-section one-col about-text">
                        <h3><?php esc_html_e("Make It Easy for Customers to Reach You!", 'oneclick-wa-order'); ?></h3>
                    </div>
                    <div class="feature-section one-col indo-about-description">
                        <p>
                            <?php esc_html_e('OneClick Chat to Order will enable you to connect your WooCommerce-powered online store with WhatsApp and make it super quick and easy for your customers to complete their order via WhatsApp.', 'oneclick-wa-order'); ?>
                        </p>
                        <p>
                            <a href="https://onlinestorekit.com/oneclick-chat-to-order/" target="_blank"><?php esc_html_e('Learn More', 'oneclick-wa-order'); ?></a>
                        </p>
                    </div>
                    <div class="clear"></div>
                    <hr>
                    <div class="feature-section one-col about-text">
                        <h4><?php echo wp_kses_post(__("<strong style=\"color:red;\">NEW!</strong> Build a Powerful Multi-Vendor Online Marketplace", 'oneclick-wa-order')); ?></h4>

                    </div>
                    <div class="feature-section one-col indo-about-description">
                        <p>
                            <?php esc_html_e('Seamlessly combine the power of WordPress & WooCommerce, OneClick Chat to Order, WCFM Marketplace, WCFM Frontend Manager and WhatsApp with the new and most requested add-on, OneClick WCFM Connector, that your vendors will love.', 'oneclick-wa-order'); ?>
                        </p>
                        <p>
                            <?php esc_html_e('Help them increase their sales, increase your revenue.', 'oneclick-wa-order'); ?>
                        </p>
                        <p>
                            <a href="https://onlinestorekit.com/oneclick-wcfm-connector/" target="_blank"><?php esc_html_e('Read Details', 'oneclick-wa-order'); ?></a>
                        </p>
                    </div>
                    <div class="clear"></div>
                    <hr />
                    <div class="feature-section one-col">
                        <h3 style="text-align: center;"><?php esc_html_e('Watch the Complete Overview and Tutorial', 'oneclick-wa-order'); ?></h3>
                        <div class="headline-feature feature-video">
                            <div class='embed-container'>
                                <iframe src='https://www.youtube.com/embed/?listType=playlist&list=PLwazGJFvaLnBTOw4pNvPcsFW1ls4tn1Uj' frameborder='0' allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <hr />
                    <div class="feature-section one-col">
                        <div class="indo-get-started">
                            <h3><?php esc_html_e('Let\'s Get Started', 'oneclick-wa-order'); ?></h3>
                            <ul>
                                <li><strong><?php esc_html_e('Step #1:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Start adding your WhatsApp number on WhatsApp Numbers post type. You can add unlimited numbers! Learn more or dismiss notice.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #2:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Show a fancy Floating Button with customized message and tooltip which you can customize easily on Floating Button setting panel.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #3:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Configure some options to display or hide buttons, including the WhatsApp button on Display Options setting panel.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #4:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Make your online store GDPR-ready by showing GDPR Notice right under the WhatsApp Order button on GDPR Notice setting panel.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #5:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Display WhatsApp button anywhere you like with a single shortcode. You can generate it with a customized message and a nice text on button on Generate Shortcode setting panel.', 'oneclick-wa-order'); ?></li>
                                <li><strong><?php esc_html_e('Step #6:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Have an inquiry? Find out how to reach me out on Support panel.', 'oneclick-wa-order'); ?></li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div class="feature-section two-col">
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/simple-chat-button.png'); ?>" alt="<?php esc_attr_e('Simple Chat Button', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Simple Chat to Order Button', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('Replace the default Add to Cart button or simply show both. Once the Chat to Order button is clicked, the message along with the product details are sent to you through WhatsApp.', 'oneclick-wa-order'); ?></p>
                        </div>
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/fancy-floating-button.png'); ?>" alt="<?php esc_attr_e('Fancy Floating Button', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Fancy Floating Button', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('Make it easy for any customers/visitors to reach you out through a click of a floating WhatsApp button, displayed on the left of right with tons of customization options.', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                    <div class="feature-section two-col">
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/display-this-or-hide-that.png'); ?>" alt="<?php esc_attr_e('Display or Hide Elements', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Display This or Hide That', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('Wanna hide some buttons or elements you don\'t like? You have the command to rule them all. Just visit the panel and all of the options are there to configure.', 'oneclick-wa-order'); ?></p>
                        </div>
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/gdpr-ready.png'); ?>" alt="<?php esc_attr_e('GDPR Ready', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Make It GDPR-Ready', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('The regulations are real and it\'s time to make your site ready for them. Make your site GDPR-ready with some simple configurations, really easy!', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                    <div class="feature-section two-col">
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/shortcode.png'); ?>" alt="<?php esc_attr_e('Shortcode Generator', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Shortcode Generator', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('Are the previous options still not enough for you? You can extend the flexibility to display a WhatsApp button using a shortcode, which you can generate easily.', 'oneclick-wa-order'); ?></p>
                        </div>
                        <div class="col">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'assets/images/documentation.png'); ?>" alt="<?php esc_attr_e('Comprehensive Documentation', 'oneclick-wa-order'); ?>" />
                            <h3><?php esc_html_e('Comprehensive Documentation', 'oneclick-wa-order'); ?></h3>
                            <p><?php esc_html_e('You will not be left alone. My complete documentation or tutorial will always help and support all your needs to get started. Watch tutorial videos.', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <?php echo do_shortcode("[donate]"); ?>
                    <center>
                        <p><?php esc_html_e('Created with ❤ and ☕ in Jakarta, Indonesia by Walter Pinem', 'oneclick-wa-order'); ?></p>
                    </center>
                </div>
            </div>
            <br>
    </div>
<?php
        }
    }

    // Donate button
    function wa_order_donate_button_shortcode()
    {
        ob_start();
?>
<center>
    <div class="donate-container">
        <p><?php esc_html_e('To keep this plugin free, I spent cups of coffee building it. If you love and find it really useful for your business, you can always', 'oneclick-wa-order'); ?></p>
        <a href="https://www.paypal.me/WalterPinem" target="_blank">
            <button class="donatebutton">
                ☕ <?php esc_html_e('Buy Me a Coffee', 'oneclick-wa-order'); ?>
            </button>
        </a>
    </div>
</center>
<?php
        return ob_get_clean();
    }
    add_shortcode('donate', 'wa_order_donate_button_shortcode');
