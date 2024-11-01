<?php
/*
 * Plugin Name: WP reCaptcha
 * Plugin URI:
 * Description: Add reCaptcha in form wordpress
 * Version:     1.0.0
 * Author:      Appsaur.co
 * Author URI:  http://www.appsaur.co/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-recaptcha
 * Domain Path: /wp-recaptcha
 *
 * WP reCaptcha is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP reCaptcha is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP reCaptcha. If not, see {URI to Plugin License}.
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

if (!defined('WPINCRC_VERSION')) {
	define('WPINCRC_VERSION', '1.0.0');
}

if (!defined('WPRC_PLUGIN_NAME')) {
	define('WPRC_PLUGIN_NAME', 'wp-recaptcha');
}

if (!defined('WPINCRC_DIR')) {
	define('WPINCRC_DIR', dirname(__FILE__) );
}

if (!defined('WPRC_LANG_DIR')) {
	define('WPRC_LANG_DIR', WPINCRC_DIR );
}

if (!defined('WPINCRC_PLUGIN_DIR')) {
	define('WPINCRC_PLUGIN_DIR', plugin_basename(__FILE__));
}


/* include class */
require_once plugin_dir_path(__FILE__) . 'includes/common/abstract/WPRC_detail.php';
require_once plugin_dir_path(__FILE__) . 'includes/common/WPRC_tools.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in classes/WPRC_activator.php
 */
function wprc_activate() {
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPRC_activator.php';
	WPRC_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in class/WPRC_deactivator.php
 */
function wprc_deactivate() {
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPRC_deactivator.php';
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPRC_tools.php';
	WPRC_Deactivator::deactivate();
}

/**
 * The code that runs during plugin uninstall.
 * This action is documented in class/WPRC_deactivator.php
 */
function wprc_uninstall() {
	require_once plugin_dir_path(__FILE__) . 'includes/common/WPRC_uninstallator.php';
	WPRC_Uninstallator::uninstall();
}

register_activation_hook(__FILE__, 'wprc_activate');
register_deactivation_hook(__FILE__, 'wprc_deactivate');
register_uninstall_hook(__FILE__, 'wprc_uninstall');


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/common/WPRC.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wprc_run() {
	if (is_admin) {
		$plugin = new WPRC();
		$plugin->run();
	}
}


wprc_run();