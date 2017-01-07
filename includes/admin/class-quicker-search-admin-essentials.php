<?php
/**
 * Quicker Search Admin Essentials.
 *
 * @package Quicker_Search
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Quicker_Search_Admin_Essentials' ) ) :

    /**
     * Quicker_Search_Admin_Essentials Class.
     */
    class Quicker_Search_Admin_Essentials {

        /**
         * Constructor.
         */
        public function __construct() {

            add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

        }

        /**
         * Scripts and styles.
         *
         * @since 1.0.0
         */
        function scripts() {

            wp_enqueue_style( 'quicker-search-smoothness', QUICKER_SEARCH_PLUGIN_URL . '/css/smoothness/jquery-ui.css', '', '1.11.4' );

            wp_enqueue_style( 'quicker-search-style', QUICKER_SEARCH_PLUGIN_URL . '/css/custom.css', '', '1.0.0' );

            wp_register_script( 'quicker-search-custom', QUICKER_SEARCH_PLUGIN_URL . '/js/custom.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-autocomplete' ), '1.0.0', true );

            global $wp_post_types;
            $cur_screen = get_current_screen();

            $correct_post_type = '';
            if ( ! empty( $cur_screen->post_type ) ) {
                if ( isset( $wp_post_types[ $cur_screen->post_type ]->show_in_rest ) && 1 === absint( $wp_post_types[ $cur_screen->post_type ]->show_in_rest ) ) {
                    $correct_post_type = $cur_screen->post_type;
                }
            }

            $custom_args = array(
                'home_url'  => home_url(),
                'admin_url' => admin_url(),
                'post_type' => $correct_post_type,
                'tax_type'  => $cur_screen->taxonomy,
            );
            wp_localize_script( 'quicker-search-custom', 'Quicker_Search_Settings', $custom_args );
            wp_enqueue_script( 'quicker-search-custom' );
        }

    }

endif;

new Quicker_Search_Admin_Essentials();
