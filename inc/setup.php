<?php
/**
 * Setup
 *
 * @package Quicker_Search
 */

/**
 * Load assets.
 *
 * @since 1.0.0
 */
function quicker_search_load_assets() {
	$cur_screen = get_current_screen();

	$post_types = quicker_search_get_option( 'post_types' );

	if ( ! quicker_search_is_allowed_post_type( $cur_screen->post_type, $post_types ) ) {
		return;
	}

	$script_asset_path = QUICKER_SEARCH_DIR . '/build/search.asset.php';
	$script_asset      = file_exists( $script_asset_path ) ? require $script_asset_path : array(
		'dependencies' => array(),
		'version'      => filemtime( __FILE__ ),
	);

	wp_enqueue_style( 'quicker-search', QUICKER_SEARCH_URL . '/build/search.css', '', $script_asset['version'] );

	wp_register_script( 'quicker-search', QUICKER_SEARCH_URL . '/build/search.js', $script_asset['dependencies'], $script_asset['version'], true );

	$data = array(
		'admin_url' => admin_url(),
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
		'post_type' => $cur_screen->post_type,
	);

	wp_localize_script( 'quicker-search', 'QUICKER_SEARCH', $data );
	wp_enqueue_script( 'quicker-search' );
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

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$item = array();

			$item['id']    = get_the_ID();
			$item['title'] = get_the_title();

			$output[] = $item;
		}

		wp_reset_postdata();
	}

	return $output;
}

function quicker_search_get_posts_callback() {
	$keyword   = isset( $_REQUEST['keyword'] ) ? sanitize_text_field( $_REQUEST['keyword'] ) : '';
	$post_type = isset( $_REQUEST['post_type'] ) ? sanitize_text_field( $_REQUEST['post_type'] ) : '';

	$data = quicker_search_get_searched_posts( $keyword, $post_type );

	wp_send_json( $data );
	exit;
}

add_action( 'wp_ajax_qs_get_posts', 'quicker_search_get_posts_callback' );
add_action( 'wp_ajax_nopriv_qs_get_posts', 'quicker_search_get_posts_callback' );
