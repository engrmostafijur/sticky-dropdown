<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Sticky_Dropdown
 * @author    engrmostafijur <engr.mostafijur@gmail.com>
 * @license   GPL-2.0+
 * @link      http://pixelsolution4it.com
 * @copyright 2014 engrmostafijur
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin settings
delete_option( 'pixl_Sticky_Dropdown' );