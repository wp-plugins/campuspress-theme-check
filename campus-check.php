<?php
class CampusPress_Checks implements themecheck {
	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {
		$ret = true;

		$checks = array(
			'/wp_feed_cache_transient_lifetime/' => __( 'Don\'t ever change wp_feed_cache_transient_lifetime (hook to the filter).', 'ctheme-check' ),
			'/SHOW TABLES\s/i' => __( 'Do not list every table in the database (you don\'t want to millions of tables in the list.', 'ctheme-check' ),
			'/dbDelta\s?\(/' => __( 'Do not create/modify tables for themes.', 'ctheme-check' ),
			'/remove_role\s?\(/' => __( 'Do not remove roles.', 'ctheme-check' ),
			'/flush_rules|flush_rewrite_rules/' => __( 'Do not flush rewrite rules.', 'ctheme-check' ),
			'/wp_cache_flush/' => __( 'Do not flush cache', 'ctheme-check' ),
			'/chdir|chroot|closedir|dir\s?\(|glob\s?\(|getcwd|opendir|readdir|rewinddir|scandir/' => __( 'Directory disk operations are not allowed', 'ctheme-check' ),
			'/googlesyndication\.com/' => __( 'Loading content from googlesyndication.com is not allowed', 'ctheme-check' ),
			'/ALLOW_EXTERNAL/' => __( 'Changing ALLOW_EXTERNAL constant is not allowed', 'ctheme-check' ),
			'/CURLOPT_CONNECTTIMEOUT/' => __( 'Do not set CURLOPT_CONNECTTIMEOUT constant', 'ctheme-check' ),
			'/WP_DEBUG|error_reporting|display_errors/' => __( 'Changing WP_DEBUG, error_reporting or display_errors is not allowed', 'ctheme-check' ),
		);

		$grep = '';

		foreach ( $php_files as $php_key => $phpfile ) {
			foreach ( $checks as $key => $check ) {
			checkcount();
				if ( preg_match( $key, $phpfile, $matches ) ) {
					$filename = tc_filename( $php_key );
					$error = ltrim( trim( $matches[0], '(' ) );
					$grep = tc_grep( $error, $php_key );
					$this->error[] = sprintf('<span class="tc-lead tc-warning">'. __( 'WARNING', 'ctheme-check' ) . '</span>: Found <strong>%1$s</strong> in the file <strong>%2$s</strong>. %3$s. %4$s', $error, $filename, $check, $grep );
					$ret = false;
				}
			}
		}



		$checks = array(
			'/wp_remote_.*\s?\(/' => __( 'Looks like you are trying to retrieve a URL using HTTP requests. Please, remember that you cannot modify timeouts on these requests', 'ctheme-check' ),
			'/file_get_contents/' => __( 'Make sure that you don\t call <strong>file_get_contents</strong> to get a remote file, use <strong>campus_remote_get_contents</strong> for that purpose', 'ctheme-check' ),
			'/DESC\s/' => __( 'Use DESCRIBE to describe tables not DESC', 'ctheme-check' ),
			'/WPCom_Theme_Updater/' => __( '<strong>WPCom_Theme_Updater</strong> class is usally used for updates. Please, deactivate the class.', 'ctheme-check' ),
			'/\$wpdb/' => __( 'Looks like you\re making queries. Please, check that they are not expensive or replace them for native WordPress functions', 'ctheme-check' ),
		);

		foreach ( $php_files as $php_key => $phpfile ) {
			foreach ( $checks as $key => $check ) {
				checkcount();
				if ( preg_match( $key, $phpfile, $matches ) ) {
					$filename = tc_filename( $php_key );
					$error = ltrim( rtrim( $matches[0], '(' ) );
					$grep = tc_grep( $error, $php_key );
					$this->error[] = sprintf('<span class="tc-lead tc-warning">'.__('ALERT','ctheme-check').'</span>: '.__('<strong>%1$s</strong> was found in the file <strong>%2$s</strong>. %3$s', 'ctheme-check'), $error, $filename, $check );
				}
			}
		}

		
		return $ret;
	}
	function getError() { return $this->error; }
}
$themechecks[] = new CampusPress_Checks;
