<?php

// Custom Thank You title
$override_thankyou_page = get_option(sanitize_text_field('wa_order_option_enable_button_thank_you'));
function wa_order_thank_you_override($title, $id)
{
    global $wp, $wa_base;
    // Consolidate get_option() calls
    $options = array(
        'wanumberpage'               => get_option('wa_order_selected_wa_number_thanks', ''),
        'custom_title'               => get_option('wa_order_option_custom_thank_you_title', 'Thanks and You\'re Awesome'),
        'custom_subtitle'            => get_option('wa_order_option_custom_thank_you_subtitle', 'For faster response, send your order details by clicking below button.'),
        'button_text'                => get_option('wa_order_option_custom_thank_you_button_text', 'Send Order Details'),
        'custom_message'             => get_option('wa_order_option_custom_thank_you_custom_message', "Hello, here's my order details:"),
        'thanks_label'               => get_option('wa_order_option_thank_you_label', ''),
        'include_order_number'       => get_option('wa_order_option_custom_thank_you_order_number', ''),
        'order_number_label'         => get_option('wa_order_option_custom_thank_you_order_number_label', ''),
        'customer_details_label'     => get_option('wa_order_option_custom_thank_you_customer_details_label', 'Customer Details'),
        'total_label'                => get_option('wa_order_option_total_amount_label'),
        'payment_label'              => get_option('wa_order_option_payment_method_label'),
        'include_sku'                => get_option('wa_order_option_custom_thank_you_include_sku'),
        'include_coupon'             => get_option('wa_order_option_custom_thank_you_inclue_coupon'),
        'coupon_label'               => get_option('wa_order_option_custom_thank_you_coupon_label'),
        'order_date'                 => get_option('wa_order_option_custom_thank_you_include_order_date'),
        'open_new_tab'               => get_option('wa_order_option_custom_thank_you_open_new_tab'),
    );
    $wanumberpage = $options['wanumberpage'];
    $postid = get_page_by_path($wanumberpage, OBJECT, 'wa-order-numbers');
    $phonenumb = $postid ? get_post_meta($postid->ID, 'wa_order_phone_number_input', true) : '';

    $custom_title           = $options['custom_title'];
    $custom_subtitle        = $options['custom_subtitle'];
    $button_text            = $options['button_text'];
    $custom_message         = $options['custom_message'];
    $thanks_label           = $options['thanks_label'];
    $include_order_number   = $options['include_order_number'];
    $order_number_label     = $options['order_number_label'];
    $customer_details_label = $options['customer_details_label'];

    $order_id   = (int) $wp->query_vars['order-received'];
    $order      = $order_id ? wc_get_order($order_id) : null;

    // Prepare the message
    $message            = urlencode($custom_message . "\r\n\r\n");
    $thetitle           = $custom_title;
    $subtitle           = $custom_subtitle;
    $button             = $button_text;
    $customer_details   = $customer_details_label;
    if (isset($order)) {
        $first_name     = $order->get_billing_first_name();
        $last_name      = $order->get_billing_last_name();
        $thetitle       = $custom_title . ', ' . $first_name . '!';
        $subtitle       = $custom_subtitle;
        $customer       = $first_name . ' ' . $last_name;
        $customer_email = $order->get_billing_email();
        $adress_1       = $order->get_billing_address_1();
        $adress_2x      = $order->get_billing_address_2();
        $postcode       = $order->get_billing_postcode();
        $state          = $order->get_billing_state();
        $country        = $order->get_billing_country();
        $customer_phone = $order->get_billing_phone();
        $full_state     = WC()->countries->get_states($country)[$state];
        $full_country   = WC()->countries->get_countries($country)[$country];
        if (empty($address_2x)) {
            $adress_2 = "";
        } else {
            $adress_2 = urlencode("\r\n" . $adress_2x . "");
        }
        $billing_address = $order->get_formatted_billing_address();
        $formatted_billingx = str_replace('<br/>', "\r\n", $billing_address);
        $formatted_billing = "" . $formatted_billingx . "\r\n" . $customer_phone . "\r\n" . $customer_email . "";

        $shipping_address = $order->get_formatted_shipping_address();
        $formatted_shipping = str_replace('<br/>', "\r\n", $shipping_address);

        $total_label = $options['total_label'];
        $payment_label = $options['payment_label'];
        $normalsubtotal = $order->get_subtotal();
        $subtotal_price = $order->get_subtotal_to_display();
        $format_subtotal_pricex = wp_strip_all_tags($subtotal_price);
        $format_subtotal_price = html_entity_decode($format_subtotal_pricex);
        // $format_price = number_format($price, 2, '.', ',');
        $currencyx = get_woocommerce_currency_symbol();
        $currency = html_entity_decode($currencyx);
        // $total_price = "\r\n*".$total_label.":*\r\n".$currency." ".$format_price."\r\n";
        $label_total = urlencode("\r\n*" . $total_label . ":*\r\n");
        $total_format_subtotal_price = "" . $label_total . "" . $format_subtotal_price . "";
        $payment_method = $order->get_payment_method_title();
        $payment = "\r\n*" . $payment_label . ":*\r\n" . $payment_method . "\r\n";
        $date = date('F j, Y - g:i A', $order->get_date_created()->getOffsetTimestamp());
        $order_number = $order->get_order_number();
        if ($order_number_label == '') {
            $on_label = "Order Number:";
        } else {
            $on_label = "$order_number_label";
        }

        // If Order Number inclusion is checked
        if ($include_order_number === 'yes') {
            $message .= urlencode("*" . $on_label . "*\r\n#" . $order_number . "\r\n\r\n");
        } else {
            // Final output of the message
            $message .= "";
        }
    }

    $order = new WC_Order($order_id);
    foreach ($order->get_items() as $item_id => $item) {
        $product_id   = $item->get_product_id(); //Get the product ID
        $quantity     = $item->get_quantity(); //Get the product QTY
        $product_name = $item->get_name(); //Get the product NAME
        $quantity = $item->get_quantity();
        $message .= urlencode("" . $quantity . "x - *" . $product_name . "*\r\n");
        // get order item data (in an unprotected array)
        $item_data = $item->get_data();

        // get order item meta data (in an unprotected array)
        $item_meta_data = $item->get_meta_data();

        // get only All item meta data even hidden (in an unprotected array)
        $formatted_meta_data = $item->get_formatted_meta_data('_', true);
        $array = json_decode(json_encode($formatted_meta_data), true);
        $arrayx = array_values($array);
        $arrayxxx = array_merge($array);
        $result = array();
        foreach ((array) $arrayxxx as $value) {
            $product_meta = "";
            $result[] = array($value["display_key"], wp_strip_all_tags($value["display_value"]));
            foreach ($result as $key) {
                $result = array();
                $product_meta .= "     - ```" . $key[0] . ":``` ```" . $key[1] . "```\r\n";
                $message .= urlencode("" . $product_meta . "");
            }
        }
        $productsku         = $item->get_product($item);
        $include_sku        = $options['include_sku'];
        $sku                = $productsku->get_sku();
        $sku_label          = __('SKU', 'woocommerce');
        if (!empty($sku) && $include_sku === 'yes') {
            $message .= urlencode("     - ```" . $sku_label . ": " . $sku . "```\r\n");
        } else {
            $message .= "";
        }
    }
    $message .= "" . $total_format_subtotal_price . "";
    $message .= urlencode("\r\n");

    // Coupon item: Check if coupon code used
    $order_items = $order->get_items('coupon');
    // Let's loop
    foreach ($order_items as $item_id => $item) {

        // Using WP_Query to retrieve the coupon ID reference
        $args = array(
            'name'           => $item->get_name(),
            'post_type'      => 'shop_coupon',
            'post_status'    => 'publish',
            'numberposts'    => 1
        );

        $coupon_posts = get_posts($args);
        if ($coupon_posts) {
            $coupon_id = $coupon_posts[0]->ID;

            // Retrieve an instance of WC_Coupon object
            $coupon = new WC_Coupon($coupon_id);

            // Conditional discount type + its symbol
            if ($coupon->is_type('fixed_cart') || $coupon->is_type('fixed_product')) {
                $pre_symbol = $currency;
            } elseif ($coupon->is_type('percent')) {
                $pre_symbol = "%";
            } else {
                $pre_symbol = "";
            }

            // Check if any discount code used and enabled from admin plugin setting
            $include_coupon = $options['include_coupon'];
            if ($order->get_total_discount() > 0 && $include_coupon === 'yes') {
                $coupons  = $order->get_coupon_codes();
                $coupons  = count($coupons) > 0 ? implode(',', $coupons) : '';
                $discountx = $order->get_total_discount();
                $discounty = wp_strip_all_tags($discountx);
                $discount = html_entity_decode($discounty);

                // Set coupon label
                $coupon_label = get_option(sanitize_text_field('wa_order_option_custom_thank_you_coupon_label'));
                if ($coupon_label == '') {
                    $voucher_label = "Voucher Code:";
                } else {
                    $voucher_label = $coupon_label;
                }

                // If coupon type is fixed cart & fixed product
                if ($coupon->is_type('fixed_cart') || $coupon->is_type('fixed_product')) {
                    // Get individual discount amount
                    $discount_format = html_entity_decode(wp_strip_all_tags(wc_price($coupon->get_amount())));

                    $coupon_code = "*" . $voucher_label . "*\r\n" . ucwords($coupon->get_code()) . ": -" . $discount_format . "";
                    $message .= urlencode("\r\n" . $coupon_code . "\r\n");

                    // Calculate subtotal after discount
                    $numeric_subtotal = $order->get_subtotal(); // Subtotal excluding tax
                    $numeric_discount = floatval($coupon->get_amount()); // Fixed discount amount
                    $subtotal_minus_discount = $numeric_subtotal - $numeric_discount;

                    // Correctly format subtotal minus discount
                    $subtotal_minus_discount_formatted = html_entity_decode(wp_strip_all_tags(wc_price($subtotal_minus_discount)));
                    $subtlabel = __('Discount', 'woocommerce');
                    $subtcalculatedoutput = html_entity_decode(wp_strip_all_tags(wc_price($numeric_subtotal))) . " - " . $discount_format . " = " . $subtotal_minus_discount_formatted;
                    $message .= urlencode("*" . $subtlabel . ":* \r\n" . $subtcalculatedoutput . "\r\n");

                    // If coupon type is percentage
                } elseif ($coupon->is_type('percent')) {
                    $discount_percent = $coupon->get_amount(); // Get the percentage amount of the discount
                    $numeric_subtotal = $order->get_subtotal(); // Subtotal excluding tax

                    // Calculate the discount amount based on the percentage
                    $discount_amount = ($discount_percent / 100) * $numeric_subtotal;

                    // Calculate the subtotal after applying the discount
                    $subtotal_minus_discount = $numeric_subtotal - $discount_amount;

                    // Format the discount and subtotal for display
                    $discount_format = html_entity_decode(wp_strip_all_tags(wc_price($discount_amount)));
                    $subtotal_minus_discount_formatted = html_entity_decode(wp_strip_all_tags(wc_price($subtotal_minus_discount)));

                    // Prepare the message
                    $coupon_code = "*" . $voucher_label . "*\r\n" . ucwords($coupon->get_code()) . ": -" . $discount_percent . "% (-" . $discount_format . ")";
                    $message .= urlencode("\r\n" . $coupon_code . "\r\n");

                    $subtlabel = __('Discount', 'woocommerce');
                    $subtcalculatedoutput = html_entity_decode(wp_strip_all_tags(wc_price($numeric_subtotal))) . " - " . $discount_format . " = " . $subtotal_minus_discount_formatted;
                    $message .= urlencode("*" . $subtlabel . ":* \r\n" . $subtcalculatedoutput . "\r\n");
                } else {
                    $discount_format = "";
                    $coupon_code = "";
                    $message .= "";
                }
            }
        }

        // Check if customer purchase note exits
        $note = $order->get_customer_note();
        if ($note) {
            $note_label = __('Note:', 'woocommerce');
            $purchase_note = "*" . $note_label . "*\r\n" . $note . "\r\n\r\n";
        } else {
            $purchase_note = "";
        }
        $message .= urlencode("" . $payment . "\r\n*" . $customer_details . "*");

        // Get Shipping Method
        $ship_method = $order->get_shipping_method();
        $ship_label = __('Shipping:', 'woocommerce');
        if (empty($ship_method) && empty($_POST['ship_to_different_address'])) {
            $message .= urlencode("\r\n" . $formatted_billing . "");
        } else {
            $ship_cost = $order->get_shipping_to_display();
            $shipping_cost = wp_strip_all_tags($ship_cost);
            $shipping_method = $ship_method . $shipping_cost;
            $message .= urlencode("\r\n" . $formatted_shipping . "\r\n");
            $message .= urlencode("\r\n*" . $ship_label . "*\r\n");
            $message .= $shipping_cost;
        }

        // Show the total price
        $price          = $order->get_formatted_order_total();
        $format_price   = wp_strip_all_tags($price);
        $currency       = get_woocommerce_currency();
        $label_total    = urlencode("\r\n\r\n*Total:*\r\n");
        $total_price    = "" . $label_total . "" . $format_price . "";
        $message        .= $total_price;

        // Include or Exclude Order date & time
        $order_date = $options['order_date'];
        if ($order_date !== 'yes') {
            $message .= urlencode("\r\n\r\n" . $purchase_note . "" . $thanks_label . ""); // Final message
        } else {
            $message .= urlencode("\r\n\r\n" . $purchase_note . "" . $thanks_label . "\r\n\r\n(" . $date . ")"); // Final message
        }
    }
    // WhatsApp URL
    $base_url           = 'https://' . $wa_base . '.whatsapp.com/send';
    $encoded_phone      = urlencode($phonenumb);
    $final_message      = $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    $button_url         = $base_url . '?phone=' . $encoded_phone . '&text=' . $final_message;
    $target             = $options['open_new_tab'];
    // Final Output
    $final_output = '<div class="thankyoucustom_wrapper">
    <h2 class="thankyoutitle">' . $thetitle . '</h2>
    <p class="subtitle">' . $subtitle . '</p>
    <a id="sendbtn" href="' . $button_url . '" target="' . $target . '" class="wa-order-thankyou">
        ' . $button . '
    </a>
    </div>';
    return wp_kses_post($final_output);
}
if ($override_thankyou_page === 'yes') {
    add_filter('woocommerce_thankyou_order_received_text', 'wa_order_thank_you_override', 10, 2);
}

// Thank you page default class
// Remove element based on class
add_action('wp_footer', 'wa_order_remove_default_thankyou_class');
function wa_order_remove_default_thankyou_class()
{
    $override_thankyou_page = get_option(sanitize_text_field('wa_order_option_enable_button_thank_you'));
    if ($override_thankyou_page === 'yes') {
?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(".woocommerce-thankyou-order-received").remove();
            });
        </script>
<?php
    }
}
