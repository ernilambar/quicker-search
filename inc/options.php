<?php
/**
 * Options
 *
 * @package Quicker_Search
 */

use Nilambar\Optioner\Optioner;

function quicker_search_register_optioner() {
	$obj = new Optioner();

	$obj->set_page(
		array(
			'page_title'     => esc_html__( 'Quicker Search', 'quicker-search' ),
			/* translators: %s: version. */
			'page_subtitle'  => sprintf( esc_html__( 'Version: %s', 'quicker-search' ), QUICKER_SEARCH_VERSION ),
			'menu_title'     => esc_html__( 'Quicker Search', 'quicker-search' ),
			'capability'     => 'manage_options',
			'menu_slug'      => 'quicker-search',
			'option_slug'    => 'quicker_search_options',
			'top_level_menu' => false,
		)
	);

	$obj->set_quick_links(
		array(
			array(
				'text' => 'Plugin Page',
				'url'  => 'https://github.com/ernilambar/quicker-search/',
				'type' => 'primary',
			),
			array(
				'text' => 'Get Support',
				'url'  => 'https://github.com/ernilambar/quicker-search/issues',
				'type' => 'secondary',
			),
		)
	);

	// Tab: qs_settings.
	$obj->add_tab(
		array(
			'id'    => 'qs_settings',
			'title' => esc_html__( 'Settings', 'quicker-search' ),
		)
	);

	// Field: post_types.
	$obj->add_field(
		'qs_settings',
		array(
			'id'      => 'post_types',
			'type'    => 'multicheck',
			'title'   => esc_html__( 'Post Types', 'quicker-search' ),
			'default' => quicker_search_get_default( 'post_types' ),
			'choices' => quicker_search_get_post_types_options(),
		)
	);

	$obj->set_sidebar(
		array(
			'render_callback' => 'quicker_search_render_admin_sidebar',
		)
	);

	$obj->run();
}

add_action( 'optioner_admin_init', 'quicker_search_register_optioner' );

/**
 * Render admin sidebar.
 *
 * @since 1.0.0
 *
 * @param Optioner $optioner_object Optioner object.
 */
function quicker_search_render_admin_sidebar( $optioner_object ) {
	$optioner_object->render_sidebar_box(
		array(
			'title'   => 'Help &amp; Support',
			'icon'    => 'dashicons-editor-help',
			'content' => '<h4>Questions, bugs or great ideas?</h4>
			<p><a href="https://github.com/ernilambar/quicker-search/issues" target="_blank">Create issue in the repo</a></p>',
		),
		$optioner_object
	);
}

/**
 * Get post types options.
 *
 * @since 1.0.0
 *
 * @return array Options.
 */
function quicker_search_get_post_types_options() {
	$output = array(
		'post' => esc_html__( 'Post', 'quicker-search' ),
		'page' => esc_html__( 'Page', 'quicker-search' ),
	);

	$args = array(
		'public'   => true,
		'_builtin' => false,
	);

	$custom_types = get_post_types( $args, 'objects' );

	if ( ! empty( $custom_types ) ) {
		foreach ( $custom_types as $item ) {
			$output[ $item->name ] = $item->labels->{'singular_name'};
		}
	}

	return $output;
}
