<?php
class CallCourierApi{

    const API_URL = "https://cod.callcourier.com.pk/API/CallCourier/";
    const CALL_COURIER_LOG_FILE_NAME = "call_courier.log";
    const SEL_ORIGIN = 'Domestic';
    const MY_BOX_ID = 3;
    const HOLIDAY = 'false';
    const SPECIAL_HANDLING = 'false';

    protected function doRequest($method, $data = null)
    {
        $url = self::API_URL . $method;

        $queryStr = null;
        if ($data != null) {
            $queryStr = http_build_query($data);
        }
        $url = ($queryStr != null) ? $url . "?" . $queryStr : $url;
        
        /*if($method == 'SaveBooking'){
            echo $url;
        }*/
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curlResponse = curl_exec($ch);
        if (curl_error($ch)) {
            //Mage::log('error:' . curl_error($ch), null, self::CALL_COURIER_LOG_FILE_NAME);
        }
        curl_close($ch);
        return $curlResponse;
    }


    public function getCitiesListByService($serviceId=0){
        //https://cod.callcourier.com.pk/API/CallCourier/GetCityListByService?serviceID=26
        //get cities against serviceId like COD
        if($serviceId){
            $response = $this->doRequest("GetCityListByService?serviceID=".$serviceId);            
        }else{
            return false;
        }
        return (array) json_decode($response,1);        
    }

    public function getAllCitiesListApi()
    {
        $method = "GetCityList";
        $response = $this->doRequest($method);
        $cityArray = (array)json_decode($response,1);

        //die($cityArray['CityName']);
        //print_array($cityArray[0]['CityName']);
        $cities = array();

        foreach ($cityArray as $city){
            //print_array($city['CityName']);
          
            $cities[$city['CityID']] = $city['CityName'];
            // if( strtoupper($cityName) == $city['CityName']){
            //   return $city['CityID'];
            // }
        }
        return $cities;
    }

    public function getCitiesListApi($cityName)
    {
        $method = "GetCityList";
        $response = $this->doRequest($method);
        $cityArray = (array)json_decode($response,1);

        //die($cityArray['CityName']);
        //print_array($cityArray[0]['CityName']);
        foreach ($cityArray as $city){
            //print_array($city['CityName']);
          if( strtoupper($cityName) == $city['CityName']){
              return $city['CityID'];
          }
        }
        //return (array)json_decode($response);
    }

    public function arealist($cityId)
    {
        //$cityId = $this->request->post['id'];
        $method = "GetAreasByCity";
        $data['CityID'] = $cityId;
        $areaList = $this->doRequest($method, $data);
        $areaList = json_decode($areaList);
        $options = array();
        foreach ($areaList as $area) {
            $options[$area->AreaID] = $area->AreaName;
        }
        $this->response->setOutput(json_encode($options));
    }

    public function serviceType($accountId)
    {
        $method = "GetServiceType/".$accountId;
        $response = $this->doRequest($method);
        return (array)json_decode($response);
    }

    public function trackingSummery()
    {
        $order_id = (int)$this->request->get['order_id'];
        if ($order_id == 0) {
            return array();
        }
        $this->load->model('extension/module/callcourier');
        $cn_number = $this->model_extension_module_callcourier->getTrackingByOrderId($order_id);

        if ($cn_number == null) {
            $return = array();
        } else {
            $data['cn'] = $cn_number;
            $curlResponse = $this->doRequest('GetTackingHistory', $data);
            $return = json_decode($curlResponse);
        }
        return ($return==null)?array():array_reverse($return);
    }

