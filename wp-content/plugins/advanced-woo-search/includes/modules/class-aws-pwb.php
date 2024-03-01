<?php
/**
 * Perfect Brands for WooCommerce support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_PWB' ) ) :

    /**
     * Class
     */
    class AWS_PWB {

        /**
         * Main AWS_PWB Instance
         *
         * Ensures only one instance of AWS_PWB is loaded or can be loaded.
         *
         * @static
         * @return AWS_PWB - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_PWB Instance
         *
         * Ensures only one instance of AWS_PWB is loaded or can be loaded.
         *
         * @static
         * @return AWS_PWB - Main instance
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

            add_filter( 'aws_search_page_filters', array( $this, 'search_page_filters' ) );

        }

        /*
         * Update filter widget for pwb-brand taxonomy
         */
        public function search_page_filters( $filters ) {

            if ( isset( $_GET['pwb-brand-filter'] ) ) {

                $terms_arr = explode( ',', $_GET['pwb-brand-filter'] );

                if ( preg_match( '/[a-z]/', $_GET['pwb-brand-filter'] ) ) {
                    $new_terms_arr = array();
                    foreach ( $terms_arr as $term_slug ) {
                        $term = get_term_by('slug', $term_slug, 'pwb-brand' );
                        if ( $term ) {
                            $new_terms_arr[] = $term->term_id;
                        }
                    }
                    $terms_arr = $new_terms_arr;
                }

                $filters['tax']['pwb-brand'] = array(
                    'terms' => $terms_arr,
                    'operator' => 'OR'
                );

            }

            return $filters;

        }

    }

endif;

AWS_PWB::instance();