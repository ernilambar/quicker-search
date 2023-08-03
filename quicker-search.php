<?php
/**
 * Plugin Name: Quicker Search
 * Plugin URI: https://github.com/ernilambar/quicker-search
 * Description: Quickly search posts or pages in admin listing.
 * Version: 1.0.5
 * Author: Nilambar Sharma
 * Author URI: https://www.nilambar.net
 * Requires at least: 6.0
 * Tested up to: 6.3
 * Text Domain: quicker-search
 *
 * @package Quicker_Search
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'QUICKER_SEARCH_VERSION', '1.0.5' );
define( 'QUICKER_SEARCH_BASENAME', basename( __DIR__ ) );
define( 'QUICKER_SEARCH_SLUG', 'quicker-search' );
define( 'QUICKER_SEARCH_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'QUICKER_SEARCH_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

// Include autoload.
if ( file_exists( QUICKER_SEARCH_DIR . '/vendor/autoload.php' ) ) {
	require_once QUICKER_SEARCH_DIR . '/vendor/autoload.php';
	require_once QUICKER_SEARCH_DIR . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
}

/**
 * Load assets.
 *
 * @since 1.0.0
 */
function quicker_search_load_assets() {
	$script_asset_path = QUICKER_SEARCH_DIR . '/build/search.asset.php';
	$script_asset      = file_exists( $script_asset_path ) ? require $script_asset_path : array(
		'dependencies' => array(),
		'version'      => filemtime( __FILE__ ),
	);

	wp_enqueue_style( 'quicker-search-style', QUICKER_SEARCH_URL . '/build/search.css', '', $script_asset['version'] );

	wp_register_script( 'quicker-search-custom', QUICKER_SEARCH_URL . '/build/search.js', $script_asset['dependencies'], $script_asset['version'], true );

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
		'rest_url'  => rest_url(),
		'admin_url' => admin_url(),
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'post_type' => $correct_post_type,
		'tax_type'  => $cur_screen->taxonomy,
	);

	wp_localize_script( 'quicker-search-custom', 'quickerSearchSettings', $custom_args );
	wp_enqueue_script( 'quicker-search-custom' );
}

add_action( 'admin_enqueue_scripts', 'quicker_search_load_assets' );

function quicker_search_get_searched_posts( $keyword, $post_type ) {
	$output = array();

	$qargs = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => 5,
		'no_found_rows'  => true,
		's'              => $keyword,
	);

	$the_query = new WP_Query( $qargs );

	if ( $the_query->have_posts()) {
		while ( $the_query->have_posts()) {
			$the_query->the_post();

			$item = array();

			$item['id'] = get_the_ID();
			$item['title'] = get_the_title();

			$output[] = $item;
		}

		wp_reset_postdata();
	}

	return $output;
}

function quicker_search_get_posts_callback() {
	$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
	$post_type = isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : '';
	$data = array(
		'id' => 1,
		'title' => 'Nepal',
		'key' => $keyword
	);

	$data = quicker_search_get_searched_posts( $keyword, $post_type );

	wp_send_json( $data );
	exit;
}

add_action('wp_ajax_qs_get_posts', 'quicker_search_get_posts_callback');
add_action('wp_ajax_nopriv_qs_get_posts', 'quicker_search_get_posts_callback');

// Updater.
$nsnd_update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker( 'https://github.com/ernilambar/quicker-search', __FILE__, QUICKER_SEARCH_SLUG );
$nsnd_update_checker->getVcsApi()->enableReleaseAssets();
