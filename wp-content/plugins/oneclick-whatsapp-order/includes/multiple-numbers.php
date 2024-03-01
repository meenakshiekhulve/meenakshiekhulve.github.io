<?php

/**
 * Inspired by: Jean Livino (jeanlivino)
 * Source: https://github.com/jeanlivino/whatsapp-redirect-wordpress-plugin
 */
// Create a Custom Post Type for WhatsApp Numbers
function wa_order_multiple_numbers()
{
  register_post_type(
    'wa-order-numbers',
    array(
      'labels' => array(
        'name' => __('WhatsApp Numbers', 'oneclick-wa-order'),
        'singular_name' => __('WhatsApp Number', 'oneclick-wa-order'),
        'add_new_item' => __('Add WhatsApp Number', 'oneclick-wa-order'),
        'add_new' => __('Add WhatsApp Number', 'oneclick-wa-order')
      ),
      'show_in_menu' => false,
      'public' => true,
      'publicly_queryable' => false,
      'has_archive' => false,
      'rewrite' => array('slug' => 'waon', 'with_front' => false),
      'supports' => array('title')
    )
  );
}
add_action('init', 'wa_order_multiple_numbers');

/**
 * Create the metabox to save number
 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
 */
function wa_order_multiple_numbers_create_metabox()
{
  add_meta_box(
    'wa_order_phonenumbers_metabox', // Metabox ID
    __('Set a Number', 'oneclick-wa-order'), // Title to display
    'wa_order_phonenumbers_render_metabox', // Function to call that contains the metabox content
    'wa-order-numbers', // Post type to display metabox on
    'normal', // Where to put it (normal = main column, side = sidebar, etc.)
    'default' // Priority relative to other metaboxes
  );
}
add_action('add_meta_boxes', 'wa_order_multiple_numbers_create_metabox');
function wa_order_phonenumbers_render_metabox()
{
  // Variables
  global $post; // Get the current post data
  $phone = get_post_meta($post->ID, 'wa_order_phone_number_input', true); // Get the saved values

?>
  <table class="form-table">
    <tbody>
      <tr class="wa_order_number">
        <th scope="row">
          <label class="wa_order_number_label" for="wa_order_phone_number_input"><b><?php esc_html_e('WhatsApp Number', 'oneclick-wa-order'); ?></b></label>
        </th>
        <td>
          <input type="text" name="wa_order_phone_number_input" class="wa_order_input" id="wa_order_phone_number_input" value="<?php echo esc_attr($phone); ?>" placeholder="<?php esc_attr_e('e.g. 6281234567890', 'oneclick-wa-order'); ?>">
          <p class="description">
            <?php esc_html_e('Enter number including country code, e.g. 6281234567890', 'oneclick-wa-order'); ?>
          </p>
        </td>
      </tr>
    </tbody>
  </table>
  <div class="wa-return-to-setting">
    <p>
      <a href="<?php echo esc_url(admin_url('admin.php?page=wa-order&tab=welcome')); ?>">
        <?php esc_html_e('Click here to return to Global Settings page.', 'oneclick-wa-order'); ?>
      </a>
    </p>
  </div>
<?php
  wp_nonce_field('wa_order_phonenumbers_metabox_nonce', 'wa_order_phonenumbers_metabox_process');
}

//
// Save the phone data
//
function wa_order_multiple_numbers_save_metabox($post_id, $post)
{
  // Verify that our security field exists. If not, bail.
  if (!isset($_POST['wa_order_phonenumbers_metabox_process'])) return;

  // Verify data came from edit/dashboard screen
  if (!wp_verify_nonce($_POST['wa_order_phonenumbers_metabox_process'], 'wa_order_phonenumbers_metabox_nonce')) {
    return $post_id;
  }

  // Verify user has permission to edit post
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }

  // Check that our custom field is being passed along
  if (!isset($_POST['wa_order_phone_number_input'])) {
    return $post_id;
  }

  // Sanitize the submitted phone number
  $sanitized_phone = sanitize_text_field($_POST['wa_order_phone_number_input']);

  // Save our submissions to the database
  update_post_meta($post_id, 'wa_order_phone_number_input', $sanitized_phone);
}
add_action('save_post', 'wa_order_multiple_numbers_save_metabox', 10, 2);

