<?php

//include( plugin_dir_path( __FILE__ ) . 'callcourier.php');
//include( plugin_dir_path( __FILE__ ) . 'options.php');

$cc_api = New CallCourierApi();

// Form Updated Data
if (isset($_POST['customForm']) && $_POST['customForm'] == 'submit'){
    $postArray = $_POST['rows'];    
    $orderData = array();

    $flashMessage = '';
    foreach ($postArray as $key => $item){
        $postArray[$item['order_id']]['serviceId'] = $_POST['serviceId'];
        $orderData = array(
            'consigneeName'=>$item['consigneeName'],
            'servicesId'=>$_POST['serviceId'],
            'consigneeCellNo'=>$item['consigneeCellNo'],
            'order_amount'=>$item['order_amount'],
            'city'=>$item['city'],
            'order_address'=>$item['order_address'],
            'remarks'=>$item['order_remarks']
        );

        //return array('CNNO'=>$data->CNNO, 'result'='success');
                
        $response = $cc_api->createshipment($item['order_id'],$orderData);
        

        if($response['result'] == 'success'){
            $flashMessage .= 'Order#'.$item['order_id'].' is booked, CNNO= <a class="cn-book-link" target="_blank" href="http://cod.callcourier.com.pk/Booking/AfterSavePublic/'.$response['CNNO'].'">'.$response['CNNO'].'</a><br />';
        }else{
            $flashMessage .= 'Order#'.$item['order_id'].' is not booked--'.$response['result'].'<br />';
        }
    }

    //exit();
    //echo "<pre>".print_r($orderData,1).'</pre>';

}else{

    $wc_order_page_url = site_url().'/wp-admin/edit.php?post_type=shop_order';
    
    if(!isset($_GET['cc_ids']) or $_GET['cc_ids'] == ''){
        header('Location: '.$wc_order_page_url);
        exit();
        //wp-admin/edit.php?post_type=shop_order
    }

    $query_cc_ids = $_GET['cc_ids'];
    $post_ids = explode(',',$_GET['cc_ids']);

    $selectedService = isset($_GET['s']) && $_GET['s'] != '' ? $_GET['s'] : 7;

    $allCities = $cc_api->getCitiesListByService($selectedService); //load COD cities by default

    //will populate all orders by ids
    $orders = array();
    $orderData = array();

    if(!empty($post_ids)){
        foreach ( $post_ids as $post_id ) {
            $order = wc_get_order( $post_id );

            $orderData[$post_id]['id'] = $order->get_id();
            $orderData[$post_id]['consigneeName'] = $order->data['shipping']['first_name'].' '.$order->data['shipping']['last_name'];
            $orderData[$post_id]['consigneeCellNo'] = $order->data['billing']['phone'];
            $orderData[$post_id]['amount'] = $order->data['total'];
            $orderData[$post_id]['address'] = $order->data['shipping']['address_1'].' '.$order->data['shipping']['address_2'];
            $orderData[$post_id]['city'] = $order->data['shipping']['city'];
        }
    }

    $optionData = '';

    // Services Data
    if($cc_api){
        
        $settings = (array) get_option( 'callcourier-plugin-settings' );

        
        foreach ($cc_api->serviceType($settings['account_number']) as $item ) {
            $isToSelectServiceOption = '';
            if(($selectedService == $item->ServiceTypeID)){
                $isToSelectServiceOption = 'selected';
            }

            $optionData.='<option  value="'.strtolower($item->ServiceTypeID).'" '.$isToSelectServiceOption.' >'.$item->ServiceType1.'</option>';
        }
    }
    $htmlData = 'Service type: <select id="order_services" name="serviceId" onchange="onServiceSelect()" ><option value="">Select Service</option>'.$optionData.'</select>';
}

