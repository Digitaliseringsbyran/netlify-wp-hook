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

require 'vendor/autoload.php';

use GuzzleHttp\Client;

class NetlifyWpHook {
	protected $BUILD_HOOK_URL;

	public function __construct()
	{
		$this->BUILD_HOOK_URL = get_option('build_hook_url');

		add_action('save_post',array($this, 'send_webhook'), 10, 3);
		add_action('admin_menu', array($this, 'create_options_page') );
	}

	/**
 	* Send a request to Netlify to build the site on update or save
 	* @return void
 	*/
 	function send_webhook( $post_id, $post, $update ) {
 			// Return if build hook isnt set
 			if (!is_string($this->BUILD_HOOK_URL) && strlen($this->BUILD_HOOK_URL) === 0) { return; }

		    // Don't fire on blank posts
			if (isset($post->post_status) && $post->post_status == 'published') { return; }

			// Only run in production
			if (defined('WP_ENV') && WP_ENV !== 'production') { return; }

			$client = new \GuzzleHttp\Client();
			$response = $client->post($this->BUILD_HOOK_URL);
		}

	/**
	 * Creates an option page and adds it to the settings menu.
	 * @return void
	 */
	public function create_options_page() 
	{
		$title = 'Netlify';
		$slug = 'netlify';
		$sectionId = 'Netlify';
		
		// Add options page
		add_options_page(
			$title, 
			$title, 
			'manage_options', 
			$slug, 
			array($this, 'render_options_page')
		);

		// Add settings section to the created options page
		add_settings_section(
			$sectionId,   
			'Netlify',
			function() {
				echo "<p>Add a build hook below or <a href='https://www.netlify.com/docs/webhooks/#incoming-webhooks'>read about configuring one</a>.</p>";
			},
			$slug
	    );

		// Add the settings field
		add_settings_field( 
			'build_hook_url',
			'Build Hook URL',
			array($this, 'addSettingsFieldCallback'),
			$slug,
			$sectionId,
			array( 'build_hook_url' )
		);

		// Register the created fields
		register_setting( $slug, 'build_hook_url' );
	}

	/**
	 * Callback to add the settings field
	 * @return void
	 */
	public function addSettingsFieldCallback( $args )
	{
		echo '<input type="text" placeholder="https://api.netlif..." id="' . $args[0] . '" name="' . $args[0] . '" value="' . get_option($args[0]) . '"/>';
	}

	/**
	 * Render the options page
	 * @return void
	 */
	public function render_options_page()
	{
		return include 'views/form.php';
	}
}

new NetlifyWpHook();