<?php
/**
 * Helpers
 *
 * @package Quicker_Search
 */

/**
 * Return option.
 *
 * @since 1.0.0
 *
 * @param string $key Option key.
 * @return mixed Option value.
 */
function quicker_search_get_option( $key ) {
	$default_options = quicker_search_get_default_options();

	if ( empty( $key ) ) {
		return;
	}

	$current_options = (array) get_option( 'quicker_search_options' );
	$current_options = wp_parse_args( $current_options, $default_options );

	$value = null;

	if ( isset( $current_options[ $key ] ) ) {
		$value = $current_options[ $key ];
	}

	return $value;
}

/**
 * Return default options.
 *
 * @since 1.0.0
 *
 * @return array Default options.
 */
function quicker_search_get_default_options() {
	$default_options = array(
		'post_types' => array( 'post', 'page' ),
	);

	return apply_filters( 'quicker_search_option_defaults', $default_options );
}

/**
 * Return default value of given key.
 *
 * @since 1.0.0
 *
 * @param string $key Option key.
 * @return mixed Default option value.
 */
function quicker_search_get_default( $key ) {
	$value = null;

	$defaults = quicker_search_get_default_options();

	if ( ! empty( $key ) && isset( $defaults[ $key ] ) ) {
		$value = $defaults[ $key ];
	}

	return $value;
}

function quicker_search_is_allowed_post_type( $post_type, $post_types ) {
	$output = false;

	if ( empty( $post_type ) ) {
		return $output;
	}

	if ( ! is_array( $post_types ) || 0 === count( $post_types ) ) {
		return $output;
	}

	foreach ( $post_types as $type ) {
		if ( $post_type === $type ) {
			$output = true;
			break;
		}
	}

	return $output;
}
