<?php
/**
 * 
 * @package  LeopardsCourier
 * 
 */

namespace Inc\Api;
defined('ABSPATH') or die('No direct script access allowed!');

class LeopardsApi {

    /**
     * @description Variable to store settings tab slug
     * 
     * @var string 
     */
    public static $id = 'leopards-courier-settings';

    /**
     * @description Variable to store settings tab name
     * 
     * @var string 
     */
    public static $tab_name = 'Leopards Courier Settings';

    /**
     * @description Variable to store this plug-in status
     * 
     * @var string 
     */
    public $api_enabled = '';

    /**
     * @description Variable to store Leopards Courier API URL
     * 
     * @var string 
     */
    public $api_url = '';

    /**
     * @description Variable to store Leopards Courier API Password
     * 
     * @var string 
     */
    public $api_password = '';

    /**
     * @description Variable to store Leopards Courier API Key
     * 
     * @var string 
     */
    private $api_key = '';

    /**
     * @description Variable to store store Origin City
     * 
     * @var string 
     */
    private $user_city = '';

    /**
     * @description Variable to store store Email
     * 
     * @var string 
     */
    private $user_email = '';

    /**
     * @description Variable to store Sender Name. It will be printed on AWB labels.
     * 
     * @var string 
     */
    private $api_sender_name = '';

    /**
     * @description Variable to store Sender Address. It will be printed on AWB labels.
     * 
     * @var string 
     */
    private $api_sender_address = '';

    /**
     * @description Variable to store Sender Contact Number. It will be printed on AWB labels.
     * 
     * @var string 
     */
    private $api_sender_no = '';

    /**
     * @description Variable to store Default Order note. It will be printed on AWB labels.
     * 
     * @var string 
     */
    private $special_instructions = '';


    private $order_details = '';

    /**
     * @description Variable to store default weight. It will be used when order weight is null.
     * 
     * @var string 
     */
    private $default_weight = '';

