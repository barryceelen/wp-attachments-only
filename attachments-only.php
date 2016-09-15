<?php
/**
 * Attachments Only.
 *
 * @package   Attachments_Only
 * @author    Barry Ceelen <b@rryceelen.com>
 * @license   GPL-3.0+
 * @link      https://github.com/barryceelen/wp-attachments-only
 * @copyright 2015 Barry Ceelen
 *
 * @wordpress-plugin
 * Plugin Name:       Attachments Only
 * Plugin URI:        https://github.com/barryceelen/wp-attachments-only
 * Description:       Simplify the default WordPress media view by removing all functionality except adding and deleting attachments.
 * Version:           0.0.4
 * Author:            Barry Ceelen
 * Author URI:        https://github.com/barryceelen
 * Text Domain:       attachments-only
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/barryceelen/wp-attachments-only
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'class-attachments-only.php' );
	add_action( 'init', array( 'Attachments_Only', 'get_instance' ) );
}
