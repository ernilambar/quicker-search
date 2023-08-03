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
	require_once QUICKER_SEARCH_DIR . '/vendor/ernilambar/optioner/optioner.php';
	require_once QUICKER_SEARCH_DIR . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
}

require_once QUICKER_SEARCH_DIR . '/inc/helpers.php';
require_once QUICKER_SEARCH_DIR . '/inc/setup.php';
require_once QUICKER_SEARCH_DIR . '/inc/options.php';

// Updater.
$nsnd_update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker( 'https://github.com/ernilambar/quicker-search', __FILE__, QUICKER_SEARCH_SLUG );
$nsnd_update_checker->getVcsApi()->enableReleaseAssets();
