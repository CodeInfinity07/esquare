<?php
/**
 * Comment layout.
 *
 * @package esquare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Comments form.
add_filter( 'comment_form_default_fields', 'esquare_bootstrap_comment_form_fields' );

/**
 * Creates the comments form.
 *
 * @param string $fields Form fields.
 *
 * @return array
 */

if ( ! function_exists( 'esquare_bootstrap_comment_form_fields' ) ) {

	function esquare_bootstrap_comment_form_fields( $fields ) {
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = ( $req ? " aria-required='true'" : '' );
		$html5     = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
		$consent  = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
		$fields    = array(
			'author'  => '<div class="form-group comment-form-author"><label for="author">' . __( 'Name',
					'esquare' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
			            '<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . '></div>',
			'email'   => '<div class="form-group comment-form-email"><label for="email">' . __( 'Email',
					'esquare' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
			            '<input class="form-control" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . '></div>',
			'url'     => '<div class="form-group comment-form-url"><label for="url">' . __( 'Website',
					'esquare' ) . '</label> ' .
			            '<input class="form-control" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30"></div>',
			'cookies' => '<div class="form-group form-check comment-form-cookies-consent"><input class="form-check-input" id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' /> ' .
			         '<label class="form-check-label" for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment', 'esquare' ) . '</label></div>',
		);

		return $fields;
	}
} // endif function_exists( 'esquare_bootstrap_comment_form_fields' )

add_filter( 'comment_form_defaults', 'esquare_bootstrap_comment_form' );

/**
 * Builds the form.
 *
 * @param string $args Arguments for form's fields.
 *
 * @return mixed
 */

if ( ! function_exists( 'esquare_bootstrap_comment_form' ) ) {

	function esquare_bootstrap_comment_form( $args ) {
		$args['comment_field'] = '<div class="form-group comment-form-comment">
	    <label for="comment">' . _x( 'Comment', 'noun', 'esquare' ) . ( ' <span class="required">*</span>' ) . '</label>
	    <textarea class="form-control" id="comment" name="comment" aria-required="true" cols="45" rows="8"></textarea>
	    </div>';
		$args['class_submit']  = 'btn'; // since WP 4.1.
		return $args;
	}
} // endif function_exists( 'esquare_bootstrap_comment_form' )
