<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPRC
 * @subpackage WPRC/includes/common
 * @author     Dariusz Andryskowski
 */
class WPRC extends WPRC_Detail {

    /**
     * Include class files from classes path
     * @param string $dir m - ain dir
     * @param string $path path file
     * @param string $class included file name without .php
     */
    public static function classInclude($dir, $path, $class) {

        if (file_exists($dir . $path . $class . '.php')) {
            require_once $dir . $path . $class . '.php';
        }
    }

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->set_plugin_name(WPRC_PLUGIN_NAME);
        $this->version = WPINCRC_VERSION;
        $this->site_url = trailingslashit( site_url() );
        $this->setting_plugin = get_option($this->plugin_name);

        // site key generate on google
        self::$site_key = ( isset( $this->setting_plugin['site_key'] ) && !empty( $this->setting_plugin['site_key'] ) )  ? $this->setting_plugin['site_key'] : '';
        // secret key generate on google
        self::$secret_key = ( isset( $this->setting_plugin['secrete_key'] ) && !empty( $this->setting_plugin['secrete_key'] ) ) ? $this->setting_plugin['secrete_key'] : '';
        // text error message when wrong walidate reCaptcha
        self::$error_message = ( isset( $this->setting_plugin['error_message'] ) && !empty( $this->setting_plugin['error_message'] ) ) ? $this->setting_plugin['error_message'] : __("<strong>ERROR</strong>: Use a captcha to validate ", $this->plugin_name);


        $this->load_dependencies();
        $this->set_init();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }


    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - WPRC_Loader. Orchestrates the hooks of the plugin.
     * - WPRC_i18n. Defines internationalization functionality.
     * - WPRC_Admin. Defines all hooks for the admin area.
     * - WPRC_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class tools WPRC
         */
        WPRC::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/common/', 'WPRC_tools');
        /**
         * The class responsible for load templates
         */
        WPRC::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/common/', 'WPRC_loader');

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        WPRC::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/common/', 'WPRC_template');

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        WPRC::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/common/', 'WPRC_i18n');

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        WPRC::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/admin/', 'WPRC_admin');

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        WPRC::classInclude( plugin_dir_path( dirname( dirname( __FILE__ )) ), '/includes/public/', 'WPRC_public');
    }


    /**
     * Get default data and set default configuration
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_init() {
        $this->loader = new WPRC_Loader();
        $this->tools = new WPRC_Tools($this->get_plugin_name(), $this->get_version());

        $this->obj_wprc_admin = new WPRC_Admin( $this->get_plugin_name(), $this->get_version() );
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the WPRC_i18n class in order to set the domain and to register the hook
     * with WordPress.     * @since    1.0.0
     * @access   private private
     */
    private function set_locale() {

        $plugin_i18n = new WPRC_I18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $this->loader->add_action( 'admin_enqueue_scripts', $this->obj_wprc_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $this->obj_wprc_admin, 'enqueue_scripts' );

        // Save/Update our plugin options
        $this->loader->add_action( 'admin_init', $this->obj_wprc_admin, 'options_update');

        // Add menu item
        $this->loader->add_action( 'admin_menu', $this->obj_wprc_admin, 'add_plugin_admin_menu' );

        // Add Settings link to the
        $this->loader->add_filter('plugin_action_links', $this->obj_wprc_admin, 'add_action_links', 10, 2);

    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new WPRC_Public( $this->get_plugin_name(), $this->get_version() );


        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        // check exist site key and securite key
        if ( isset($this->setting_plugin[site_key]) && $this->setting_plugin[site_key] != "" &&
            isset($this->setting_plugin[secrete_key]) && $this->setting_plugin[secrete_key] != ""
        ) {

            // add captcha header script to WordPress header
            $this->loader->add_action('wp_head', $this->tools, 'add_header_script_recaptcha');

            /**
             * Login Form
             */
            if ( isset( $this->setting_plugin[captcha_login] ) && $this->setting_plugin[captcha_login] == true ) {
                // add captcha header script in header
                $this->loader->add_action('login_enqueue_scripts', $this->tools, 'add_header_script_recaptcha');

                // display captcha in login form
                $this->loader->add_action('login_form', $this->tools, 'display_captcha_form');

                // authenticate the captcha answer
                $this->loader->add_action('wp_authenticate_user', $this->tools, 'validate_captcha', 10, 2);
            }

            /**
             * Register Form
             */

            if ( isset( $this->setting_plugin[captcha_registration] ) && $this->setting_plugin[captcha_registration] == true ) {
                // display captcha in registration form
                $this->loader->add_action( 'register_form', $this->tools, 'display_captcha_form' );

                // authenticate the captcha answer
                $this->loader->add_action( 'register_post', $this->tools, 'validate_captcha', 10, 3 );

            }

            /**
             * Forgot password Form
             */
            if ( isset( $this->setting_plugin[captcha_reset_pass] ) && $this->setting_plugin[captcha_reset_pass] == true ) {
                // display captcha in lostpassword form
                $this->loader->add_action( 'lostpassword_form', $this->tools, 'display_captcha_form' );

                // authenticate the captcha answer
                $this->loader->add_filter( 'allow_password_reset', $this->tools, 'validate_captcha_lost_password', 10, 2 );
            }

            /**
             * Comment Form
             */
            if ( isset( $this->setting_plugin[captcha_reset_pass] ) && $this->setting_plugin[captcha_reset_pass] == true ) {

                // adds the captcha in comment form
                $this->loader->add_action( 'comment_form', $this->tools, 'display_captcha_form' );
                $this->loader->add_action( 'comment_form_after_fields', $this->tools, 'display_captcha_form', 1 );
                $this->loader->add_action( 'comment_form_logged_in_after', $this->tools, 'display_captcha_form', 1 );

                // authenticate the captcha answer
                $this->loader->add_action( 'preprocess_comment', $this->tools, 'validate_captcha_comment'  );
            }
        }
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }
}