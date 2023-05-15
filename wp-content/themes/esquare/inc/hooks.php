<?php
/**
 * Custom hooks.
 *
 * @package esquare
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'esquare_site_info' ) ) {
	/**
	 * Add site info hook to WP hook library.
	 */
	function esquare_site_info() {
		do_action( 'esquare_site_info' );
	}
}

if ( ! function_exists( 'esquare_add_site_info' ) ) {
	add_action( 'esquare_site_info', 'esquare_add_site_info' );

	/**
	 * Add site info content.
	 */
	function esquare_add_site_info() {
		$the_theme = wp_get_theme();
        $the_home_url = get_home_url();
        $the_site_title = get_bloginfo( 'name' );
		$year = date("Y"); 
        
		$site_info =   "Copyright  © $year  <a href='$the_home_url'> $the_site_title </strong></a> <span class='sep'> | </span> All rights Reserved. "; 
		echo apply_filters( 'esquare_site_info_content', $site_info ); // WPCS: XSS ok.
	}
}
