<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPRC
 * @subpackage WPRC/includes/admin
 * @author     Dariusz Andryskowski
 */
class WPRC_Admin extends WPRC_Detail {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = WPINCRC_VERSION;
		$this->setting_plugin = get_option($this->plugin_name);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// set file css
		wp_enqueue_style( $this->plugin_name, plugins_url( '/assets/css/wprc-admin.css', dirname(dirname(__FILE__)) ), array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// set file js
		wp_enqueue_script(  $this->plugin_name, plugins_url( '/assets/js/wprc-public.js', dirname(dirname(__FILE__)) ), array( 'jquery' ), $this->version, false );
	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/**
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */

		add_options_page( __('WP reCaptcha - Configuration', $this->plugin_name), 'WP reCaptcha', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 *
	 */
	public function add_action_links($links, $file) {

		if ($file == WPINCRC_PLUGIN_DIR) {
			$settings_link = '<a href="' . admin_url('/options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
		include_once(WPINCRC_DIR . '/view/admin/wprc-settings.php');
	}

	/**
	 *  Save the plugin options
	 *
	 * @since    1.0.0
	 */
	public function options_update() {

		// save data when click submit
		if (isset ($_REQUEST)) {
			$this->flush_changes();
		}
	}

	/**
	 * Flush the changes in htaccess
	 *
	 * @since    1.0.0
	 */
	public function flush_changes() {

		// save data in db
		register_setting( $this->plugin_name, $this->plugin_name, array($this, 'validate_form_settings_recaptcha') );

		//Flush the changes
		global $wp_rewrite;

		$wp_rewrite->flush_rules(true);
	}

	/**
	 * Validate all options fields
	 *
	 * @since    1.0.0
	 */
	public function validate_form_settings_recaptcha($input) {

		// santize input
		$input = $this->filter_sanitize_text_field($input);

		// settings google
		$valid['site_key']    = isset( $input['site_key'] ) ? $input['site_key'] : '';
		$valid['secrete_key'] = isset( $input['secrete_key'] ) ? $input['secrete_key'] : '';

		// settings plugin reCaptcha
		$valid['captcha_login'] = isset( $input['captcha_login'] ) ? $input['captcha_login'] : '';
		$valid['captcha_registration'] = isset( $input['captcha_registration'] ) ? $input['captcha_registration'] : '';
		$valid['captcha_reset_pass'] = isset( $input['captcha_reset_pass'] ) ? $input['captcha_reset_pass'] : '';
		$valid['captcha_comment'] = isset( $input['captcha_comment'] ) ? $input['captcha_comment'] : '';
		$valid['error_message'] = isset( $input['error_message'] ) ? $input['error_message'] : '';

		return $valid;
	}

	/**
	 * Santize string from array
	 * @param $array
	 * @return array|bool
	 */
	public function filter_sanitize_text_field($array) {

		$santizeArray = array();

		if (!is_array($array)) {
			return false;
		}

		foreach ($array as $key => $val ) {
			$santizeArray[$key] = sanitize_text_field($val);
		}

		return $santizeArray;
	}
}