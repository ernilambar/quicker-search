<?php
/**
 * Plugin Name: Quicker Search
 * Plugin URI:
 * Description: Quicker Search
 * Version: 1.0
 * Author: WEN Themes
 * Author URI:
 * Requires at least: 4.2
 * Tested up to: 4.4
 * Text Domain: quicker-search
 *
 * @package Quicker_Search
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Quicker_Search' ) ) :

    /**
     * Main Class.
     */
    class Quicker_Search {

        /**
         * Plugin version.
         *
         * @var string
         * @since 1.0.0
         */
        public $version = '1.0.0';

        /**
         * Plugin instance.
         *
         * @var Quicker_Search The single instance of the class.
         * @since 1.0.0
         */
        protected static $instance = null;

        /**
         * Main Quicker_Search Instance.
         *
         * Ensures only one instance of Quicker_Search is loaded or can be loaded.
         *
         * @since 1.0.0
         * @return Quicker_Search - Main instance.
         */
        public static function instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor.
         *
         * @since 1.0.0
         */
        function __construct() {
            $this->define_constants();
            $this->includes();
            $this->init_hooks();

            do_action( 'quicker_search_loaded' );
        }

        /**
         * Define Constants.
         *
         * @since 1.0.0
         * @access private
         */
        private function define_constants() {
            $this->define( 'QUICKER_SEARCH_PLUGIN_FILE', __FILE__ );
            $this->define( 'QUICKER_SEARCH_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'QUICKER_SEARCH_VERSION', $this->version );
            $this->define( 'QUICKER_SEARCH_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
            $this->define( 'QUICKER_SEARCH_PLUGIN_URI', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
        }

        /**
         * Define constant if not already set.
         *
         * @since 1.0.0
         * @access private
         *
         * @param string      $name Define key.
         * @param string|bool $value Define value.
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * Include required core files used in admin and on the frontend.
         *
         * @since 1.0.0
         */
        public function includes() {
            // include_once( 'includes/demobar-core-functions.php' );
            // include_once( 'includes/class-demobar-post-types.php' );
            // include_once( 'includes/class-demobar-install.php' );
            // include_once( 'includes/class-demobar-switcher.php' );

            if ( $this->is_request( 'admin' ) ) {
                require_once( 'includes/admin/class-quicker-search-admin.php' );
            }
        }


        /**
         * What type of request is this?
         * string $type ajax, frontend or admin.
         *
         * @since 1.0.0
         *
         * @param string $type Request type.
         * @return bool
         */
        private function is_request( $type ) {
            switch ( $type ) {
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined( 'DOING_AJAX' );
                case 'cron' :
                    return defined( 'DOING_CRON' );
                case 'frontend' :
                    return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
            }
        }

        /**
         * Hook into actions and filters.
         *
         * @since 1.0.0
         * @access private
         */
        private function init_hooks() {

            add_action( 'init', array( $this, 'init' ), 0 );
            add_action( 'admin_notices', array( $this, 'custom_admin_notices' ) );

        }

        /**
         * Plugin init.
         *
         * @since 1.0.0
         */
        function init() {

            // Load plugin text domain.
            load_plugin_textdomain( 'quicker-search', false, basename( dirname( __FILE__ ) ) . '/languages' );

        }

        /**
         * Custom admin notices.
         *
         * @since 1.0.0
         */
        function custom_admin_notices() {

            if ( ! current_user_can( 'manage_options' ) ) {
                return;
            }
            if ( true !== class_exists( 'WP_REST_Controller' ) ) {

                $string = 'Quicker Search Notice: WordPress REST API plugin is required.';
                $string .= ' <a href="https://wordpress.org/plugins/rest-api/" target="_blank">WordPress REST API</a>';
                echo '<div id="message" class="error">';
                echo '<p>' . $string . '</p>';
                echo '</div>';

            }

        }
    }

endif;

$GLOBALS['quicker_search'] = Quicker_Search::instance();
