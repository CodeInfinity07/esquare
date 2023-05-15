<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestController;
use \WP_REST_Server;

class FeedLists extends RestController {

	private static $status = null;
	private static $feed_lists = [];
	/**
	 * The single instance of the class
	 *
	 * @var FeedLists
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = 'feed_lists';
	}

	/**
	 * Main FeedLists Instance.
	 *
	 * Ensures only one instance of FeedLists is loaded or can be loaded.
	 *
	 * @return FeedLists Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Register routes.
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				/**
				 * @endpoint: wp-json/ctxfeed/v1/feed_lists
				 * @description  Will get all feed lists
				 *
				 * @endpoint wp-json/ctxfeed/v1/feed_lists/?status=inactive
				 *  @method GET
				 * @description  Only inactive feed lists will be returned.
				 *
				 *
				 * @endpoint wp-json/ctxfeed/v1/feed_lists/?status=active
				 *  @method GET
				 * @description  Only active feed lists will be returned.
				 *
				 * @endpoint wp-json/ctxfeed/v1/feed_lists/?status=active&page=1&per_page=2
				 *  @method GET
				 * @descripton Get paginated value with previous page and next page link
				 *
				 *
				 * @endpoint wp-json/ctxfeed/v1/feed_lists/?name=wf_feed_google_shopping
				 * @method GET
				 * @descripton Get single feed
				 * @param $name String
				 *
				 * @param $status String
				 * @param $page Number
				 * @param $per_page Number
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_items' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'status'   => [
							'description'       => __( 'Is active or inactive', 'woo-feed' ),
							'type'              => 'string',
							'required'          => false,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'name' => [
							'description'       => __( 'feed name', 'woo-feed' ),
							'type'              => 'string',
							'required'          => false,
							'sanitize_callback' => 'sanitize_text_field',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'page'     => [
							'description'       => __( 'Page number', 'woo-feed' ),
							'type'              => 'number',
							'required'          => false,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						],
						'per_page' => [
							'description'       => __( 'Per page', 'woo-feed' ),
							'type'              => 'number',
							'required'          => false,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						],
					],
				]
			]
		);
	}

	/**
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response|null
	 */
	public function get_item( $request ) {
		$args      = $request->get_params();
		$feed_name = $args['name'];
		global $wpdb;
		$feed_lists        = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name LIKE %s ORDER BY option_id DESC;", $feed_name ), 'ARRAY_A' );
		$this::$feed_lists = $feed_lists;
		if( count( $this::$feed_lists ) ) {
			$item = $this->prepare_item_for_response( $this::$feed_lists[0], $request );
			return $this->success( $item );
		}

		return $this->error( sprintf( __( 'Not found with: %s ', 'woo-feed' ), $feed_name ) );
	}

	/**
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_items( $request ) {
		$args          = $request->get_params();
		$this::$status = isset( $args['status'] ) ? $args['status'] : $this::$status;
		if ( isset( $args['name'] ) ) {
			return $this->get_item( $request );
		}
		global $wpdb;
		$feed_lists        = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name LIKE %s ORDER BY option_id DESC;", 'wf_feed_%' ), 'ARRAY_A' );
		$this::$feed_lists = $feed_lists;
		if ( $this::$status ) {
			// True if status is active/inactive
			if ( 'active' === $this::$status || 'inactive' === $this::$status ) {
				$data = $this->get__feed_lists( $request );
			} else {
				return $this->error( __( 'Status should be active/inactive !', 'woo-feed' ) );
			}
		} else {
			$data = $this->get__feed_lists( $request );
		}

		$response = rest_ensure_response( $this->response );
		$response = $this->maybe_add_pagination( $args, $data, $response );

		return $response;
	}

	private function get__feed_lists( $request ) {
		$lists = [];
		foreach ( $this::$feed_lists as $feed_list ) {
			$item = $this->prepare_item_for_response( $feed_list, $request );
			if ( $this::$status ) {
				if ( is_object( $item['option_value'] ) ) {
					$lists[] = $item;
					continue;
				}
				if ( 'active' === $this::$status && 1 === $item['option_value']['status'] ) {
					$lists[] = $item;
				}
				if ( 'inactive' === $this::$status && 0 === $item['option_value']['status'] ) {
					$lists[] = $item;
				}
			} else {
				$lists[] = $item;
			}
		}

		return $lists;
	}

	/**
	 * @param $item
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {
		$item['option_value'] = maybe_unserialize( get_option( $item['option_name'] ) );

		return $item;
	}

}
