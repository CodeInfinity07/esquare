<?php
/**
 * esquare Theme Customizer
 *
 * @package esquare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if ( ! function_exists( 'esquare_customize_register' ) ) {
	/**
	 * Register basic customizer support.
	 *
	 * @param object $wp_customize Customizer reference.
	 */
	function esquare_customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	}
}
add_action( 'customize_register', 'esquare_customize_register' );

if ( ! function_exists( 'esquare_theme_customize_register' ) ) {
	/**
	 * Register individual settings through customizer's API.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer reference.
	 */
	function esquare_theme_customize_register( $wp_customize ) {

		// Theme layout settings.
		$wp_customize->add_section(
			'esquare_theme_layout_options',
			array(
				'title'       => __( 'Theme Layout Settings', 'esquare' ),
				'capability'  => 'edit_theme_options',
				'description' => __( 'Container width and sidebar defaults', 'esquare' ),
				'priority'    => 160,
			)
		);

		/**
		 * Select sanitization function
		 *
		 * @param string               $input   Slug to sanitize.
		 * @param WP_Customize_Setting $setting Setting instance.
		 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
		 */
		function esquare_theme_slug_sanitize_select( $input, $setting ) {

			// Ensure input is a slug (lowercase alphanumeric characters, dashes and underscores are allowed only).
			$input = sanitize_key( $input );

			// Get the list of possible select options.
			$choices = $setting->manager->get_control( $setting->id )->choices;

				// If the input is a valid key, return it; otherwise, return the default.
				return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

		}

		$wp_customize->add_setting(
			'esquare_container_type',
			array(
				'default'           => 'container',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esquare_theme_slug_sanitize_select',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'esquare_container_type',
				array(
					'label'       => __( 'Container Width', 'esquare' ),
					'description' => __( 'Choose between Bootstrap\'s container and container-fluid', 'esquare' ),
					'section'     => 'esquare_theme_layout_options',
					'settings'    => 'esquare_container_type',
					'type'        => 'select',
					'choices'     => array(
						'container'       => __( 'Fixed width container', 'esquare' ),
						'container-fluid' => __( 'Full width container', 'esquare' ),
					),
					'priority'    => '10',
				)
			)
		);

		$wp_customize->add_setting(
			'esquare_sidebar_position',
			array(
				'default'           => 'right',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'esquare_sidebar_position',
				array(
					'label'             => __( 'Sidebar Positioning', 'esquare' ),
					'description'       => __(
						'Set sidebar\'s default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.',
						'esquare'
					),
					'section'           => 'esquare_theme_layout_options',
					'settings'          => 'esquare_sidebar_position',
					'type'              => 'select',
					'sanitize_callback' => 'esquare_theme_slug_sanitize_select',
					'choices'           => array(
						'right' => __( 'Right sidebar', 'esquare' ),
						'left'  => __( 'Left sidebar', 'esquare' ),
						'both'  => __( 'Left & Right sidebars', 'esquare' ),
						'none'  => __( 'No sidebar', 'esquare' ),
					),
					'priority'          => '20',
				)
			)
		);
	}
} // endif function_exists( 'esquare_theme_customize_register' ).
add_action( 'customize_register', 'esquare_theme_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
if ( ! function_exists( 'esquare_customize_preview_js' ) ) {
	/**
	 * Setup JS integration for live previewing.
	 */
	function esquare_customize_preview_js() {
		wp_enqueue_script(
			'esquare_customizer',
			get_template_directory_uri() . '/js/customizer.js',
			array( 'customize-preview' ),
			'20130508',
			true
		);
	}
}
add_action( 'customize_preview_init', 'esquare_customize_preview_js' );
