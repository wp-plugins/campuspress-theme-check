<?php
/*
Plugin Name: CampusPress Theme Check
Plugin URI: https://github.com/igmoweb/theme-check
Description: A simple and easy way to test your theme before upload it to CampusPress
Author: igmoweb,campuspress
Author URI: http://campuspress.com
Version: 1.0
Requires at least: 3.8
Tested up to: 4.1
Text Domain: ctheme-check

This plugin is an extension Theme Check created by Otto42 and pross.

Please, do not use this plugin if you don't have a site in CampusPress network.
You can found the original Theme Check here: https://wordpress.org/plugins/theme-check/
*/

class CampusPress_ThemeCheck {

	function __construct() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( ! is_plugin_active( 'theme-check/theme-check.php' ) ) {
			add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
		}
		else {
			add_action( 'admin_init', array( $this, 'load_plugin_textdomain' ) );	

			// Load our check file
			add_action( 'themecheck_checks_loaded', array( $this, 'load_campus_check' ) );
		}
		
	}

	public function display_admin_notice() {
		if ( is_multisite() && ! current_user_can( 'manage_network' ) )
			return;
		
		if ( ! is_multisite() && ! current_user_can( 'manage_options' ) )
			return;

		$install_url = add_query_arg( array( 'tab' => 'search', 's' => 'theme+check' ), is_multisite() ? network_admin_url( 'plugin-install.php' ) : admin_url( 'plugin-install.php' ) );
		?>
			<div class="updated">
				<p><?php printf( __( '<a href="%s" title="%s">Theme Check</a> needs to be activated before using CampusPress Theme Check.', 'ctheme-check' ), esc_url( $install_url ), __( 'Install Theme Check', 'ctheme-check' ) ); ?></p>
			</div>
		<?php 
	}

	function load_plugin_textdomain() {
		load_plugin_textdomain( 'ctheme-check', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/lang/' );
	}

	function load_campus_check() {
		global $themechecks;
		include 'campus-check.php';
	}

	
}
new CampusPress_ThemeCheck;
