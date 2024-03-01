<?php
/**
 * WooCommerce Show Single Variations by Iconic plugin integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWL_Single_Variations' ) ) :

    /**
     * Class
     */
    class AWL_Single_Variations {

        /**
         * Main AWL_Single_Variations Instance
         *
         * Ensures only one instance of AWL_Single_Variations is loaded or can be loaded.
         *
         * @static
         * @return AWL_Single_Variations - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWL_Single_Variations Instance
         *
         * Ensures only one instance of AWL_Single_Variations is loaded or can be loaded.
         *
         * @static
         * @return AWL_Single_Variations - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            add_action( 'woocommerce_product_set_visibility', array( $this, 'woocommerce_product_set_visibility' ), 99, 2 );

        }

        /*
         * Update index table on bulk visibility change
         */
        public function woocommerce_product_set_visibility( $id, $terms ) {

            if ( is_ajax() && isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'iconic_wssv_process_product_visibility' ) {

                $sync = AWS()->get_settings( 'autoupdates' );

                if ( $terms && $sync !== 'false' ) {

                    $new_visibility = $terms;

                    if ( $new_visibility && ! AWS_Helpers::is_table_not_exist() ) {

                        global $wpdb;

                        $table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

                        $wpdb->update( $table_name, array( 'visibility' => $new_visibility ), array( 'id' => $id ) );

                        do_action('aws_cache_clear');

                    }

                }

            }

        }

    }

endif;

AWL_Single_Variations::instance();