/**
 * Customize the CPT WhatsApp Number Notices
 * Original code by Welcher
 * @link https://wordpress.stackexchange.com/questions/268379/how-to-customize-post-edit-notices
 */
add_filter('post_updated_messages', 'wa_order_multiple_numbers_updated_messages');
function wa_order_multiple_numbers_updated_messages($messages)
{
  global $post;
  if ('wa-order-numbers' != get_post_type($post)) {
    return $messages;
  }
  $messages['wa-order-numbers'] = array(
    0  => '', // Unused. Messages start at index 1.
    1  => esc_html__('WhatsApp Number updated.', 'oneclick-wa-order'),
    2  => esc_html__('WhatsApp Number updated.', 'oneclick-wa-order'),
    3  => esc_html__('WhatsApp Number deleted.', 'oneclick-wa-order'),
    4  => esc_html__('WhatsApp Number updated.', 'oneclick-wa-order'),
    5  => isset($_GET['revision']) ? sprintf(esc_html__('WhatsApp Number restored to revision from %s', 'oneclick-wa-order'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
    6  => esc_html__('WhatsApp Number successfully added.', 'oneclick-wa-order'),
    7  => esc_html__('WhatsApp Number saved.', 'oneclick-wa-order'),
    8  => esc_html__('WhatsApp Number submitted.', 'oneclick-wa-order'),
    9  => sprintf(
      esc_html__('WhatsApp Number scheduled for: <strong>%1$s</strong>.', 'oneclick-wa-order'),
      date_i18n(__('M j, Y @ G:i', 'oneclick-wa-order'), strtotime($post->post_date))
    ),
    10 => esc_html__('WhatsApp Number draft updated.', 'oneclick-wa-order')
  );

  return $messages;
}

/**
 * Validate the Phone Number Metabox Before Publishing
 * Original code by englebip
 * @link https://wordpress.stackexchange.com/questions/42013/prevent-post-from-being-published-if-custom-fields-not-filled
 */
// Check, Validate and Show Error Notice
// Save WhatsApp Number
add_action('save_post', 'wa_order_number_save_number_field', 10, 2);
function wa_order_number_save_number_field($pid, $post)
{
  if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || $post->post_status == 'auto-draft' || $post->post_type != 'wa-order-numbers') {
    return;
  }

  // Check if the phone number input exists in the POST data
  if (isset($_POST['wa_order_phone_number_input'])) {
    $sanitized_phone = sanitize_text_field($_POST['wa_order_phone_number_input']);
    update_post_meta($pid, 'wa_order_phone_number_input', $sanitized_phone);
  } else {
    // If the phone number input is not set, you might want to delete the meta key or handle it accordingly
    delete_post_meta($pid, 'wa_order_phone_number_input');
  }
}

// Validate and Prevent Publishing if WhatsApp Number is Empty
add_action('save_post', 'wa_order_completion_validator', 20, 2);
function wa_order_completion_validator($pid, $post)
{
  if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || $post->post_status == 'auto-draft' || $post->post_type != 'wa-order-numbers') {
    return;
  }
  $wa_number = get_post_meta($pid, 'wa_order_phone_number_input', true);
  if (empty($wa_number) && (isset($_POST['publish']) || isset($_POST['save'])) && $_POST['post_status'] == 'publish') {
    global $wpdb;
    $wpdb->update($wpdb->posts, array('post_status' => 'pending'), array('ID' => $pid));

    add_filter('redirect_post_location', function ($location) {
      return add_query_arg('message', '4', $location);
    });
    set_transient('wa_order_number_empty_notice', true, 5 * MINUTE_IN_SECONDS);
  }
}

// Show admin notice if the WhatsApp number is empty
add_action('admin_notices', function () {
  if (get_transient('wa_order_number_empty_notice')) {
    echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('OneClick Chat to Order requires a WhatsApp Number to be set!', 'oneclick-wa-order') . '</p></div>';
    delete_transient('wa_order_number_empty_notice');
  }
});

