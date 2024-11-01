<?php

/**
 * The file that defines the uninstall plugin
 *
 * A class definition that includes attributes and functions uninstall plugin
 *
 * @link       http://appsaur.co
 * @since      1.0.0
 *
 * @package    WPRC
 * @subpackage WPRC/includes/common
 * @author     Dariusz Andryskowski
 */
class WPRC_Uninstallator {

    public $rewrite;

    /**
     * Uninstall plugin
     */
    public static function uninstall() {

        delete_option(WPRC_PLUGIN_NAME);


        flush_rewrite_rules();
    }
}