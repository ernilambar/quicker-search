<?php
/**
 * Quicker Search Admin.
 *
 * @package Quicker_Search
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Quicker_Search_Admin class.
 */
class Quicker_Search_Admin {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		require_once( 'class-quicker-search-admin-essentials.php' );
	}
}

return new Quicker_Search_Admin();
