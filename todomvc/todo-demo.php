<?php
/**
 * @package   Todo_Demo
 * @author    Gabe Shackle <gabe@hereswhatidid.com>
 * @license   GPL-2.0+
 * @link      http://hereswhatidid.com
 * @copyright 2013 Gabe Shackle
 *
 * @wordpress-plugin
 * Plugin Name:       Todo MVC Demo
 * Plugin URI:        http://hereswhatidid.com
 * Description:       Sample MVC demo using WordPress and Backbone
 * Version:           1.0.0
 * Author:            Gabe Shackle
 * Author URI:        http://hereswhatidid.com/
 * Text Domain:       todo-demo-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-todo-demo.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-todo-demo-admin.php' );

register_activation_hook( __FILE__, array( 'Todo_Demo', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Todo_Demo', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Todo_Demo', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'Todo_Demo_Admin', 'get_instance' ) );