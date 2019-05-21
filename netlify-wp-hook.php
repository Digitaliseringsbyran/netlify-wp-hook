<?php
/**
 * Plugin Name: netlify-wp-hook
 * Plugin URI:  https://github.com/digitaliseringsbyran/netlify-wp-hook
 * Description: Trigger a Netlify build on post save/update
 * Version:     1.0.0
 * Author:      Digitaliseringsbyån
 * Author URI:  https://digitaliseringsbyran.se
 * License:     MIT
 * Text Domain: netlify-wp-hook
 */

define('NWH_PLUGIN_NAME', 'Netlify WP Hook');
define('NWH_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('NWH_PLUGIN_URL', plugins_url('', __FILE__));

if (file_exists(NWH_PLUGIN_PATH . 'vendor/autoload.php')) {
	require_once NWH_PLUGIN_PATH . 'vendor/autoload.php';
}

if (file_exists(dirname(ABSPATH) . '/vendor/autoload.php')) {
	require_once dirname(ABSPATH) . '/vendor/autoload.php';
}

require_once NWH_PLUGIN_PATH . 'src/Vendor/Psr4ClassLoader.php';

$loader = new NetlifyWpHook\Vendor\Psr4ClassLoader();
$loader->addPrefix('NetlifyWpHook', NWH_PLUGIN_PATH);
$loader->addPrefix('NetlifyWpHook', NWH_PLUGIN_PATH . 'src/php/');
$loader->register();

// Initialize plugin
add_action('plugins_loaded', function () {
	new NetlifyWpHook\App();
}, 20);