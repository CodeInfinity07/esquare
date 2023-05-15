<?php
/**
* Plugin Name: Trax Logistics
* Description: Trax Sonic Shipment Booking Plugin
* Version: 1.7
* Author: Trax Logistics
* Author URI: https://trax.pk
**/
require_once(ABSPATH . 'wp-config.php');

function register_shipment_arrival_order_status() {
    register_post_status( 'wc-trax-booked', array(
        'label'                     => 'Booked at Trax',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Booked at Trax <span class="count">(%s)</span>', 'Booked at Trax <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_shipment_arrival_order_status' );

function add_awaiting_shipment_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-trax-booked'] = 'Booked at Trax';
        }
    }
    return $new_order_statuses;
}

add_filter( 'wc_order_statuses', 'add_awaiting_shipment_to_order_statuses' );

 add_filter( 'manage_edit-shop_order_columns', 'trax_function' );

 function trax_function($columns){
          $new_columns = (is_array($columns)) ? $columns : array();
          unset( $new_columns['order_actions'] );

          //all of your columns will be added before the actions column
          $new_columns['trax'] = 'Trax Logistics';
          //stop editing

          $new_columns['order_actions'] = $columns['order_actions'];
          return $new_columns;
  }

  add_action( 'manage_shop_order_posts_custom_column', 'trax_value_function', 2 );
	function trax_value_function($column){
		global $post;
		$data = get_post_meta($post->ID);

      	if ($column == 'trax') {
			global $wpdb;

			$result = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."postmeta where  post_id =".$post->ID);

			$tracking_link = '';

			foreach($result as $row) {
				if($row->meta_key == 'tracking_number') {
				  $tracking_link = '<a target="_blank" href="https://sonic.pk/tracking?tracking_number=' . $row->meta_value . '">Track Shipment: ' . $row->meta_value . '</a>';
				}
			}

			if (!empty($tracking_link)) {
				echo $tracking_link;
			}
		}
	}



function submit_form()
{
	if (!empty($_POST['form_submitted_trax']))
	{
		$apiKey  = get_option('trax_sonic_api');

		if($apiKey == '') { die('Please specify valid Sonic API Key'); }

		ob_start();
		include('submit_booking.php');
		echo ob_get_clean();
		die;
	}
}

add_action( 'init', 'submit_form' );

//Trax plugin -- START

add_filter( 'bulk_actions-edit-shop_order', 'trax_my_bulk_actions' );

function trax_my_bulk_actions( $bulk_array ) {

	$bulk_array['trax_orders'] = 'Book at Trax';
	return $bulk_array;

}

add_filter( 'handle_bulk_actions-edit-shop_order', 'trax_bulk_action_handler', 10, 3 );