    /**
     * 
     * @description Constructor function for setting up all default settings
     */
    public function __construct() {

        $this->api_url = 'http://new.leopardscod.com/webservice/';

        $this->api_enabled = get_option(self::$id . '-api_enabled');

        $this->api_key = get_option(self::$id . '-api_key');

        $this->api_password = get_option(self::$id . '-api_password');

        $this->user_email = get_option(self::$id . '-sender_email');

        $this->api_sender_name = get_option(self::$id . '-sender_name');

        $this->api_sender_address = get_option(self::$id . '-sender_address');

        $this->api_sender_no = get_option(self::$id . '-sender_no');

        $this->origin_city = get_option(self::$id . '-origin_city');

        $this->special_instructions = get_option(self::$id . '-special_instructions');

        $this->order_details = get_option(self::$id . '-api_show_order_details');

        $this->default_weight = get_option(self::$id . '-packet_weight');

        add_filter('woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50);

        add_action('woocommerce_settings_tabs_' . self::$id, __CLASS__ . '::settings_tab');

        add_action('woocommerce_update_options_' . self::$id, __CLASS__ . '::update_settings');

        add_action('woocommerce_order_details_after_order_table', array($this, 'leopards_show_tracking_order_detail'), 10, 1);

      

        if (($this->api_enabled == 'yes') && ($this->user_email != '') && ($this->api_sender_name != '') && ($this->api_sender_address != '') && ($this->api_sender_no != '')  && ($this->default_weight != '')) {

            add_action('add_meta_boxes_shop_order', array($this, 'track_meta_boxes'));

            add_action('wp_ajax_lcs_sync_all_cities', array($this, 'fetch_cities_from_lcs'));

            add_action('wp_ajax_leopards_book_packet', array($this, 'leopards_book_packet'));

            add_action('wp_ajax_leopards_book_preview_packet', array($this, 'leopards_book_preview_packet'));

            add_action('wp_ajax_leopards_bulk_preview_packet', array($this, 'leopards_bulk_preview_packet'));

            add_action('wp_ajax_leopards_cancel_packet', array($this, 'leopards_cancel_packet'));

            add_action('wp_ajax_leopards_track_packet', array($this, 'leopards_track_packet'));

            add_action( 'wp_ajax_nopriv_leopards_track_packet', array($this, 'leopards_track_packet' ));

            add_filter( 'bulk_actions-edit-shop_order', array($this,'lcs_add_bulk_shipment'));
            
            add_action('wp_ajax_leopards_book_packet_modal', array($this, 'leopards_book_packet_modal'));

            add_action('wp_ajax_leopards_bulk_check_cities', array($this, 'leopards_bulk_check_cities'));

            add_filter( 'manage_edit-shop_order_columns', array($this,'lcs_tracking_column' ));

            add_action( 'manage_shop_order_posts_custom_column', array($this,'lcs_tracking_column_content'));

            add_action('admin_footer', array($this,'modal_html_bulk'));

        }
    }

    public function lcs_add_bulk_shipment( $bulk_array ) {
        $bulk_array['lcs_bulk_shipment'] = 'LCS Bulk Shipment';
        return $bulk_array;
     
    }


    public function lcs_tracking_column( $column ) {
        $column['lcs_tracking_number'] = 'Tracking Number';
        return $column;
    }

    public function lcs_tracking_column_content($column){
        if ( 'lcs_tracking_number' === $column ) {
            global $post , $wpdb;
            $tracking_no_column = $wpdb->get_results("SELECT track_no, is_cancel FROM ". $wpdb->prefix ."leopards_courier_sheet WHERE order_id = ".$post->ID." ORDER BY id DESC Limit 1");            
           // print_r($tracking_no_column[0]->track_no);
             if (count($tracking_no_column) > 0){
                 if ($tracking_no_column[0]->is_cancel == 0){
                    $get_trackno = $tracking_no_column[0]->track_no;
                    echo $get_trackno; 
                 }
                 else{
                    echo '--';
                 }
             }
        }
    }

    public function leopards_bulk_check_cities() {
        global $wpdb;
		$array_order_ids = $_REQUEST['array_order_ids'];
        $status = array(
            'success' => 0,
            'message' => ''
        );
        
        $cities = $this->get_cities(true);

        $cities_db = array();
        $cities_with_id = array();
        if(!empty($array_order_ids)) {
            foreach ($cities as $key => $city) {
                    $cities_db[] = trim(strtolower($city['city_val']));
                    $cities_with_id[] = array(
                        'city_id' => $city['city_id'],
                        'city_value' => trim(strtolower($city['city_val'])),
                        'shipment_type' => $city['shipment_type']
                    );
            }
            $modal_array = array();
            foreach ($array_order_ids as $order_id) {
                $order_id_object = wc_get_order($order_id);

                $order_city = empty($order_id_object->get_shipping_city()) ? $order_id_object->get_billing_city() : $order_id_object->get_shipping_city();
                $check_if_shipment_created = $wpdb->get_results("SELECT is_cancel, order_id, id FROM ". $wpdb->prefix ."leopards_courier_sheet WHERE order_id = ".$order_id." ORDER BY id DESC LIMIT 0,1");
                if(count($check_if_shipment_created) >0) {
                    $is_cancelled = $check_if_shipment_created[0]->is_cancel;
                }else {
                    $is_cancelled = 1;
                }
                if(in_array(trim(strtolower($order_city)), $cities_db)) {
                   $modal_array[] = array(
                    'order_id'  => $order_id,
                    'order_url' => get_edit_post_link($order_id),
                    'city_name' => $order_city,
                    'is_match'  => 1,
                    'is_cancelled' => $is_cancelled, 
                    'price' => $order_id_object->get_total(),
                    'customer_name' => ($order_id_object->get_shipping_first_name() == '' && $order_id_object->get_shipping_last_name() == '') ? $order_id_object->get_billing_first_name().' '.$order_id_object->get_billing_last_name() : $order_id_object->get_shipping_first_name().' '.$order_id_object->get_shipping_last_name()
                   );

                } else {
                    $modal_array[] = array(
                    'order_id'  => $order_id,
                    'order_url' => get_edit_post_link($order_id),
                    'city_name' => $order_city,
                    'is_match'  => 0,
                    'is_cancelled' => $is_cancelled,
                    'price' => $order_id_object->get_total(),
                    'customer_name' => ($order_id_object->get_shipping_first_name() == '' && $order_id_object->get_shipping_last_name() == '') ? $order_id_object->get_billing_first_name().' '.$order_id_object->get_billing_last_name() : $order_id_object->get_shipping_first_name().' '.$order_id_object->get_shipping_last_name()
                   );
                }

            }
            $cities_array = array();
            $cities_array['order_arr'] = $modal_array;
            $cities_array['cities_arr'] = $cities_with_id;

            //$status['success'] = 1;

        } else {
        	$cities_array['order_arr'] = '';
        	$cities_array['cities_arr'] = '';
        }
        echo json_encode($cities_array);
        die();
    }

    
    public function modal_html_bulk(){
           if(isset($_GET['post_type']) == 'shop_order') {
            echo '<div id="lcs_bulk_shipment_modal" class="leopards-courier-modal">
                        <div class="modal-content">
                            <div class="lcs-modal-header">
                                <h4 class="lcs-modal-title">Bulk Shipment</h4>
                                <span class="close">&times;</span>
                            </div>
                            <div class="leopards-bulk-track">
                            <table style="width:100%" class="wp-list-table widefat fixed striped posts"><thead><tr><th class="lcs_orderid">Order ID</th><th class="customer_cities">Customer City</th><th class="customer_name">Customer Name</th><th class="lcs_cities">LCS City</th><th class="lcs_shipment">Shipment Type</th><th class="lcs_actual_price">Actual Price</th><th class="lcs_status">Status</th><th class="lcs_status">Preview Order Details</th></tr></thead><tbody></tbody></table>
                            <div class="lcs_response">
                            <ul class="order_notes"><li rel="96" class="note system-note"><div class="note_content"><p class="lcs_message"></p></div></li></ul></div>
                            <button type="button" class="lcs_bulk_shipment_btn button">Create Bulk Shipment</button>
                            <p  class="select_msg">Please select correct LCS city from dropdown(s).</p>
                            </div>
                        
                        </div>

                </div>';
        }
    }
    /**
     * 
     * @description function for showing tracking order details
     * 
     * @param object $order
     * 
     */

    public function leopards_show_tracking_order_detail($order) {

        global $woocommerce, $post, $wpdb;
        $tracking_no = '';
        $query_select = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}leopards_courier_sheet WHERE  is_cancel = 0 AND  order_id=".$order->get_id(). ' limit 0,1' ,ARRAY_A);
        if(count($query_select) > 0){
            $tracking_no = $query_select[0]['track_no'];
        }else{
            $results = get_metadata('post', $order->get_id(), self::$id . '-tracking_no', true);
            if (trim($results) != '') {
                $recent_tracking_no = explode(',', $results);

                $tracking_no = $recent_tracking_no[count($recent_tracking_no) - 1];

            }
        }




        if($tracking_no != '' ){
            echo '<h2>Shipment Details</h2><p>You shipment(s) will fullfill by <a href="https://leopardscourier.com/pk/">Leopards Courier Services</a>.<br>Tracking Number: <strong>' . $tracking_no. '</strong>  </p>';


            echo '<p class="btn-ordertrack">Click here to track your order.</p>
                <input type="button" class="track-orders leopards-courier-track-packet-btn button" data-lcstrack="' . $tracking_no . '" value="Track Shipment">';

            echo '<div id="leopards-courier-modal" class="leopards-courier-modal leopoard-tracking-modal">
                    <div class="modal-content">
                        <div class="lcs-modal-header">
                            <h4 class="lcs-modal-title">Tracking information</h4>
                            <span class="close">&times;</span>
                        </div>
                        <div class="leopards-tracking-details"></div>
                    </div>
                </div>';
        }


    }


     /**
     * 
     * @description function for creating meta box for LCS shipments
     * 
     */

    public function track_meta_boxes() {

        add_meta_box('track_meta-box', __('Leopard Details', 'leopards-courier'), array($this, 'lcs_save_shipment'), 'shop_order', 'normal', 'default'
        );
    }

    /**
     * 
     * @description Function for fetching and showing all tracking statuses of a shipment
     * 
     */

