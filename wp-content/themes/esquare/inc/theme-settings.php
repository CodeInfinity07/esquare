<?php
/**
 * Check and setup theme's default settings
 *
 * @package esquare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'esquare_setup_theme_default_settings' ) ) {
	function esquare_setup_theme_default_settings() {

		// check if settings are set, if not set defaults.
		// Caution: DO NOT check existence using === always check with == .
		// Latest blog posts style.
		$esquare_posts_index_style = get_theme_mod( 'esquare_posts_index_style' );
		if ( '' == $esquare_posts_index_style ) {
			set_theme_mod( 'esquare_posts_index_style', 'default' );
		}

		// Sidebar position.
		$esquare_sidebar_position = get_theme_mod( 'esquare_sidebar_position' );
		if ( '' == $esquare_sidebar_position ) {
			set_theme_mod( 'esquare_sidebar_position', 'right' );
		}

		// Container width.
		$esquare_container_type = get_theme_mod( 'esquare_container_type' );
		if ( '' == $esquare_container_type ) {
			set_theme_mod( 'esquare_container_type', 'container' );
		}
	}
}