function trax_bulk_action_handler( $redirect, $doaction, $object_ids )
{
	if ($doaction == 'trax_orders') {
		$apiKey  = get_option('trax_sonic_api');

		if($apiKey == '') { die('Please specify valid Sonic API Key'); }

		
		$baseUrl = "https://sonic.pk/";
		
		$apiUrl = $baseUrl."api/cities";
		$headers = ['Authorization:' . $apiKey, 'Accepts:' . 'application/json'];
		$ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $apiUrl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        try
        {
            $result = curl_exec($ch);
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }

        $result = json_decode($result,true);

        $cityList = [];

        if($result['status'] == 0)
        {
            $cities = $result['cities'];

            foreach($cities as $city)
            {
                $cityList[$city['id']] = $city['name'];
            }
        }

        //close connection
        curl_close($ch);



		$shipping_mode_id  = get_option('trax_sonic_shipping_mode_id');
		if($shipping_mode_id == '') { die('Please specify Shipping Mode'); }

		$pickup_address_id  = get_option('trax_sonic_pickup_address_id');

        if($pickup_address_id == '') { die('Please specify Pickup Address ID'); }

		wp_head();
		require_once( ABSPATH . 'wp-admin/admin-header.php' );
?>
	<style>
@import url('https://fonts.googleapis.com/css?family=Roboto&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css');

	*
	{
	font-family: 'Roboto', sans-serif;
	font-size: 11px;
	}



	</style>
	<div class="form-box">
	  <?php //screen_icon(); ?>
	  <h1>Order Booking with Trax</h1>
	  <form method="post">
	  	<input type="hidden" name="form_submitted_trax" value="true" />
		  <?php  submit_button(); ?>

		  <div class="container-fluid">
			<div class="row ml-3">
				<div class="col-12 p-2">
				


				<table class="table">
				  <thead class="thead-light">
				    <tr>
				      <th scope="col">Order #</th>
				      <th scope="col">Consignee Name</th>
				      <th scope="col">Consignee Address</th>
				      <th scope="col">Consignee Phone</th>
				      <th scope="col">City</th>
				      <th scope="col">COD Amount</th>
				      <th scope="col">Item Description</th>
				      <th scope="col">Item Quantity</th>
				      <th scope="col">Special Instructions</th>
				    </tr>
				  </thead>
				  <tbody>
				    


<?php
		$count = 0;
		$skip = 0;

		foreach($object_ids as $orderId)
		{
			$getOrder = new WC_Order($orderId);
	    	$getOrder = $getOrder->get_data();
	    	
	    	if($getOrder['status'] == 'trax-booked')
	    	{
	    		$skip++;

	    		if(count($object_ids) == $skip)
	    		{
	    			wp_redirect(wp_get_referer());
	    			die;
	    		}


	    		continue;
	    	}

			$count++;
			$itemDesc = '';
			$itemQty = 0;

			//Get Order Info
			$order = new WC_Order($orderId);
			$orderData = $order->get_data();

            $key = 0;
            foreach($orderData['line_items'] as $item)
            {
                $item_data = $item->get_data();

                $itemQty += $item_data['quantity'];
                if($key != 0)
                {
                    $itemDesc .= ", ";
                }
                $itemDesc .= $item_data['quantity'] . " X ".$item_data['name'];
                $key++;
            }

			if (trim($orderData['shipping']['first_name']) != '') {
				$first_name = $orderData['shipping']['first_name'];
			}
			else {
				$first_name = $orderData['billing']['first_name'];
			}

			if (trim($orderData['shipping']['last_name']) != '') {
				$last_name = $orderData['shipping']['last_name'];
			}
			else {
				$last_name = $orderData['billing']['last_name'];
			}

			if (trim($orderData['shipping']['address_1']) != '') {
				$address_1 = $orderData['shipping']['address_1'];
			}
			else {
				$address_1 = $orderData['billing']['address_1'];
			}

			if (trim($orderData['shipping']['address_2']) != '') {
				$address_2 = $orderData['shipping']['address_2'];
			}
			else {
				$address_2 = $orderData['billing']['address_2'];
			}

			if (trim($orderData['shipping']['phone']) != '') {
				$phone = $orderData['shipping']['phone'];
			}
			else {
				$phone = $orderData['billing']['phone'];
			}

			if (isset($orderData['shipping']['email']) && trim($orderData['shipping']['email']) != '') {
				$email = $orderData['shipping']['email'];
			}
			else {
				$email = $orderData['billing']['email'];
			}

			if (trim($orderData['shipping']['city']) != '') {
				$city = $orderData['shipping']['city'];
			}
			else {
				$city = $orderData['billing']['city'];
			}
			$cityId = array_search(strtolower($city), array_map('strtolower', $cityList));
?>
		

					<tr>
				      <th scope="row"><?= $count ?>. Order # <?= $orderId?> <input type="hidden" id="order_id_<?= $count ?>" name="order[<?= $count ?>][order_id]" value="<?= $orderId ?>" required/></th>
				      <td><input type="text" id="consignee_name_<?= $count ?>" name="order[<?= $count ?>][consignee_name]" value="<?= $first_name.' '.$last_name?>" required/></td>
				      <td><textarea required id="consignee_address_<?= $count ?>" name="order[<?= $count ?>][consignee_address]"><?= $address_1."\r\n".$address_2?></textarea></td>
				      <td><input type="text" id="consignee_phone_<?= $count ?>" name="order[<?= $count ?>][consignee_phone]" value="<?= $phone?>" pattern="[0-9]{3,11}" placeholder='03xxxxxxxxx' required/></td>
				      <input type="hidden" id="consignee_email_<?= $count ?>" name="order[<?= $count ?>][consignee_email]" value="<?= $email?>" />
				      <td>
				      	
				      	<select id="consignee_city_<?= $count ?>" name="order[<?= $count ?>][consignee_city]" required>
				      		<option value="">Select Consignee City</option>
					  	<?php foreach($cityList as $city_id => $city) { ?>
					  		<?php if($city_id == $cityId){ ?>
							  <option value='<?= $city_id ?>' selected><?= $city ?></option>
							<?php }else{ ?>
								<option value='<?= $city_id ?>'><?= $city ?></option>
							<?php } ?>
						<?php } ?>
						</select>

				      </td>
				      <td>
				      	<input type="text" id="amount_<?= $count ?>" name="order[<?= $count ?>][amount]" value="<?= $orderData['total']?>" required/>
				      </td>
				      
				      <td>
				      	<textarea id="item_description_<?= $count ?>" name="order[<?= $count ?>][item_description]" required><?= $itemDesc ?></textarea>
				      </td>
				      <td>
				      	<input style='width:60px' type="number" id="item_quantity_<?= $count ?>" name="order[<?= $count ?>][item_quantity]" value="<?= $itemQty ?>" required/>
				      </td>	
				      <td>
				      	<textarea id="spec_instructions_<?= $count ?>" name="order[<?= $count ?>][spec_instructions]"><?= $orderData['customer_note']?></textarea>
				      </td>
				    </tr>
				    
				  

		
		
<?php
		}
?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
		<div class="row justify-content-center">
		  <?php  submit_button(); ?>
		</div>
	  </form>
	</div>
<?php wp_footer(); ?>

	<?php
		exit;
	}
	else {
		return $redirect;
	}
}