    public function leopards_track_packet() {

       /* check_ajax_referer('ajax_nonce', 'nonce');*/

        $tracking_number = $_REQUEST['leopards_tracking_no'];

        $status = array(
            'success' => 0,
            'message' => ''
        );

        if (isset($tracking_number) && !empty($tracking_number)) {

            $curl_response = $this->curlrequest("trackBookedPacket", $tracking_number);

            if (!empty((array) $curl_response)) {

                if ($curl_response->status == 1) {
                      
                     $cancel_booked_status =  $curl_response->packet_list[0]->booked_packet_status;
                   

                    $html = '';
                    $html .= '<p class="current-status"> Current Status : ' . $cancel_booked_status. '</p>
                    <table class="widefat fixed">            
                    <thead>
                    <tr>   
                    <th>Receiver Name</th>    
                    <th>Status</th>
                    <th>Activity Date Time</th>
                    <th>Reason:</th>
                    </tr>
                    </thead><tbody>';

                    if (!empty($curl_response->packet_list[0]->{'Tracking Detail'})) {

                        $reverse_list = array_reverse($curl_response->packet_list[0]->{'Tracking Detail'});
                        
                        foreach ($reverse_list as $row) {
                        	
                        	$html .= '<tr><td>' . $row->Reciever_Name . '</td>';
                            $html .= '<td>' . $row->Status . '</td>';
                            $html .= '<td>' . $row->Activity_datetime . '</td>';
                            $html .= '<td>' . $row->Reason . '</td></tr>';
                        }
                    }
                   
                    $html .= '<tr><td></td>';
                    $html .= '<td> Consignment Booking at ' . $curl_response->packet_list[0]->destination_city_name . '</td>';
                    $html .= '<td>' . $curl_response->packet_list[0]->booking_date . '</td>';
                    $html .= '<td></td></tr>';
                    $html .= '</tbody>';
                    $html .= '</table>';

                    $status['success'] = 1;

                    $status['message'] = $html;
                 } elseif ($curl_response->status == 0) {

                    $status['message'] = $curl_response->error;
                } else {

                    $status['message'] = 'Unable to Track packet contact leopards courier support';
                }
            } else {

                $status['message'] = 'Unable to Track packet contact leopards courier support';
            }
        } else {

            $status['message'] = "Tracking Number Is Empty";
        }

        echo json_encode($status);
        //  print_r($curl_response);
        // exit('ssd');
        die();
    }

   /**
     * 
     * @description Function for cancel shipment
     * 
     */

    public function leopards_cancel_packet() {

        global $wpdb;

        $tracking_number = $_REQUEST['leopards_tracking_no'];

        $status = array(
            'success' => 0,
            'message' => ''
        );

         if (isset($tracking_number) && !empty($tracking_number)) {

            $curl_response = $this->curlrequest("cancelBookedPackets", '', '', $tracking_number);



            if (!empty((array) $curl_response)) {

                if ($curl_response->status == 1) {

                    $status['success'] = 1;

                    $status['message'] = 'Packet has been successfully cancelled';
                       
    
                $cancel_courier_query_update = $wpdb->update( $wpdb->prefix . 'leopards_courier_sheet',
                    array('is_cancel' => 1 ), array('track_no' => $tracking_number));
                    
                } 
                    elseif ($curl_response->status == 0) {

                        if( $curl_response->error->$tracking_number ==  'Already Cancelled.'){
                            $status['success'] = 1;
                            $status['message'] = 'Packet has been successfully cancelled';
                            $wpdb->update( $wpdb->prefix . 'leopards_courier_sheet',
                                array('is_cancel' => 1 ), array('track_no' => $tracking_number));
                        }else{
                            $status['message'] = $curl_response->error;
                        }

                } else {

                    $status['message'] = 'Unable to cancel packet contact leopards courier support';
                }
            } else {

                $status['message'] = 'Unable to cancel packet contact leopards courier support';
            }
        } else {

            $status['message'] = "Tracking Number Is Empty";
        }

        echo json_encode($status);
        die();
    }



    public function get_order_item_details($order_id){

        $order = wc_get_order( $order_id );

        $formated_string = '';

        $itemsCount = count($order->get_items());

        $count = 1;

        foreach ($order->get_items() as $item_id => $item_data) {


            $product = $item_data->get_product();
            $product_name = $product->get_name();

            $item_quantity = $item_data->get_quantity();
            // Displaying this data (to check)
            $formated_string .= $product_name.' -- Qty: '.$item_quantity;
            if($product->get_sku() != '') $formated_string .= ' -- SKU: '.$product->get_sku();
            if($itemsCount != $count) $formated_string .= ' -------- ';

            $count ++;
        }

        return nl2br($formated_string);

    }

    
    /**
     * 
     * @description Function for create shipment
     * 
     */

    public function leopards_book_packet() {

        global $woocommerce, $post , $wpdb;

        date_default_timezone_set("Asia/Karachi");

        $order_id = $_REQUEST['leopards_order_id'];

        $destination_city = $_REQUEST['leopards_destination_city'];

        $shipment_type = $_REQUEST['selected_shipment_type'];

        $actual_price = $_REQUEST['price'];

        $orders = wc_get_order($order_id);

        $customer_note = '';

        $customer_note = ($orders->get_customer_note() != '') ? $orders->get_customer_note() : $this->special_instructions;
    
        if($customer_note == '') {
                    
                    $customer_note = 'Handle With Care';
        }

        $packet_weight = ( $this->get_order_weight($orders) > 0 ) ? $this->get_order_weight($orders) : $this->default_weight;


        if($this->order_details == 'yes'){
            $customer_note = $this->get_order_item_details($order_id) . ' ------ '.$customer_note;
        }

        $book_packet_array = array(
            'booked_packet_weight' => $packet_weight,
            'booked_packet_vol_weight_w' => '',
            'booked_packet_vol_weight_h' => '',
            'booked_packet_vol_weight_l' => '',
            'booked_packet_no_piece' => 1,
            'booked_packet_collect_amount' => ($actual_price == '') ? $orders->get_total() : $actual_price,
            'booked_packet_order_id' => $order_id,
            'origin_city' => $this->origin_city > 0 ? $this->origin_city : 'self',
            'destination_city' => $destination_city,
            'shipment_name_eng' => $this->api_sender_name,
            'shipment_email' => $this->user_email,
            'shipment_phone' => $this->api_sender_no,
            'shipment_address' => $this->api_sender_address,
            'consignment_name_eng' => empty($orders->get_shipping_first_name() && $orders->get_shipping_last_name()) ? $orders->get_billing_first_name().' '.$orders->get_billing_last_name() : $orders->get_shipping_first_name().' '.$orders->get_shipping_last_name(),
            'consignment_email' => $orders->get_billing_email(),
            'consignment_phone' => $orders->get_billing_phone(),
            'consignment_phone_two' => '',
            'consignment_phone_three' => '',
            'consignment_address' => empty($orders->get_shipping_address_1()) ? $orders->get_billing_address_1() . ' ' . $orders->get_billing_address_2() : $orders->get_shipping_address_1() . ' ' . $orders->get_shipping_address_2(),
            'special_instructions' => $customer_note,
            'shipment_type' => $shipment_type
        );



        $query_get_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}leopards_courier_sheet WHERE order_id=".$order_id,ARRAY_A);
       
