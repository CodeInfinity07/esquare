<?php
/**
 * esquare enqueue scripts
 *
 * @package esquare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'esquare_scripts' ) ) {
	/**
	 * Load theme's JavaScript and CSS sources.
	 */
	function esquare_scripts() {
		// Get the theme data.
		$the_theme     = wp_get_theme();
		$theme_version = $the_theme->get( 'Version' );

		$css_version = $theme_version . '.' . filemtime( get_template_directory() . '/app/css/app.min.css' );
		wp_enqueue_style( 'esquare-styles', get_stylesheet_directory_uri() . '/app/css/app.min.css', array(), $css_version );


		
		
		// $css_version = $theme_version . '.' . filemtime( get_template_directory() . '/app/css/woocommerce.css' );
		// wp_enqueue_style( 'wooTheme', get_stylesheet_directory_uri() . '/app/css/woocommerce.css', array(), $css_version );

		$css_version = $theme_version . '.' . filemtime( get_template_directory() . '/app/css/responsive.css' );
		wp_enqueue_style( 'responsive', get_stylesheet_directory_uri() . '/app/css/responsive.css', array(), $css_version );



		wp_enqueue_script( 'jquery' );

		// $js_version = $theme_version . '.' . filemtime( get_template_directory() . '/app/js/jquery.min.js' );
		// wp_enqueue_script( 'jquery-main', get_template_directory_uri() . '/app/js/jquery.min.js', array(), $js_version, true );



		$js_version = $theme_version . '.' . filemtime( get_template_directory() . '/app/js/app.js' );
		wp_enqueue_script( 'esquare-scripts', get_template_directory_uri() . '/app/js/app.js', array(), $js_version, true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
} // endif function_exists( 'esquare_scripts' ).

add_action( 'wp_enqueue_scripts', 'esquare_scripts', 101 ) ;