    public function createshipment($order_id, $orderData = array() )
    {
        //example api call
        /*http://cod.callcourier.com.pk/api/CallCourier/SaveBooking?
        loginId=test-0001&
        ConsigneeName=Sabeeh&
        ConsigneeRefNo=5627087636&
        ConsigneeCellNo=03004344328&
        Address=Sher+Shah+Block+New+Garden&
        Origin=Lahore&
        DestCityId=18&
        ServiceTypeId=7&
        Pcs=01&
        Weight=01&
        Description=Test%20Description&
        SelOrigin=Domestic&
        CodAmount=1&
        SpecialHandling=false&
        MyBoxId=1%20My%20Box%20ID&
        Holiday=false&
        remarks=Test%20Remarks&
        ShipperName=ShipperName&
        ShipperCellNo=03004344328&
        ShipperArea=1&
        ShipperCity=1&
        ShipperAddress=kks&
        ShipperLandLineNo=34544343&
        ShipperEmail=sabeeh@gmail.com&
        currency=USD&
        financial_status=paid
        */

        $order = wc_get_order( $order_id );
        $curlData = array();

        $settings = (array) get_option( 'callcourier-plugin-settings' );

        //if payment method COD,CODZ,OverLand CODZ then serviceType=7,65,68 otherwise for all other payment Types serviceType=1 (Overnight and CODE amount must be 0)
        if($orderData['servicesId'] == 7 || $orderData['servicesId'] == 65 || $orderData['servicesId'] == 68  || $orderData['servicesId'] == 25){
            $total_amount = $orderData['order_amount'];
        }else{
            $total_amount = 0;
        }

        /*
            'consigneeName'=>$item['consigneeName'],
            'servicesId'=>$item['services'],
            'consigneeCellNo'=>$item['consigneeCellNo'],
            'order_amount'=>$item['order_amount'],
            'city'=>$item['city'],
            'order_address'=>$item['order_address'],
            'remarks'=>$item['order_remarks']        
        */

        $curlData['ConsigneeName'] = $orderData['consigneeName'];
        $curlData['ServiceTypeId'] = $orderData['servicesId'];
        $curlData['ConsigneeCellNo'] = $orderData['consigneeCellNo'];
        $curlData['CodAmount'] = $total_amount;
        $curlData['DestCityId'] = $orderData['city'];
        $curlData['Address'] = $orderData['order_address'];
        $curlData['remarks'] = $orderData['remarks'];

//        $curlData['ConsigneeName'] = $order->data['shipping']['first_name'].' '.$order->data['shipping']['last_name'];
//        $curlData['ConsigneeCellNo'] = $order->data['billing']['phone'];
//        $curlData['DestCityId'] = $order->data['shipping']['city'];
//        $curlData['ServiceTypeId'] = $ServiceTypeId;
//        $curlData['Address'] = $order->data['shipping']['address_1'].' '.$order->data['shipping']['address_2'];
//        $curlData['ConsigneeRefNo'] = $order_id;
//        $curlData['remarks'] = 'Remarks';


        //shipper detail
        $curlData['ConsigneeRefNo'] = $order_id;
        $curlData['loginId'] = $settings['login_id'];
        $curlData['Origin'] = $settings['shipper_origin'];
        $curlData['ShipperName'] = $settings['shipper_name'];
        $curlData['ShipperArea'] = $settings['shipper_area'];
        $curlData['ShipperCity'] = $settings['shipper_city'];
        $curlData['ShipperLandLineNo'] = $settings['shipper_land_line_no'];
        $curlData['ShipperCellNo'] = $settings['shipper_cell_no'];
        $curlData['ShipperEmail'] = $settings['shipper_email'];
        $curlData['ShipperAddress'] = $settings['shipper_address'];
        //order data
        //$curlData['Address'] = $order->data['shipping']['address_1'].' '.$order->data['shipping']['address_2'];
        //$curlData['ConsigneeRefNo'] = $order_id;
        //$curlData['ConsigneeName'] = $order->data['shipping']['first_name'].' '.$order->data['shipping']['last_name'];
        //$curlData['ConsigneeCellNo'] = $order->data['billing']['phone'];
        //$curlData['DestCityId'] = $this->getCitiesListApi($order->data['shipping']['city']);
        //$curlData['ServiceTypeId'] = $ServiceTypeId;
        $curlData['Pcs'] = 01;
        $curlData['Weight'] = 01;
        

        //echo '<pre>'.print_r($curlData,1).'</pre>';
        //exit();
        //populate product description
        $order_items = $order->get_items();
        //echo '<pre>'.print_r($order_items,1).'</pre>';
        
        $product_description = '';
        foreach ($order_items as $item_id => $item_data) {
            $product_name = $item_data['name'];
            $product_quantity = $item_data['quantity'];
            
            $product = new WC_Product($item_data['product_id']);            
            $product_sku = $product->get_sku();
            
            $product_description .=  $product_quantity.' x '.$product_name.",SKU: $product_sku, ";
        }

         //echo $product_description;
         //die();
        
        $curlData['Description'] = $product_description;
        $curlData['SelOrigin'] = self::SEL_ORIGIN;
        $curlData['SpecialHandling'] = self::SPECIAL_HANDLING;
        $curlData['MyBoxId'] = self::MY_BOX_ID;
        $curlData['Holiday'] = self::HOLIDAY;

        $response = $this->doRequest('SaveBooking', $curlData);
        $data = json_decode($response);

        //echo '<pre>'.print_r($curlData,1).'</pre>';
        //echo '<pre>'.print_r($data,1).'</pre>';
        //exit();

        if ($data->Response == 'true') {

            //$saveData['cn_number'] = $data->CNNO;
            //$saveData['order_id'] = $curlData['ConsigneeRefNo'];
            //$newURL = "http://cod.callcourier.com.pk/Booking/AfterSavePublic/" . $data->CNNO;
            //update cnid custom field with generated cnid
            update_post_meta( $order_id, 'cc_cnid', $data->CNNO );

            return array('CNNO'=>$data->CNNO, 'result'=>'success');
        } else {
            return array('CNNO'=>0, 'result'=>$data->Response);
        }
    }
}

return new CallCourierApi();