        $is_all_cancelled  = 1;
        if(!empty((array) $query_get_data)) {
            
            foreach ($query_get_data as $key => $query_db) {
                if($query_db['is_cancel'] != 1 ){
                    $is_all_cancelled = 0;
                    break;
                }  
            }
        }
        if($is_all_cancelled == 1){
            $curl_response = $this->curlrequest("bookPacket", '', $book_packet_array);
        }else{
            $status = array(
                'success' => 1,
                'message' => 'Shipment Already Created'
            );
            echo json_encode($status);
            die();
        }
     

        $status = array(
            'success' => 0,
            'message' => ''
        );
        if (!empty((array) $curl_response)) {
            if ($curl_response->status == 1) {
             
                $results = get_metadata('post', $order_id, self::$id . '-tracking_no', true);
               
                if ($results != "") {
                
                    $results .= ',' . $curl_response->track_number;
                } else {
                   
                    $results = $curl_response->track_number;
                }

                     $current_user_id = get_current_user_id();
                     $is_load_sheet = 0;
                     $is_cancelled = 0;
                     $slip_link = $curl_response->slip_link;


                $query_insert = $wpdb->insert($wpdb->prefix . 'leopards_courier_sheet', array(
                    'track_no' => $curl_response->track_number,
                    'user_id' => $current_user_id,
                    'order_id' => $order_id,
                    'booking_datetime' => date("Y-m-d h:i:sa"),
                    'is_sheet' => $is_load_sheet,
                    'is_cancel' => $is_cancelled,
                    'slip_url' => $slip_link
                ));

                    $note = ($actual_price == '') ? $orders->get_total() : $actual_price;
                    $orders->add_order_note('Shipment created with price '.$note);
                    $orders->update_meta_data(self::$id . '-tracking_no', $results);
                    $orders->update_meta_data(self::$id . '-slip_link', $slip_link);
                    $orders->save_meta_data();



                $status['success'] = 1;
                $status['message'] = "Packet has been booked successfully";

            } else if ($curl_response->status == 0) {

                $status['message'] = $curl_response->error;

            } else {

                $status['message'] = 'Unable to book packet contact leopards courier support';
            }
        } else {

            $status['message'] = 'Unable to book packet contact leopards courier support';
        }

        echo json_encode($status);

        die();
    }

    /**
     * Preview Book Packet Request
     */
    public function leopards_book_preview_packet() {
        
        global $woocommerce, $post , $wpdb;

        $status = [
            'success' => false,
            'msg' => ''
        ];

        date_default_timezone_set("Asia/Karachi");

        $order_id = $_REQUEST['leopards_order_id'];

        $destination_city = $_REQUEST['leopards_destination_city'];

        $shipment_type = $_REQUEST['selected_shipment_type'];

        $actual_price = $_REQUEST['price'];

        $orders = wc_get_order($order_id);

        $customer_note = '';

        $customer_note = ($orders->get_customer_note() != '') ? $orders->get_customer_note() : $this->special_instructions;
    
        if($customer_note == '') {
                    
                    $customer_note = 'Handle With Care';
        }

        $packet_weight = ( $this->get_order_weight($orders) > 0 ) ? $this->get_order_weight($orders) : $this->default_weight;


        if($this->order_details == 'yes'){
            $customer_note = $this->get_order_item_details($order_id) . ' ------ '.$customer_note;
        }

        $items = [];
        $loop_count = 1;
        foreach ( $orders->get_items() as $item_id => $item ) {
            $product_id = $item->get_product_id();
            $variation_id = $item->get_variation_id();
            $product = $item->get_product();
            $product_name = $item->get_name();
            $quantity = $item->get_quantity();
            $items['item_name'][] = $loop_count.'. '.$product_name.' -- Quantity: '.$quantity;
            $loop_count++;
        }

        $book_packet_array = array(
            'booked_packet_weight' => $packet_weight,
            'booked_packet_vol_weight_w' => '',
            'booked_packet_vol_weight_h' => '',
            'booked_packet_vol_weight_l' => '',
            'booked_packet_no_piece' => 1,
            'booked_packet_collect_amount' => ($actual_price == '') ? $orders->get_total() : $actual_price,
            'booked_packet_order_id' => $order_id,
            'origin_city' => $this->origin_city > 0 ? $this->origin_city : 'self',
            'destination_city' => $destination_city,
            'shipment_name_eng' => $this->api_sender_name,
            'shipment_email' => $this->user_email,
            'shipment_phone' => $this->api_sender_no,
            'shipment_address' => $this->api_sender_address,
            'consignment_name_eng' => empty($orders->get_shipping_first_name() && $orders->get_shipping_last_name()) ? $orders->get_billing_first_name().' '.$orders->get_billing_last_name() : $orders->get_shipping_first_name().' '.$orders->get_shipping_last_name(),
            'consignment_email' => $orders->get_billing_email(),
            'consignment_phone' => $orders->get_billing_phone(),
            'consignment_phone_two' => '',
            'consignment_phone_three' => '',
            'consignment_address' => empty($orders->get_shipping_address_1()) ? $orders->get_billing_address_1() . ' ' . $orders->get_billing_address_2() : $orders->get_shipping_address_1() . ' ' . $orders->get_shipping_address_2(),
            'special_instructions' => $customer_note,
            'shipment_type' => $shipment_type
        );
        
        $status['success'] = true;
        $status['data'] = $book_packet_array;
        $status['data']['items'] = $items;
        echo json_encode($status);

        die();
    }

    /**
     * Bulk Preview Data
     */
    public function leopards_bulk_preview_packet(){
        $status = [
            'success' => false,
            'msg' => ''
        ];

        global $woocommerce,$post,$wpdb;

        $order_id = $_REQUEST['leopards_order_id'];

        $destination_city = $_REQUEST['leopards_destination_city'];

        $shipment_type = $_REQUEST['selected_shipment_type'];

        $actual_price = $_REQUEST['price'];

        $orders = wc_get_order($order_id);

        $customer_note = '';

        $customer_note = ($orders->get_customer_note() != '') ? $orders->get_customer_note() : $this->special_instructions;
    
        if($customer_note == '') {
                    
                    $customer_note = 'Handle With Care';
        }

        $packet_weight = ( $this->get_order_weight($orders) > 0 ) ? $this->get_order_weight($orders) : $this->default_weight;


        if($this->order_details == 'yes'){
            $customer_note = $this->get_order_item_details($order_id) . ' ------ '.$customer_note;
        }
        $items = [];
        $loop_count = 1;
        foreach ( $orders->get_items() as $item_id => $item ) {
            $product_id = $item->get_product_id();
            $variation_id = $item->get_variation_id();
            $product = $item->get_product();
            $product_name = $item->get_name();
            $quantity = $item->get_quantity();
            $items['item_name'][] = $loop_count.'. '.$product_name.' -- Quantity: '.$quantity;
            $loop_count++;
        }

        $book_packet_array = array(
            'booked_packet_weight' => $packet_weight,
            'booked_packet_vol_weight_w' => '',
            'booked_packet_vol_weight_h' => '',
            'booked_packet_vol_weight_l' => '',
            'booked_packet_no_piece' => 1,
            'booked_packet_collect_amount' => ($actual_price == '') ? $orders->get_total() : $actual_price,
            'booked_packet_order_id' => $order_id,
            'origin_city' => $this->origin_city > 0 ? $this->origin_city : 'self',
            'destination_city' => $destination_city,
            'shipment_name_eng' => $this->api_sender_name,
            'shipment_email' => $this->user_email,
            'shipment_phone' => $this->api_sender_no,
            'shipment_address' => $this->api_sender_address,
            'consignment_name_eng' => empty($orders->get_shipping_first_name() && $orders->get_shipping_last_name()) ? $orders->get_billing_first_name().' '.$orders->get_billing_last_name() : $orders->get_shipping_first_name().' '.$orders->get_shipping_last_name(),
            'consignment_email' => $orders->get_billing_email(),
            'consignment_phone' => $orders->get_billing_phone(),
            'consignment_phone_two' => '',
            'consignment_phone_three' => '',
            'consignment_address' => empty($orders->get_shipping_address_1()) ? $orders->get_billing_address_1() . ' ' . $orders->get_billing_address_2() : $orders->get_shipping_address_1() . ' ' . $orders->get_shipping_address_2(),
            'special_instructions' => $customer_note,
            'shipment_type' => $shipment_type
        );

        $status['success'] = true;
        $status['data'] = $book_packet_array;
        $status['data']['items'] = $items;
        echo json_encode($status);

        die();

    }


