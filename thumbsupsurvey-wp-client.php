<?php
/**
 * Plugin Name:       Thumbs Up Survey
 * Plugin URI:        https://thumbsupsurvey.com
 * Description:       Thumbs Up Survey plugin for WP, provides the necessary shortcodes to easily use our surveys in pages and posts!
 * Version: 1.3.6
 * Author:            Nexus Media Co Ltd
 * Author URI:        https://nexusmedialtd.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined("WPINC")) {
    die;
}

define( 'THUMBSUPSURVEY_WP_PLUGIN_FILE', __FILE__ );
define("THUMBSUPSURVEY_WP_PLUGIN_VER", "1.3.6");
define("THUMBSUPSURVEY_WP_PLUGIN_DOMAIN", "thumbsupsurvey");
define("THUMBSUPSURVEY_WP_PLUGIN_URL", plugin_dir_url(__FILE__));
define("THUMBSUPSURVEY_WP_PLUGIN_DIR", __DIR__);
define("THUMBSUPSURVEY_SITE", "https://thumbsupsurvey.com");
define("THUMBSUPSURVEY_WP_PLUGIN_SRV", "https://thumbsupsurvey.com");
define("THUMBSUPSURVEY_WP_PLUGIN_DEV", "");

// System
include_once(THUMBSUPSURVEY_WP_PLUGIN_DIR . "/lib/init.php");
include_once(THUMBSUPSURVEY_WP_PLUGIN_DIR . "/lib/functions.php");
include_once(THUMBSUPSURVEY_WP_PLUGIN_DIR . "/lib/settings.php");

// Shortcodes
include_once(THUMBSUPSURVEY_WP_PLUGIN_DIR . "/shortcodes/client.php");
