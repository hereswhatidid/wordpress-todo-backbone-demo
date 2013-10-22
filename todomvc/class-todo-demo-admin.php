<?php
/**
 * Plugin Name.
 *
 * @package   Todo_Demo_Admin
 * @author    Gabe Shackle <gabe@hereswhatidid.com>
 * @license   GPL-2.0+
 * @link      http://hereswhatidid.com
 * @copyright 2013 Gabe Shackle
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Todo_Demo_Admin
 * @author  Gabe Shackle <gabe@hereswhatidid.com>
 */
class Todo_Demo_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 * TODO:
		 *
		 * - Rename "Todo_Demo" to the name of your initial plugin class
		 *
		 */
		$plugin = Todo_Demo::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . 'plugin-name.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		add_action( 'wp_ajax_get_todos', array( $this, 'ajax_get_todos' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/styles.dev.css', __FILE__ ), array(), Todo_Demo::VERSION );
			// wp_enqueue_style( $this->plugin_slug .'-base-styles', plugins_url( 'css/base.css', __FILE__ ), array(), Todo_Demo::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
		wp_enqueue_script( $this->plugin_slug . '-todomvc-script', plugins_url( 'js/app.js', __FILE__ ), array( 'jquery', 'backbone', 'underscore' ), Todo_Demo::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Todo MVC Demo', $this->plugin_slug ),
			__( 'Todo MVC Demo', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	public function ajax_get_todos() {
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);

		$model = json_decode( file_get_contents( "php://input" ) );

		// var_dump( $request_method );

		$arrTodos = array();
		$arrTodos[] = array( 'id' => 1, 'title' => 'Todo Item 1', 'completed' => false );
		$arrTodos[] = array( 'id' => 2, 'title' => 'Todo Item 2', 'completed' => false );
		$arrTodos[] = array( 'id' => 3, 'title' => 'Todo Item 3', 'completed' => true );
		$arrTodos[] = array( 'id' => 4, 'title' => 'Todo Item 4', 'completed' => false );
		$arrTodos[] = array( 'id' => 5, 'title' => 'Todo Item 5', 'completed' => false );
		$arrTodos[] = array( 'id' => 6, 'title' => 'Todo Item 6', 'completed' => true );
		$arrTodos[] = array( 'id' => 7, 'title' => 'Todo Item 7', 'completed' => true );
		$arrTodos[] = array( 'id' => 8, 'title' => 'Todo Item 8', 'completed' => true );
		switch ( $request_method ) {
			case 'get':
				echo json_encode( $arrTodos );
				break;
			case 'post':
				echo json_encode( array( 'post' => $model ) );
				break;
			case 'put':
				echo json_encode( 'put' );
				break;
			case 'delete':
				echo json_encode( array( 'delete' => $model ) );
				break;
			default:
				echo json_encode( $arrTodos );
				break;
		}

		
		exit;
	}

}