//echo '<pre>'.print_r($orderData,1).'</pre>';
?>
<style>
    .cn-book-link{
        color: white;
        text-decoration: underline;
    }
    .services_order{
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .order_form th{
        padding-bottom: 5px;
        padding-left: 5px;
    }
    #order_form td {
        padding-bottom: 5px;
    }
    #order_form input, #order_form select, #order_form textarea{
        border: 2px solid #ccc;
        border-radius: 6px;
        height: 45px;
    }
    #order_submit {
        float: right;
        margin: 20px 0;
        align-items: center;
        background-color: initial;
        background-image: linear-gradient(#464d55, #25292e);
        border-radius: 8px;
        border-width: 0;
        box-shadow: 0 10px 20px rgba(0, 0, 0, .1),0 3px 6px rgba(0, 0, 0, .05);
        box-sizing: border-box;
        color: #fff;
        cursor: pointer;
        display: inline-flex;
        flex-direction: column;
        font-size: 18px;
        height: 52px;
        justify-content: center;
        line-height: 1;
        outline: none;
        overflow: hidden;
        padding: 0 32px;
        text-align: center;
        text-decoration: none;
        transition: all 150ms;
        vertical-align: baseline;
        white-space: nowrap;
    }
    #order_submit:hover { box-shadow: rgba(0, 1, 0, .2) 0 2px 8px; opacity: .85; }
    #order_submit:active { outline: 0; }
    #order_submit:focus { box-shadow: rgba(0, 0, 0, .5) 0 0 0 3px; }
    @media (max-width: 420px) { #order_submit { height: 48px; } }
</style>
<div class="wrap">

    
    <?php 
        if($flashMessage != ''){
            echo '<div style="padding:10px; background-color:green;color:white;">';
            echo $flashMessage;
            echo '<a href="'.$wc_order_page_url.'" style="color:white;">Click here</a> to book more orders';
            echo '</div>';
            exit();

        }
        
    ?>

    <form class="order_form_submit" method="post" onsubmit="return validateForm()" action="<?php echo get_site_url();?>/wp-admin/options-general.php?page=callcourier-plugin-booking">

        <div class="services_order">
            <h2><?php _e('CallCourier Booking', 'textdomain'); ?></h2>
            <span><?php echo $htmlData; ?></span>
        </div>

        <input type="hidden" name="customForm" value="submit">
        <table class="table table-striped table-bordered order_form">
            <thead>
            <tr align="left">
                <th>Order Id</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Amount</th>
                <th>City</th>
                <th>Address</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            <p id="error_message"></p>
            <?php foreach($orderData as $key => $row){?>
                <tr id="order_form">
                    <td><input id="order_id" style="width: 60px;" type="text" name="rows[<?php echo $row['id'];?>][order_id]" value="<?php echo $row['id'];?>" readonly></td>
                    <td><input id="consigneeName" name="rows[<?php echo $row['id'];?>][consigneeName]" type="text" value="<?php echo $row['consigneeName'];?>"></td>
                    <td><input id="consigneeCellNo" name="rows[<?php echo $row['id'];?>][consigneeCellNo]" type="text" value="<?php echo $row['consigneeCellNo'];?>"></td>
                    <td><input id="order_amount" name="rows[<?php echo $row['id'];?>][order_amount]" type="text" value="<?php echo $row['amount'];?>"></td>
                    <td><select id="order_city" name="rows[<?php echo $row['id'];?>][city]" style="width: 110px;">
                            <option >Select City</option>
                            <?php foreach($allCities as $city){?>
                                <option <?php echo strtolower($city['CityName']) == strtolower($row['city']) ? 'selected' : ''; ?>  value="<?php echo $city['CityID'];?>" ><?php echo $city['CityName'];?></option>
                            <?php }?>
                        </select>
                    </td>
                    <td><textarea id="order_address" name="rows[<?php echo $row['id'];?>][order_address]" style="resize: none;" value="<?php echo $row['address'];?>" rows="2"><?php echo $row['address'];?></textarea></td>
                    <td><textarea id="order_remarks" name="rows[<?php echo $row['id'];?>][order_remarks]" style="resize: none;" value="<?php echo $row['remarks'];?>" rows="2"><?php echo $row['remarks'];?></textarea></td>
                </tr>
            <?php } ?>
            </tbody>

        </table>
        <input type="hidden" id="query_cc_ids" value="<?php echo $query_cc_ids;?>"  />       
        <button id="order_submit" type="submit" onClick="return empty()">Submit</button>
    </form>
</div>

<script type="text/javascript">

    function onServiceSelect(){
        order_services = document.getElementById("order_services").value ;
        query_cc_ids = document.getElementById("query_cc_ids").value ;
        //query_cc_ids

        window.location.href = "<?php echo site_url();?>/wp-admin/options-general.php?page=callcourier-plugin-booking&s="+order_services+"&cc_ids="+query_cc_ids;
        console.log(order_services);
    }

    function empty() {
        consigneeName = document.getElementById("consigneeName").value ;
        consigneeCellNo = document.getElementById("consigneeCellNo").value ;
        order_amount = document.getElementById("order_amount").value ;
        order_address = document.getElementById("order_address").value ;
        order_services = document.getElementById("order_services").value ;

        if(!order_services) {
            document.getElementById('error_message').innerHTML="Please select a services first.";
            return false;
        };
        if(!consigneeName) {
            document.getElementById('error_message').innerHTML="Please enter a value";
            return false;
        };
        if( !consigneeCellNo) {
            document.getElementById('error_message').innerHTML="Please enter a value";
            return false;
        };
        if(!order_amount ) {
            document.getElementById('error_message').innerHTML="Please enter a value";
            return false;
        };
        if(!order_address ) {
            document.getElementById('error_message').innerHTML="Please enter a value";
            return false;
        };
    }
</script>