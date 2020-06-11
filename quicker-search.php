<?php
/**
 * Plugin Name: Quicker Search
 * Plugin URI: https://github.com/ernilambar/quicker-search
 * Description: Quickly search posts or pages quickly in admin listing.
 * Version: 1.0.1
 * Author: Nilambar Sharma
 * Author URI: httpS://WWW.nilambar.net
 * Requires at least: 5.2
 * Tested up to: 5.4
 * Text Domain: quicker-search
 *
 * @package Quicker_Search
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'QUICKER_SEARCH_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'QUICKER_SEARCH_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'QUICKER_SEARCH_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

function quicker_search_load_assets() {
	wp_enqueue_style( 'quicker-search-smoothness', QUICKER_SEARCH_URL . '/css/smoothness/jquery-ui.css', '', '1.11.4' );

	wp_enqueue_style( 'quicker-search-style', QUICKER_SEARCH_URL . '/css/custom.css', '', '1.0.0' );

	wp_register_script( 'quicker-search-custom', QUICKER_SEARCH_URL . '/js/custom.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-autocomplete' ), '1.0.0', true );

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

add_action( 'admin_enqueue_scripts', 'quicker_search_load_assets' );