function trax_register_settings() {
   add_option( 'trax_sonic_api', '');
   add_option( 'trax_sonic_shipping_mode_id', '');
   add_option( 'trax_sonic_pickup_address_id', '');
   register_setting( 'trax_options_group', 'trax_sonic_api', 'trax_callback' );
   register_setting( 'trax_options_group', 'trax_sonic_shipping_mode_id', 'trax_callback' );
   register_setting( 'trax_options_group', 'trax_sonic_pickup_address_id', 'trax_callback' );
}
add_action( 'admin_init', 'trax_register_settings' );

function trax_register_options_page() {
  add_options_page('Configure Trax Sonic API', 'Configure Trax Sonic API', 'manage_options', 'trax', 'trax_options_page');
}
add_action('admin_menu', 'trax_register_options_page');

function trax_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h2>API Credentials | Sonic | Trax</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'trax_options_group' ); ?>
  <h3>Shipment Booking</h3>
  <table>
  <tr valign="top">
  <th scope="row"><label for="trax_sonic_api">API Key</label></th>
  <td><input type="text" id="trax_sonic_api" name="trax_sonic_api" value="<?php echo get_option('trax_sonic_api'); ?>" /></td>
  </tr>
  <tr >
  <th scope="row"><label for="trax_sonic_api">Shipping Mode</label></th>
  	<td>
  		<?php $shipping_mode =  (int)get_option('trax_sonic_shipping_mode_id');?>

  		<select name="trax_sonic_shipping_mode_id">
  			<option value="1" <?php if($shipping_mode == 1) echo 'selected'; ?>>Rush</option>
  			<option value="2" <?php if($shipping_mode == 2) echo 'selected'; ?>>Saver Plus</option>
  			<option value="3" <?php if($shipping_mode == 3) echo 'selected'; ?>>Swift</option>
  		</select>
  	</td>
  </tr>

  <tr >
  	<?php 
  	$apiKey  = get_option('trax_sonic_api');
  	$addrList = [];
  		$baseUrl = "https://sonic.pk/";
		$apiUrl = $baseUrl . "api/pickup_addresses";
		$headers = ['Authorization:' . $apiKey, 'Accepts:' . 'application/json'];

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $apiUrl);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		try
		{
			$result = curl_exec($ch);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}

		$result = json_decode($result,true);

		if($result['status'] == 0)
		{
			$addresses = $result['pickup_addresses'];

			foreach($addresses as $address)
			{
				if ($address['status']) {
					$addrList[$address['id']] = $address['address']. ', '. $address['city']['name'];
				}
			}
		}

		$pickup_address_id =  (int)get_option('trax_sonic_pickup_address_id');

  	?>
  <th scope="row"><label for="trax_sonic_api">Pickup Address</label></th>
  	<td>
  		<select name="trax_sonic_pickup_address_id">
  			<?php foreach($addrList as $id => $addr) { ?>
  					<?php if($pickup_address_id == $id){ ?>
					  <option value='<?= $id ?>' selected><?= $addr ?></option>
					  <?php }else{ ?>
						<option value='<?= $id ?>'><?= $addr ?></option>
					<?php } ?>
				<?php } ?>
  		</select>
  	</td>
  </tr>

  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}