public function leopards_book_packet_modal() {
	global $woocommerce, $post , $wpdb;
    date_default_timezone_set("Asia/Karachi");
 
    if(is_array($_POST['table_data_array']) && !empty($_POST['table_data_array'])) {
        $bulk_order_data = $_POST['table_data_array'];
        $status_array = [];
        foreach ($bulk_order_data as $key => $value) {

            $order_id = $value[0]['order_id'];
            $destination_city = $value[0]['city_value'];
            $shipment_type = $value[0]['shipment_value'];
            $shipment_price = $value[0]['shipment_price'];

             $status = array(
                'success' => 0,
                'message' => 'Unknown error',
                'order_id' => $order_id
            );

            if(!empty( $order_id) && !empty($destination_city) && !empty($shipment_type) && !empty($shipment_price)) {

            

                $orders = wc_get_order($order_id);
                $customer_note = '';

                $customer_note = ($orders->customer_note != '') ? $orders->customer_note : $this->special_instructions;
        
                if($customer_note == '') {
                            
                    $customer_note = 'Handle With Care';
                }

                $packet_weight = ( $this->get_order_weight($orders) > 0 ) ? $this->get_order_weight($orders) : $this->default_weight;


                if($this->order_details == 'yes'){
                    $customer_note = $this->get_order_item_details($order_id) . ' ------ '.$customer_note;
                }

                $book_packet_array = array(
                'booked_packet_weight' => $packet_weight,
                'booked_packet_vol_weight_w' => '',
                'booked_packet_vol_weight_h' => '',
                'booked_packet_vol_weight_l' => '',
                'booked_packet_no_piece' => 1,
                'booked_packet_collect_amount' => ($shipment_price == '') ? $orders->get_total() : $shipment_price,
                'booked_packet_order_id' => $order_id,
                'origin_city' => $this->origin_city > 0 ? $this->origin_city : 'self',
                'destination_city' => $destination_city,
                'shipment_name_eng' => $this->api_sender_name,
                'shipment_email' => $this->user_email,
                'shipment_phone' => $this->api_sender_no,
                'shipment_address' => $this->api_sender_address,
                'consignment_name_eng' => empty($orders->get_shipping_first_name() && $orders->get_shipping_last_name()) ? $orders->get_billing_first_name().' '.$orders->get_billing_last_name() : $orders->get_shipping_first_name().' '.$orders->get_shipping_last_name(),
                'consignment_email' => $orders->get_billing_email(),
                'consignment_phone' => $orders->get_billing_phone(),
                'consignment_phone_two' => '',
                'consignment_phone_three' => '',
                'consignment_address' => empty($orders->get_shipping_address_1()) ? $orders->get_billing_address_1() . ' ' . $orders->get_billing_address_2() : $orders->get_shipping_address_1() . ' ' . $orders->get_shipping_address_2(),
                'special_instructions' => $customer_note,
                'shipment_type' => $shipment_type
                );


        
                $query_get_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}leopards_courier_sheet WHERE order_id=".$order_id,ARRAY_A);
                
                $is_all_cancelled  = 1;
                if(!empty((array) $query_get_data)) {
                    
                    foreach ($query_get_data as $key => $query_db) {
                        if($query_db['is_cancel'] != 1 ){
                            $is_all_cancelled = 0;
                            break;
                        }  
                    }
                }
                if($is_all_cancelled == 1){
                    $curl_response = $this->curlrequest("bookPacket", '', $book_packet_array);
                    if (!empty((array) $curl_response)) {
                        if ($curl_response->status == 1) {
                        
                            $results = get_metadata('post', $order_id, self::$id . '-tracking_no', true);
                        
                            if ($results != "") {
                            
                                $results .= ',' . $curl_response->track_number;
                            } else {
                            
                                $results = $curl_response->track_number;
                            }
    
                                $current_user_id = get_current_user_id();
                                $is_load_sheet = 0;
                                $is_cancelled = 0;

                            $slip_link = $curl_response->slip_link;
    
                            $query_insert = $wpdb->insert($wpdb->prefix . 'leopards_courier_sheet', array(
                                'track_no' => $curl_response->track_number,
                                'user_id' => $current_user_id,
                                'order_id' => $order_id,
                                'booking_datetime' => date("Y-m-d h:i:sa"),
                                'is_sheet' => $is_load_sheet,
                                'is_cancel' => $is_cancelled,
                                'slip_url'  => $slip_link
                            ));
    
    

                                $note = ($shipment_price == '') ? $orders->get_total() : $shipment_price;
                                $orders->add_order_note('Bulk Shipment created with price '.$note);
                                $orders->update_meta_data(self::$id . '-tracking_no', $results);
                                $orders->update_meta_data(self::$id . '-slip_link', $slip_link);
                                $orders->save_meta_data();
    
    
    
                            $status['success'] = 1;
                            $status['message'] = "Packet has been booked successfully";
                            $status['order_id'] = $order_id;
    
                        } else if ($curl_response->status == 0) {
                            $status['success'] = 0;
                            $status['message'] = $curl_response->error;
                            $status['order_id'] = $order_id;
    
                        } else {
                            $status['success'] = 0;
                            $status['message'] = 'Unable to book packet contact leopards courier support';
                            $status['order_id'] = $order_id;
                        }
                    } else {
                        $status['success'] = 0;
                        $status['message'] = 'Unable to book packet contact leopards courier support';
                        $status['order_id'] = $order_id;
                    }

                }else{
                    $status['success'] = 0;
                    $status['message'] = 'Shipment Already created';
                    $status['order_id'] = $order_id;
                }

            } else {
                $status['success'] = 0;
                $status['message'] = 'Shipment Already created';
                $status['order_id'] = $order_id;
            }

            $status_array[] = $status;
        }
        echo json_encode($status_array);
    }else {
        $status['success'] = 0;
        $status['message'] = 'Unable to book packet contact leopards courier support';
        echo json_encode($status);
    }
     
