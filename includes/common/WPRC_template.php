<?php

/**
 * Class templates the plugin.
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPRC
 * @subpackage WPRC/includes/common
 * @author     Dariusz Andryskowski
 */
class WPRC_Template {

	/**
	 * Load the selected admin template
	 */
	public static function LoadTemplate() {

		//Get template based on GET[page] value
		switch($_GET['page']) {
			case 'WppaHideWP':
					$template = '/view/admin/wprc-settings.php';
				break;
		}


		include_once( WPINCRC_DIR . $template );
	}

}