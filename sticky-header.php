<?php
/**
 * Sticky Dropdown menu plugin by pixelsolution4it.
 *
 * @package   Sticky_Dropdown
 * @author    engrmostafijur <engr.mostafijur@gmail.com>
 * @license   GPL-2.0+
 * @link      http://pixelsolution4it.com
 * @copyright 2014 engrmostafijur
 *
 * @wordpress-plugin
 * Plugin Name:		Sticky Dropdown
 * Plugin URI:		http://pixelsolution4it.com
 * Description:		Adds Sticky Dropdown menu to your WordPress website
 * Version:			1.3
 * Author:			engrmostafijur
 * Author URI:		http://pixelsolution4it.com
 * Text Domain:		pixl-sticky-header
 * License:			GPL-2.0+
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:		/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-sticky-header.php' );
require_once( plugin_dir_path( __FILE__ ) . 'sticky-header-settings.php' );

add_action( 'plugins_loaded', array( 'Sticky_Dropdown', 'get_instance' ) );