die();
}
   /**
     * 
     * @description Function for Saving shipment details in Database.
     * 
     */


      public function add_all_tracking_db(){
          global $woocommerce, $post, $wpdb;


          $query_select = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}leopards_courier_sheet WHERE order_id=".$post->ID,ARRAY_A);

          $results = get_metadata('post', $post->ID, self::$id . '-tracking_no', true);


          if(!count($query_select) > 0 && $results != ''){
              if (trim($results) != '') {
                  $recent_tracking_no = explode(',', $results);

                  $tracking_count = count($recent_tracking_no);

                  if($tracking_count > 0 ) {
                      $count = 1;
                      foreach ($recent_tracking_no as $tracking_no) {

                          $get_slip_link = get_metadata('post', $post->ID, self::$id . '-slip_link', true);

                          $args = array(
                              'track_no' => $tracking_no,
                              'user_id' => get_current_user_id(),
                              'order_id' => $post->ID,
                              'booking_datetime' => date("Y-m-d h:i:sa"),
                              'slip_url' => $get_slip_link
                          );

                          if ($count == $tracking_count){
                              $args['is_sheet'] = 0;
                              $args['is_cancel'] = 0;
                          }else{
                              $args['is_sheet'] = 1;
                              $args['is_cancel'] = 1;
                          }
                          $query_insert = $wpdb->insert($wpdb->prefix . 'leopards_courier_sheet', $args);

                          $count++;
                      }

                  }

              }


          }
      }


      public function lcs_save_shipment(){

          global $woocommerce, $post, $wpdb;

          $this->add_all_tracking_db();

          $query_get_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}leopards_courier_sheet WHERE order_id=".$post->ID,ARRAY_A);

          if (!empty((array) $query_get_data)) {

            $is_all_cancelled  = 1;


            foreach ($query_get_data as $key => $query_db) {
                if($query_db['is_cancel'] != 1 ){
                    $is_all_cancelled = 0;
                    break;
                }
            }


            if($is_all_cancelled == 1){
                echo '<p><input name="lcs-btn" type="button" value="Create Shipment" class="leopards-courier-book-packet_btn button button-primary" data-orderid="' . $post->ID . '"></p>';
            }


            if (!empty($query_db) || $query_db == 1) {


                 echo '<table class="widefat fixed "><thead><tr><th>Tracking Number</th><th>Date</th><th>Cancel Order:</th><th>Track Order</th><th>Order Label</th></tr></thead><tbody>';

                $query_select = array_reverse($query_get_data);

                $c = true;

                foreach ($query_select as $key => $value) {

                    $track_number = $value['track_no'];
                    $track_booking_date = $value['booking_datetime'];
                    $booked_packet_status = $value['is_cancel'];
                    $sheet_status = $value['is_sheet'];
                    $slip_url = $value['slip_url'];

                    echo '<tr ' . (($c = !$c) ? ' class="alternate"' : '') . '><td>' . $track_number . '</td><td>' . $track_booking_date . '</td><td>';

                  if ($booked_packet_status == '1') {

                        echo 'Cancelled';
                    } else {

                    echo '<input type="button" value="Cancel Shipment" class="leopards-courier-cancel-packet-btn button" data-lcscancel="' . $track_number . '">';
                    }

                    echo '</td><td>';

                    if ($booked_packet_status == '1') {

                        echo '--';

                } else {

                 echo '<input type="button" class="leopards-courier-track-packet-btn button" data-lcstrack="' . $track_number . '" value="Track Shipment"></td>';

            echo '<td><a href="'.$slip_url.'" class="button button-primary" download>Download Label</a>';

            }

                echo '</td></tr>';
         }

                echo '</tbody></table>';

           }

        } else {

              echo '<p><input name="lcs-btn" type="button" value="Create Shipment" class="leopards-courier-book-packet_btn button button-primary" data-orderid="' . $post->ID . '"></p>';
               
     }

            echo '<div id="leopards-courier-modal" class="leopards-courier-modal leopoard-tracking-modal">
                    <div class="modal-content">
                        <div class="lcs-modal-header">
                            <h4 class="lcs-modal-title">Tracking information</h4>
                            <span class="close">&times;</span>
                        </div>
                        <div class="leopards-tracking-details"></div>
                    </div>
                </div>';


        $cities = $this->get_cities(true);
        $cities_options = '';
        $order_obj = wc_get_order( $post->ID );
        $order_data = $order_obj->get_data();
        $price = $order_obj->get_total();
        // echo $order_shipping_city = $order_data['billing']['city'].'assd';
        $order_city = empty($order_data['shipping']['city']) ? $order_data['billing']['city'] : $order_data['shipping']['city'];
        
        $cities_options .= '<option>Select City</option>';
        $isfound = false;
      foreach ($cities as $key => $city) {
        if(trim(strtolower($order_city)) == trim(strtolower($city['city_val']))) {
            $class = 'selected';
            $isfound = true;
        } else {
             $class = '';
        }
         $cities_options .= '<option '.$class.' data-ship-type = "' . implode(',', $city['shipment_type']) . '" value="' . $city['city_id'] . '">' . $city['city_val'] . '</option>';
        }
        

        $shipment_type_options = '';
        // $shipment_type_options .= '<option '.$class.'>Select Shipment Type</option>';
        foreach ($cities[0]['shipment_type'] as $key => $shipment) {

            $shipment_type_options .= '<option value="' . $shipment . '">' . $shipment . '</option>';
        }
        $outline_focus =  (!$isfound) ? 'outline-focus' : '';
        $payment_method = $this->get_payment_method_by_order_id($post->ID);
        $readonly = '';
        if($payment_method == 'cod') {
            $readonly = 'readonly';
        }
        echo '<div id="leopards-courier-modal-book-packet" class="leopards-courier-modal">
            
            <div class="modal-content">
                <div class="lcs-modal-header">
                    <h4 class="lcs-modal-title">Book Shipment</h4>
                    <span class="close">&times;</span>
                </div>
                <div class="lcs-modal-inner-content">
                    <div class="step-1">
                        <div class="lcs-select-wrap">
                            <label>Destination city</label>
                            <select class="select2 '. $outline_focus .'" id="lcs_select_city">' . $cities_options . '</select>
                        </div>
                        <div class="checkout-field"> 
                        </div>
                        <div class="lcs-select-wrap">
                            <label>shipment type</label>
                            <select class="select2 " id="lcs_select_shipment_type">' . $shipment_type_options . '</select>
                        </div>
                        <div class="lcs-select-wrap">
                            <label>Actual Price</label>
                            <input type="number" id="lcs_default_price" value="'.$price.'">
                        </div>
                        <div class="lcs-select-wrap">
                            <label>Customer City</label>
                            <input type="text" id="lcs_select_shipment_type" value="'.$order_city.'" readonly>
                        </div>
                        <div class="error-show"></div>
                    </div>
                    <div class="lcs-submit-btn"><input class="button button-primary" type="button" value="Back" id="lcs_submit_book_packet_back_btn" data-orderid="' . $post->ID . '"><input class="button button-primary" type="button" value="Submit" id="lcs_submit_book_packet_btn" data-orderid="' . $post->ID . '"><input class="button button-primary" type="button" value="Preview" id="lcs_submit_book_packet_preview_btn" data-orderid="' . $post->ID . '"></div>
                </div>
            </div>
        </div>';
    }

    /**
     * Get Payment Method of specific order
     */
    public function get_payment_method_by_order_id($order_id) {
        $payment_method = '';
        $payment_method = get_post_meta( $order_id, '_payment_method', true );
        return $payment_method;
    }

    
     
     /**
      * fetch_cities_from_lcs
      *
      * @return void
      */
     public function fetch_cities_from_lcs(){
        global $wpdb;

        $cities = array();

        $response = $this->curlrequest('getAllCities');

        if ($response->status == 1) {

            if (!empty($response->city_list)) {

                $wpdb->delete( $wpdb->prefix.'leopards_courier', array( 'store_key' => 'cities' ) );
                foreach ($response->city_list as $city) {

                    $cities[] = array(
                        'city_id' => $city->id,
                        'city_val' => $city->name, 
                        'shipment_type' => $city->shipment_type,
                        'allow_as_origin' => $city->allow_as_origin,
                        'allow_as_destination' => $city->allow_as_destination
                    );
                }

                
            }
        }

        $query_insert = $wpdb->insert($wpdb->prefix . 'leopards_courier', array(
            'store_key' => 'cities',
            'store_value' => serialize($cities)
        ));

     } 


    /**
     * @description Function for fetch cities from db
     * 
     * @global object $wpdb
     * @param boolean $with_shipment_type
     * @return array
     */


     public function get_cities($with_shipment_type = false,$allow_as_origin = false) {

        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}leopards_courier WHERE store_key = 'cities'", OBJECT);

        $cities = array();

        if (empty($results)) {

            $this->fetch_cities_from_lcs();
            $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}leopards_courier WHERE store_key = 'cities'", OBJECT);
            $cities = unserialize($results[0]->store_value);

        } else {

            $cities = unserialize($results[0]->store_value);
        }

        if (!$with_shipment_type) {

            $filtered_cities = array();

            if (!empty($cities)) {

                foreach ($cities as $key => $city) {
                    if($allow_as_origin && $city['allow_as_origin']){
                        $filtered_cities[$city['city_id']] = $city['city_val'];
                    }else{
                        $filtered_cities[$city['city_id']] = $city['city_val'];
                    }
                }
            }

            return $filtered_cities;
        }

        return $cities;
    }



  /**
     * @description Function for check and fetch API Password is exists
     * 
     * @param type $id
     * @return boolean
     */

    public function is_api_pwd_exist($id) {
        if (get_option($id . '-api_enabled') == 'yes' && !empty(get_option($id . '-api_key')) && !empty(get_option($id . '-api_password'))) {
            return true;
        } else {
            return false;
        }
    }


       /**
     * @description Function for add Settings tab in WooCommerce Settings page
     * 
     * @param array $settings_tabs
     * @return array
     */

    public static function add_settings_tab($settings_tabs) {

        $settings_tabs[self::$id] = __(self::$tab_name, 'leopards-courier');

        return $settings_tabs;
    }


     /**
     * 
     * @description Function for populate add fields of Settings tab in WooCommerce Settings page
     * 
     */

    public static function settings_tab() {

        woocommerce_admin_fields(self::get_settings());
    }

 /**
     * 
     * @description Function for update Setting fields
     * 
     */
    public static function update_settings() {

        $get_settings_data = $_POST;

        woocommerce_update_options(self::get_settings());

        if ($get_settings_data[self::$id . '-api_enabled']) {

            $check_credentials = new LeopardsApi();

            $result = $check_credentials->curlrequest('getAllCities');
            if ($result->status == 0) {

                if ($result->error == 'Invalid API Key/Password') {

                    add_action('admin_notices', array($check_credentials, 'wrong_api_notice'));
                }

                update_option(self::$id . '-api_enabled', 'no');
            }
        }
    }


  /**
     * 
     * @description Function for show Error Message when API key or API password is invalid
     * 
     */
    public function wrong_api_notice() {

            echo '<div class="notice notice-warning is-dismissible">
                    <p>Invalid API Key/Password</p>
                  </div>';
    }


    /**
     * 
     * @description Function for show settings tab in WooCommerce Settings page
     * 
     */
    public static function get_settings() {

        $check_query = new LeopardsApi();

        $citiesList = $check_query->get_cities(false,true);

        $settings = array(
            'section_title' => array(
                'name' => __('Leapard Courier', 'leopards-courier'),
                'type' => 'title',
                'desc' => '',
                'id' => self::$id . '-title'
            ),
            'api_enabled' => array(
                'id' => self::$id . '-api_enabled',
                'name' => __('Enable / Disable', 'leopards-courier'),
                'type' => 'checkbox',
                'default' => 'no',
            ),
            'environment' => array(
                'title' => __('Environment', 'leopards-courier'),
                'type' => 'select',
                'class' => 'wc-enhanced-select',
                'description' => __('This setting specifies whether you will process live Api, or whether you will process simulated orders using the  Sandbox.', 'leopards-courier'),
                'default' => 'live',
                'desc_tip' => true,
                'options' => array(
                    'live' => __('Live', 'leopards-courier'),
                    'sandbox' => __('Sandbox', 'leopards-courier'),
                ),
                'id' => self::$id . 'api_method',
            ),
            'api_key' => array(
                'name' => __('Api Key', 'leopards-courier'),
                'type' => 'text',
                'desc' => __('Please Enter Your Leopards Api Key', 'leopards-courier'),
                'desc_tip' => true,
                'id' => self::$id . '-api_key'
            ),
            'api_password' => array(
                'name' => __('Api Password', 'leopards-courier'),
                'type' => 'password',
                'desc' => __('Please Enter Your Leopards Api Password', 'leopards-courier'),
                'default' => '',
                'desc_tip' => true,
                'id' => self::$id . '-api_password'
            )
        );

        if ($check_query->is_api_pwd_exist(self::$id)) {

            $settings['sender_name'] = array(
                'name' => __('Sender Name', 'leopards-courier'),
                'type' => 'text',
                'desc' => __('Please Enter Courier Sender Name', 'leopards-courier'),
                'desc_tip' => true,
                'id' => self::$id . '-sender_name'
            );

            $settings['sender_email'] = array(
                'name' => __('Sender Email', 'leopards-courier'),
                'type' => 'email',
                'desc' => __('Please Enter Courier Sender Email', 'leopards-courier'),
                'desc_tip' => true,
                'id' => self::$id . '-sender_email'
            );

            $settings['sender_address'] = array(
                'name' => __('Sender Address', 'leopards-courier'),
                'type' => 'textarea',
                'desc' => __('Please Enter Courier Sender Address.', 'leopards-courier'),
                'desc_tip' => true,
                'id' => self::$id . '-sender_address'
            );

            $settings['sender_no'] = array(
                'name' => __('Sender Phone Number', 'leopards-courier'),
                'type' => 'number',
                'desc' => __('Please Enter Courier Sender Phone number', 'leopards-courier'),
                'desc_tip' => true,
                'id' => self::$id . '-sender_no'
            );

            $settings['special_instructions'] = array(
                'name' => __('Special Instructions', 'leopards-courier'),
                'type' => 'textarea',
                'desc' => __('Please Enter Special Instructions.', 'leopards-courier'),
                'desc_tip' => true,
                'default' => __('Handle With Care', 'leopards-courier'),
                'id' => self::$id . '-special_instructions'
            );

            $settings['order_details'] = array(
                'id' => self::$id . '-api_show_order_details',
                'name' => __('Show Order Detail In Special Instruction', 'leopards-courier'),
                'type' => 'checkbox',
                'default' => 'no',
            );

            $settings['packet_weight'] = array(
                'name' => __('Packet Weight', 'leopards-courier'),
                'type' => 'number',
                'desc' => __('Please Enter default packet weight .', 'leopards-courier'),
                'desc_tip' => true,
                'default' => __(250, 'leopards-courier'),
                'id' => self::$id . '-packet_weight'
            );
            $settings['origin_city'] =array(
                'title' => __('Select Origin City', 'leopards-courier'),
                'type' => 'select',
                'class' => 'wc-enhanced-select',
                'desc' => __('', 'leopards-courier'),
                'default' => '0',
                'desc_tip' => true,
                'options' => $citiesList,
                'id' => self::$id . '-origin_city',
            );
            $settings['sync_cities'] = array(
                'name' => __('Sync Cities', 'leopards-courier'),
                'type' => 'text',
                'default' => 'no',  
                'desc' => __('<button class="button-primary woocommerce-save-button lcs_sync_all_cities" id="lcs_sync_all_cities" type="submit">Sync Cities</button>', 'leopards-courier'),
                'id' => self::$id . '-sync_cities'
            );

        }

        $settings['section_end'] = array(
            'type' => 'sectionend',
            'id' => self::$id . '-section_end'
        );

        return apply_filters(self::$id, $settings);
    }



    /**
     * @description Function for perform CURL requests
     * 
     * @param string $request
     * @param string $track_numbers
     * @param array $book_packet_array
     * @param string $cancel_track_no
     * @param string $generate_sheet_array
     * @return object
     */

    public function curlrequest($request, $track_numbers = "", $book_packet_array = [], $cancel_track_no = "",$generate_sheet_array= []) {
        
        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $this->api_url . $request . '/format/json/');  // Write here Test or Production Link

        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl_handle, CURLOPT_POST, 1);

        $api_cred = array(
            'api_key' => $this->api_key,
            'api_password' => $this->api_password,
        );

        if ($track_numbers != "") {

            $api_cred = $api_cred + array("track_numbers" => $track_numbers);
        }

        if (!empty($book_packet_array)) {

            $api_cred = $api_cred + $book_packet_array;
        }

        if ($cancel_track_no != "") {

            $api_cred = $api_cred + array("cn_numbers" => $cancel_track_no);
        }


        if(!empty($generate_sheet_array)){
             $api_cred = $api_cred + $generate_sheet_array;
        }

         if(!empty($download_sheet_array)){
             $api_cred = $api_cred + $download_sheet_array;
        }

        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $api_cred);

        $buffer = curl_exec($curl_handle);

        curl_close($curl_handle);

        return json_decode($buffer);
    }


    /**
     * @description Function for calculate order weight if exists
     * 
     * @param object $order
     * @return float
     */

    public function get_order_weight($order) {

        $weight = 0;

        foreach ($order->get_items() as $item_id => $item_product) {

            $product = $item_product->get_product();

            $single_weight = $product->get_weight();

            $quantity = $item_product->get_quantity();

           // $weight += wc_get_weight($single_weight * $quantity, 'g');
            $weight += (float)wc_get_weight((float)$single_weight * $quantity, 'g');
        }

        return ceil($weight);
    }


    /**
     * @description Function for Getting api credentials from Tab settings
     * 
     */

    public function lcs_api_credentials() {
           
            return array(
               'api_key' => $this->api_key,
               'api_password' => $this->api_password,
        );

      }

}