// If the WhatsApp number is empty, show notice
add_action('admin_notices', 'wa_order_check_if_number_empty');
function wa_order_check_if_number_empty()
{
  global $typenow, $pagenow;
  // Check if we are on the specific post type edit screen
  if (in_array($pagenow, array('post.php', 'post-new.php')) && $typenow == 'wa-order-numbers') {
    $wa_number = get_post_meta(get_the_ID(), 'wa_order_phone_number_input', true);
    // Check if WhatsApp number is empty
    if (empty($wa_number)) {
      $error = esc_html__('OneClick Chat to Order requires a WhatsApp number to be set! Please add a valid and active WhatsApp number.', 'oneclick-wa-order');
      printf('<div class="error"><p><strong>%s</strong></p></div>', $error);
    }
  }
}

// WA Number Selection
if (!function_exists('wa_order_phone_numbers_dropdown')) {
  function wa_order_phone_numbers_dropdown($args)
  {
    // WP_Query arguments
    $query_args = array(
      'post_type'      => 'wa-order-numbers',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'title',
      'order'          => 'ASC'
    );

    // The Query
    $the_query = new WP_Query($query_args);

    // Dropdown HTML
    $name = isset($args['name']) ? 'name="' . esc_attr($args['name']) . '" ' : '';
    $multiple = isset($args['multiple']) ? 'multiple' : '';

    echo '<select ' . $name . 'id="" class="wa_order-admin-select2 regular-text" ' . $multiple . '>';

    // Loop through posts
    if ($the_query->have_posts()) {
      while ($the_query->have_posts()) {
        $the_query->the_post();
        $phonenumb = get_post_meta(get_the_ID(), 'wa_order_phone_number_input', true);
        $selected = '';

        if (isset($args['selected'])) {
          if ($multiple) {
            $selected = in_array(get_post_field('post_name'), $args['selected']) ? 'selected="selected"' : '';
          } else {
            $selected = (get_post_field('post_name') == $args['selected']) ? 'selected="selected"' : '';
          }
        }

        echo '<option value="' . esc_attr(get_post_field('post_name')) . '" ' . $selected . '>' . esc_html(get_the_title()) . ' - ' . esc_html($phonenumb) . '</option>';
      }
      wp_reset_postdata();
    }

    echo '</select>';
  }
}

// WA Number Selection for Shortcode generator
if (!function_exists('wa_order_phone_numbers_dropdown_shortcode_generator')) {
  function wa_order_phone_numbers_dropdown_shortcode_generator($args)
  {
    // Prepare arguments
    $name     = isset($args['name']) ? 'name="' . esc_attr($args['name']) . '" ' : '';
    $multiple = isset($args['multiple']) ? 'multiple' : '';

    // WP_Query arguments
    $query_args = array(
      'post_type'      => 'wa-order-numbers',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'title',
      'order'          => 'ASC'
    );

    // The Query
    $the_query = new WP_Query($query_args);

    // Dropdown HTML
    echo '<select ' . $name . 'onChange="generateWAshortcode();" id="selected_wa_number" class="wa_order-admin-select2 regular-text" ' . $multiple . '>';

    // Loop through posts
    if ($the_query->have_posts()) {
      while ($the_query->have_posts()) {
        $the_query->the_post();
        $phonenumb = get_post_meta(get_the_ID(), 'wa_order_phone_number_input', true);
        $selected  = '';

        if (isset($args['selected'])) {
          if ($multiple) {
            $selected = in_array(get_the_title(), $args['selected']) ? 'selected="selected"' : '';
          } else {
            $selected = (get_the_title() === $args['selected']) ? 'selected="selected"' : '';
          }
        }

        echo '<option value="' . esc_attr($phonenumb) . '" ' . $selected . '>' . esc_html(get_the_title()) . ' - ' . esc_html($phonenumb) . '</option>';
      }
      wp_reset_postdata();
    }

    echo '</select>';
  }
}
