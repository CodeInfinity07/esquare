<?php
/*
Plugin Name: CallCourier WooCommerce Fulfillment
Plugin URI: mailto:mudusser@gmail.com
Description: Woocommerce plugin to fulfill an order with CallCourier.
Author: Mudasser Aslam
Author URI: mailto:mudusser@gmail.com
Version: 1.0.0
WC requires at least: 3.0.0
WC tested up to: 4.9.7
*/

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	if ( ! class_exists( 'WC_Cc' ) ) {
		
		$cc_api_obj = include( plugin_dir_path( __FILE__ ) . 'callcourier.php');
		include( plugin_dir_path( __FILE__ ) . 'options.php');
		
		/**
		 * Localisation
		 **/
		load_plugin_textdomain( 'wc_cc', false, dirname( plugin_basename( __FILE__ ) ) . '/' );

		class WC_Cc {
			public function __construct() {
				// called only after woocommerce has finished loading
				add_action( 'woocommerce_init', array( &$this, 'woocommerce_loaded' ) );
				
				// called after all plugins have loaded
				add_action( 'plugins_loaded', array( &$this, 'plugins_loaded' ) );
				
				// called just before the woocommerce template functions are included
				add_action( 'init', array( &$this, 'include_template_functions' ), 20 );

				// Register the script like this for a plugin:

				add_action( 'admin_footer', array( &$this, 'cc_wc_scripts_basic'));

				//disable plugin booking menu
				add_action( 'admin_head', function() {
					remove_submenu_page( 'options-general.php', 'callcourier-plugin-booking' );
				} );

				/*woocommerce_order_status_pending
				woocommerce_order_status_failed
				woocommerce_order_status_on-hold
				woocommerce_order_status_processing
				woocommerce_order_status_completed
				woocommerce_order_status_refunded
				woocommerce_order_status_cancelled*/
				
				//create shipment when order status chnaged to complete
				//add_action( 'woocommerce_order_status_completed', array( &$this, 'cc_woocommerce_order_status_completed'), 10, 1 );
				
				add_filter( 'manage_edit-shop_order_columns', array( &$this, 'wc_cc_cnid_column') );
				add_action( 'manage_shop_order_posts_custom_column' , array( &$this, 'cc_cnid_orders_list_column_content'), 10, 2 );

				// indicates we are running the admin
				if ( is_admin() ) {
					// ...
				}
				
				// indicates we are being served over ssl
				if ( is_ssl() ) {
					// ...
				}
    
				// take care of anything else that needs to be done immediately upon plugin instantiation, here in the constructor
			}

			public function cc_wc_scripts_basic()
			{
				// Register the script like this for a plugin:
				$url = plugins_url( '/js/custom-script.js', __FILE__ );
				echo '"<script type="text/javascript" src="'. $url . '"></script>"';
				//echo wp_register_script( 'custom-script', plugins_url( '/js/custom-script.js', __FILE__ ) );
			}
			function cc_woocommerce_order_status_completed( $order_id ) {
				error_log( "Order complete for order $order_id", 0 );
				$cc_api = New CallCourierApi();
				$cc_api->createshipment($order_id);
			}

			function wc_cc_cnid_column( $columns )
			{
				$columns['cc_cnid'] = 'Fulfilled';
				return $columns;
			}
			function cc_cnid_orders_list_column_content( $column, $post_id ) {
				if ( $column == 'cc_cnid' )
				{
					// The billing phone for example (to be repaced by your custom field meta_key)
					$custom_field_value = get_post_meta( $post_id, 'cc_cnid', true );
					if( ! empty( $custom_field_value ) )
						echo '<a href="http://cod.callcourier.com.pk/Booking/AfterSavePublic/'.$custom_field_value.'" target="_blank">'.$custom_field_value.'</a>';
						echo ' | ';
						echo '<a href="https://callcourier.com.pk/tracking/?tc='.$custom_field_value.'" target="_blank">View Tracking</a>';
				}
			}

			/**
			 * Take care of anything that needs woocommerce to be loaded.  
			 * For instance, if you need access to the $woocommerce global
			 */
			public function woocommerce_loaded() {
				// ...
			}
			
			/**
			 * Take care of anything that needs all plugins to be loaded
			 */
			public function plugins_loaded() {
				// ...
			}
			
			/**
			 * Override any of the template functions from woocommerce/woocommerce-template.php 
			 * with our own template functions file
			 */
			public function include_template_functions() {
				include( 'woocommerce-template.php' );
			}
		}

		// finally instantiate our plugin class and add it to the set of globals
		$GLOBALS['wc_cc'] = new WC_Cc();

/**
 * Add custom tracking code to the thank-you page
 */

//add_action( 'woocommerce_order_is_paid', 'my_custom_tracking' );
function my_custom_tracking( $order_id, $a ) {

	print_r($a);
	die();

	// Lets grab the order
	$order = wc_get_order( $order_id );

	/**
	 * Put your tracking code here
	 * You can get the order total etc e.g. $order->get_total();
	 */
	 
	// This is the order total
	$order->get_total();
 
	// This is how to grab line items from the order 
	$line_items = $order->get_items();

	// This loops over line items
	foreach ( $line_items as $item ) {
  		// This will be a product
  		$product = $order->get_product_from_item( $item );
  
  		// This is the products SKU
		$sku = $product->get_sku();
		
		// This is the qty purchased
		$qty = $item['qty'];
		
		// Line item total cost including taxes and rounded
		$total = $order->get_line_total( $item, true, true );
		
		// Line item subtotal (before discounts)
		$subtotal = $order->get_line_subtotal( $item, true, true );
	}
}		
				
	function cc_cnid_add_order_meta( $order_id ) {		
		add_post_meta( $order_id, 'cc_cnid', sanitize_text_field( rand() ), true );
	}

	/**
	 * Display field value on the order edit page
	 */
	add_action( 'woocommerce_admin_order_data_after_billing_address', 'show_cc_cnid_on_order_edit', 10, 1 );

	function print_array($array){
		echo '<pre>'.print_r($array,1).'</pre>';
	}

	function show_cc_cnid_on_order_edit($order){
		//echo $order->id;
		$cc_cnid = get_post_meta( $order->id, 'cc_cnid', true );

		//echo '<p><strong>'.__('CallCourier Tracking ID').':</strong> <br/>' . $cc_cnid . '</p>';

		if($cc_cnid){
			echo '<p><strong>'.__('CallCourier Tracking ID').':</strong> <br/> <a href="http://cod.callcourier.com.pk/Booking/AfterSavePublic/'.$cc_cnid.'" id="fullfil-cc-order-link" target="_blank" data-orderid="'.$cc_cnid.'">'.$cc_cnid.'</a></p>';
		}else{
			echo '<p><strong>'.__('CallCourier Tracking ID').':</strong> <br/> Not Fullfilled</a></p>';
		}

		//$createShipping = ajaxCreateShipment($order->id);
	}

	function make_cc_cnid_call($order){
		print_r($order);
	}

	// Adding to admin order list bulk dropdown a custom action 'custom_downloads'
	add_filter( 'bulk_actions-edit-shop_order', 'fullfill_to_cc_bulk_actions_edit_product', 20, 1 );
	function fullfill_to_cc_bulk_actions_edit_product( $actions ) {
		$actions['fullfil_orders'] = __( 'FullFill to CallCourier', 'woocommerce' );
		return $actions;
	}

	// Make the action from selected orders
	add_filter( 'handle_bulk_actions-edit-shop_order', 'fullfill_to_cc_handle_bulk_action_edit_shop_order', 10, 3 );

	function fullfill_to_cc_handle_bulk_action_edit_shop_order( $redirect_to, $action, $post_ids ) {

		//update cnic only if bulk action is 'fullfil_orders'
		if($action == 'fullfil_orders'){

			/*
			if (!session_id()) {
				session_start();
			}
			unset($_SESSION['order_ids']);
			$_SESSION['order_ids'] = $post_ids;
			unset($_SESSION['cc_api']);
			$_SESSION['cc_api'] = New CallCourierApi();
			*/

			$url = 'options-general.php?page=callcourier-plugin-booking&cc_ids='.implode(',',$post_ids);
			header("Location: ".$url);
			die();
			
			foreach ( $post_ids as $post_id ) {
				$order = wc_get_order( $post_id );

				$cc_api = New CallCourierApi();
				$cnic = $cc_api->createshipment($post_id);

				//if order fullfilled successfully
				if($cnic){
					$processed_ids[] = $cnic;
				}
			}
		
			return $redirect_to = add_query_arg( array(
				'fullfil_orders' => '1',
				'processed_count' => count( $processed_ids ),
				'processed_ids' => implode( ',', $processed_ids ),
			), $redirect_to );
		}
	}

// The results notice from bulk action on orders
add_action( 'admin_notices', 'fulfill_to_cc_bulk_action_admin_notice' );
function fulfill_to_cc_bulk_action_admin_notice() {
    if ( empty( $_REQUEST['fullfil_orders'] ) ) return; // Exit

    $count = intval( $_REQUEST['processed_count'] );

    printf( '<div id="message" class="updated fade"><p>' .
        _n( 'Fullfilled %s Order with CallCourier.',
        'Fullfilled %s Orders for downloads.',
        $count,
        'fullfil_orders'
    ) . '</p></div>', $count );
}	

	}
}