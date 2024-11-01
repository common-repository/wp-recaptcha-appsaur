<?php

/**
 * The file that defines the core detail plugin classes
 *
 * A class definition that includes attributes and functions used in
 * public side and the admin side.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPPA
 * @subpackage WPPA/includes/common
 * @author     Dariusz Andryskowski
 */
abstract class WPRC_Detail {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPPA_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Variable for class tools
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPRC_Tools   mixed
	 */
	protected $tools;

	/**
	 * Variable for class WPRC_admin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPRC_Admin   mixed
	 */
	protected $obj_wprc_admin;

	/**
	 * Site url the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPRC_detail    string  site url
	 */
	protected $site_url;

	/**
	 * Options plugin
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPRC_detail    array settings
	 */
	protected $setting_plugin;

	/**
	 * Site key
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPRC_detail    string
	 */
	static protected $site_key;

	/**
	 * Siecret key
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPRC_detail    string
	 */
	static protected $secret_key;

	/**
	 * Error message
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPRC_detail    string
	 */
	static protected $error_message;

	/**
	 * Language wordpress
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WPRC_detail    string language
	 */
	static protected $language;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->set_plugin_name($plugin_name);
		$this->version = WPINCRC_VERSION;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @param     string    name plugin
	 * @return    string    The name of the plugin.
	 */
	public function set_plugin_name($plugin_name) {
		$this->plugin_name = $plugin_name;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WPRC_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Set settings plugin.
	 *
	 * @since     1.0.0
	 */
	public function set_settings($setting_plugin) {
		return $this->setting_plugin = $setting_plugin;
	}
	/**
	 * Get settings plugin.
	 *
	 * @since     1.0.0
	 * @return    array    Settings  plugin.
	 */
	public function get_settings() {
		return $this->setting_plugin;
	}

	/**
	 * Function get the last path in a URL eg. wp-login.php
	 * @return string
	 */
	public function get_request_uri() {
		return basename(dirname(strtolower($_SERVER['REQUEST_URI'])));
	}

	/**
	 * Function add cookies for new admin slug
	 */
	public function set_admin_cookie($auth_cookie, $expire) {
		$wp_slug_wp_admin = !empty($this->setting_plugin['slug_wp_admin']) ? $this->setting_plugin['slug_wp_admin'] : 'wp-admin';

		setcookie(is_ssl() ? SECURE_AUTH_COOKIE : AUTH_COOKIE, $auth_cookie, $expire, SITECOOKIEPATH . $wp_slug_wp_admin, COOKIE_DOMAIN, is_ssl(), true);
	}


}