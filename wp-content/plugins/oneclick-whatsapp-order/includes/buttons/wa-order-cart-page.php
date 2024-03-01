<?php
function wa_order_add_button_to_cart_page()
{
	if (get_option('wa_order_option_add_button_to_cart') !== 'yes') {
		return;
	}
	global $product, $wa_base, $woocommerce, $subtotal;
	$wanumberpage = get_option('wa_order_selected_wa_number_cart', '');
	$postid = get_page_by_path($wanumberpage, OBJECT, 'wa-order-numbers');
	if (!$postid) {
		return;
	}
	$phonenumb = get_post_meta($postid->ID, 'wa_order_phone_number_input', true);
	if (!$phonenumb) {
		return;
	}
	$items = WC()->cart->get_cart();
	$cart_button_text = get_option('wa_order_option_cart_button_text', 'Complete Order via WhatsApp');
	$custom_message = get_option('wa_order_option_cart_custom_message', 'Hello, I want to purchase the item(s) below:');
	$message = urlencode($custom_message);
	$currency = get_woocommerce_currency();
	foreach ($items as $item) {
		$_product = wc_get_product($item['product_id']);
		$product_name = $_product->get_name();
		$qty = $item['quantity'];
		$price = wc_price($item['line_subtotal']);
		$format_price = wp_strip_all_tags($price);
		$var = $item['variation'];
		$product_url = get_post_permalink($item['product_id']);
		$total_amount = wc_price(WC()->cart->get_cart_total());
		$format_total = wp_strip_all_tags($total_amount);
		$quantity_label = get_option('wa_order_option_quantity_label');
		$price_label = get_option('wa_order_option_price_label');
		$url_label = get_option('wa_order_option_url_label');
		$thanks_label = get_option('wa_order_option_thank_you_label');
		$total_label = get_option('wa_order_option_total_amount_label');
		$target = get_option(sanitize_text_field('wa_order_option_cart_open_new_tab'));
		$removeproductURL = get_option(sanitize_text_field('wa_order_option_cart_hide_product_url'));
		$message .= urlencode("\r\n\r\n*" . $product_name . "*");
		$include_variation = get_option(sanitize_text_field('wa_order_option_cart_enable_variations'));
		if ($item['variation_id'] > 0 && $_product->is_type('variable') && $include_variation === 'yes') {
			$variations = wc_get_formatted_variation($item['variation'], false);
			$variationx = rawurldecode($variations);
			$variation = str_replace(array('<dl class="variation"><dt>', "</dt><dd>", "</dd><dt>", "</dd></dl>"), array('', " ", "\r\n", ""), $variations);
			$message .= urlencode("\r\n" . ucwords($variation) . "");
		} else {
			$message .= "";
		}
		if ($removeproductURL === 'yes') {
			$message .= urlencode("\r\n*" . $quantity_label . ":* " . $qty . "\r\n*" . $price_label . ":* ");
			$message .= " " . $format_price . " ";
		} else {
			$message .= urlencode("\r\n*" . $quantity_label . ":* " . $qty . " ");
			$message .= urlencode("\r\n*" . $price_label . ":*");
			$message .= " " . $format_price . " ";
			$message .= urlencode("\r\n*" . $url_label . ":* " . $product_url . "");
		}
	}
	$cart = WC()->cart;
	$coupons = WC()->cart->get_applied_coupons();
	foreach ($coupons as $coupon) {
		$coupon = new WC_Coupon($coupon);
		if ($woocommerce->cart->has_discount($coupon->get_code())) {
			$currencyx = get_woocommerce_currency_symbol();
			$currency = html_entity_decode($currencyx);
			if ('fixed_product' === $coupon->get_discount_type() && 'fixed_cart' === $coupon->get_discount_type()) {
				$pre_symbol = $currency;
			} elseif ('percent' === $coupon->get_discount_type()) {
				$pre_symbol = "%";
			} else {
				$pre_symbol = "";
			}
			$coupons = $cart->get_applied_coupons();
			$coupons = count($coupons) > 0 ? implode(', ', $coupons) : '';
			$discountx = $cart->get_total_discount();
			$discounty = wp_strip_all_tags($discountx);
			$discount = html_entity_decode($discounty);
			$normalsubtotal = WC()->cart->subtotal;
			$coupon_label = get_option(sanitize_text_field('wa_order_option_custom_thank_you_coupon_label'));
			if ($coupon_label == '') $voucher_label = "Voucher Code:";
			else $voucher_label = "$coupon_label";
			if ($coupon->is_type('fixed_cart') || $coupon->is_type('fixed_product')) {
				// Get individual discount amount
				$indv_discountx = wc_price($coupon->get_amount());
				$indv_discounty = wp_strip_all_tags($indv_discountx);
				$indv_discount = html_entity_decode($indv_discounty);
				$discount_format = $indv_discount;

				$coupon_code = "*" . $voucher_label . "*\r\n" . ucwords($coupon->get_code()) . ": -" . $discount_format . "";
				$message .= urlencode("\r\n\r\n" . $coupon_code . "\r\n");

				// Calculate subtotal after discount
				$numeric_subtotal = WC()->cart->subtotal_ex_tax; // Subtotal excluding tax
				$numeric_discount = floatval($coupon->get_amount()); // Fixed discount amount
				$subtotal_minus_discount = $numeric_subtotal - $numeric_discount;

				// Correctly format subtotal minus discount
				$subtotal_minus_discount_formatted = html_entity_decode(wp_strip_all_tags(wc_price($subtotal_minus_discount)));
				$subtlabel = __('Discount', 'woocommerce');
				$subtcalculatedoutput = html_entity_decode(wp_strip_all_tags(wc_price($numeric_subtotal))) . " - " . $discount_format . " = " . $subtotal_minus_discount_formatted;
				$message .= urlencode("*" . $subtlabel . ":* \r\n" . $subtcalculatedoutput . "\r\n");
			} elseif ($coupon->is_type('percent')) {
				$discount_percent = $coupon->get_amount(); // Get the percentage amount of the discount
				$numeric_subtotal = WC()->cart->subtotal_ex_tax; // Subtotal excluding tax

				// Calculate the discount amount based on the percentage
				$discount_amount = ($discount_percent / 100) * $numeric_subtotal;

				// Calculate the subtotal after applying the discount
				$subtotal_minus_discount = $numeric_subtotal - $discount_amount;

				// Format the discount and subtotal for display
				$discount_format = html_entity_decode(wp_strip_all_tags(wc_price($discount_amount)));
				$subtotal_minus_discount_formatted = html_entity_decode(wp_strip_all_tags(wc_price($subtotal_minus_discount)));

				// Prepare the message
				$coupon_code = "*" . $voucher_label . "*\r\n" . ucwords($coupon->get_code()) . ": -" . $discount_percent . "% (-" . $discount_format . ")";
				$message .= urlencode("\r\n\r\n" . $coupon_code . "\r\n");

				$subtlabel = __('Discount', 'woocommerce');
				$subtcalculatedoutput = html_entity_decode(wp_strip_all_tags(wc_price($numeric_subtotal))) . " - " . $discount_format . " = " . $subtotal_minus_discount_formatted;
				$message .= urlencode("*" . $subtlabel . ":* \r\n" . $subtcalculatedoutput . "\r\n");
			} else {
				$discount_format = "";
				$coupon_code = "";
				$message .= "";
			}
		} else {
			$message .= "";
		}
	}
	$products = $woocommerce->cart->get_cart();
	// Check if the cart contains non-virtual, non-downloadable products and shipping is calculated
	$customer = WC()->session->get('customer');
	$is_shipping_applicable = false;

	foreach (WC()->cart->get_cart() as $cart_item) {
		if (!$cart_item['data']->is_virtual() && !$cart_item['data']->is_downloadable()) {
			$is_shipping_applicable = true;
			break;
		}
	}

	if ($is_shipping_applicable || $customer['calculated_shipping'] && !empty($customer['address']) && !empty($customer['city']) && !empty($customer['state'])) {
		// if ($is_virtual == 'no' && $is_downloadable == 'no' || $customer['calculated_shipping'] && !empty($customer['address']) && !empty($customer['city']) && !empty($customer['state'])) {
		if (WC()->cart->show_shipping()) {
			if (WC()->cart->get_customer()->get_shipping_first_name()) {
				$firstname = WC()->cart->get_customer()->get_shipping_first_name();
				$fname = "\r\n" . $firstname . "";
			} else {
				$fname = "";
			}
			if (WC()->cart->get_customer()->get_shipping_last_name()) {
				$lastname = WC()->cart->get_customer()->get_shipping_last_name();
				$lname = "" . $lastname . "";
			} else {
				$lname = "";
			}
			if (WC()->cart->get_customer()->get_shipping_postcode()) {
				$postcodex = WC()->cart->get_customer()->get_shipping_postcode();
				$postcode = "\r\n" . $postcodex . "";
			} else {
				$postcode = "";
			}
			if (WC()->cart->get_customer()->get_shipping_city()) {
				$cityx = WC()->cart->get_customer()->get_shipping_city();
				$city = "\r\n" . $cityx . "";
			} else {
				$city = "";
			}
			if (WC()->cart->get_customer()->get_shipping_address()) {
				$ad1x = WC()->cart->get_customer()->get_shipping_address();
				$ad1 = "\r\n" . $ad1x . "";
			} else {
				$ad1 = "";
			}
			if (WC()->cart->get_customer()->get_shipping_address_2()) {
				$ad2x = WC()->cart->get_customer()->get_shipping_address_2();
				$ad2 = "\r\n" . $ad2x . "";
			} else {
				$ad2 = "";
			}
			if (WC()->customer->get_shipping_country()) {
				$current_cc = WC()->customer->get_shipping_country();
				$countryx = WC()->countries->countries[$current_cc];
				$country = "\r\n" . $countryx . "";
			} else {
				$country = "";
			}
			if (WC()->customer->get_shipping_state()) {
				$current_cc = WC()->customer->get_shipping_country();
				$current_r = WC()->customer->get_shipping_state();
				$statex = WC()->countries->get_states($current_cc)[$current_r];
				$statesx = "\r\n" . $statex . "";
			} else {
				$statesx = "";
			}
			if (WC()->countries->get_states($current_cc)) {
				$states = WC()->countries->get_states($current_cc);
			} else {
				$states = "";
			}
			$address = "" . $fname . " " . $lname . "" . $ad1 . "" . $ad2 . "" . $ad2 . "" . $city . "" . $statesx . "" . $country . "" . $postcode . "";
			$addressx = html_entity_decode($address);
			$ship_label = __('Shipping:', 'woocommerce');
			$message .= urlencode("\r\n*" . $ship_label . "*");
			$message .= urlencode("" . $addressx . "\r\n");
		}
	}
	// Shipping method details
	if (!empty(WC()->session->get('chosen_shipping_methods'))) {
		$chosen_method_id = WC()->session->get('chosen_shipping_methods')[0];
		$available_rates = WC()->session->get('shipping_for_package_0')['rates'];
		if (isset($available_rates[$chosen_method_id])) {
			$rate = $available_rates[$chosen_method_id];
			$shipping_method_name = ucwords($rate->label);
			$shipping_cost = wc_price($rate->cost + array_sum($rate->taxes));
			$message .= urlencode("\r\n*Shipping Method:* " . $shipping_method_name);
			$message .= urlencode("\r\n*Cost:* " . html_entity_decode(strip_tags($shipping_cost)) . "\r\n");
		}
	}

	$message .= urlencode("\r\n*" . $total_label . ":*\r\n");
	$total_amount = wp_kses_data(WC()->cart->get_total());
	$message .= "" . $total_amount . "";
	$message .= urlencode("\r\n\r\n" . $thanks_label . "");
	$button_url = 'https://' . $wa_base . '.whatsapp.com/send?phone=' . $phonenumb . '&text=' . $message; ?>
	<div class="wc-proceed-to-checkout">
		<a id="sendbtn" href="<?php echo $button_url  ?>" target="<?php echo $target  ?>" class="wa-order-checkout checkout-button button">
			<?php echo $cart_button_text  ?>
		</a>
	</div>
<?php }
add_action('woocommerce_after_cart_totals', 'wa_order_add_button_to_cart_page');
function disable_checkout_button_no_shipping()
{
	$hide_checkout_button = get_option(sanitize_text_field('wa_order_option_cart_hide_checkout'));
	if ($hide_checkout_button === 'yes') {
		remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
	}
}
add_action('woocommerce_proceed_to_checkout', 'disable_checkout_button_no_shipping', 1);
