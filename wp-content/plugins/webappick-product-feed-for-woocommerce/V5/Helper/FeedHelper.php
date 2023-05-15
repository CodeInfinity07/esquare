<?php

namespace CTXFeed\V5\Helper;
class FeedHelper {

	/** Sanitize Feed Configs
	 * @param $data
	 *
	 * @return mixed
	 */
	public static function sanitize_form_fields( $data ) {
		foreach ( $data as $k => $v ) {
			if ( true === apply_filters( 'woo_feed_sanitize_form_fields', true, $k, $v, $data ) ) {
				if ( is_array( $v ) ) {
					$v = woo_feed_sanitize_form_fields( $v );
				} else {
					// $v = sanitize_text_field( $v ); #TODO should not trim Prefix and Suffix field
				}
			}
			$data[ $k ] = apply_filters( 'woo_feed_sanitize_form_field', $v, $k );
		}

		return $data;
	}

	/**
	 * Remove Feed Option Name Prefix and return the slug
	 *
	 * @param string $feed
	 *
	 * @return string
	 */
	public static function get_feed_option_name( $feed ) {
		return str_replace( [ 'wf_feed_', 'wf_config' ], '', $feed );
	}

}
