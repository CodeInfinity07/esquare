<?php

/**

 * 

 * @package  LeopardsCourier

 * 

 */



namespace Inc\Api;



defined('ABSPATH') or die('No direct script access allowed!');



use Inc\Api\LeopardsApi;

use Inc\Api\Leopardtable;

        

   class Leopardsheet  {

 



    public function __construct() {



        add_action('admin_menu', array($this, 'theme_options_panel'));

            

        add_action('wp_ajax_leopards_lcs_create_sheet', array($this, 'leopards_lcs_create_sheet'));



        add_action('wp_ajax_lcs_download_pdf', array($this, 'lcs_download_pdf'));



        add_action('wp_ajax_lcs_tracking_number_load_sheet', array($this, 'lcs_trackingno_loadsheet'));



   

}

  



   /**

         * 

         * @description Function for LCS options in Wordpress Admin

         * 

     */



        function theme_options_panel(){



          add_menu_page("Manage Load Sheets", "LCS", "manage_options", "lcs_download_sheet", array($this, 'lcs_download_sheet'),'dashicons-groups',26);

}



  

   /**

       * 

       * @description Function for creating load sheet ID

       * @param object lcs_api_credentials;

       * @param object LeopardsApi;

   */





    public function leopards_lcs_create_sheet(){



            global $wpdb;

       

            date_default_timezone_set("Asia/Karachi");



            $track_id = $_REQUEST['leopards_track_id'];

            $track_id_array =  explode(',', $track_id);



          

            $courier_code= $_REQUEST['leopards_courier_code'];

            $courier_name= $_REQUEST['leopards_courier_name'];



            $sheet_array = array(

            'cn_numbers'    => explode(',',$track_id),                     

            'courier_name'  => $courier_name,

            'courier_code'  => $courier_code

            );

           

          

            $newcurl  =  new LeopardsApi();



            $credential_array =  $newcurl->lcs_api_credentials();

 

            $curl = curl_init();



              curl_setopt_array($curl, array(

              CURLOPT_URL => "http://new.leopardscod.com/webservice/generateLoadSheet/format/json/",

              CURLOPT_RETURNTRANSFER => true,

              CURLOPT_ENCODING => "",

              CURLOPT_MAXREDIRS => 10,

              CURLOPT_TIMEOUT => 30,

              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

              CURLOPT_CUSTOMREQUEST => "POST",

              CURLOPT_POSTFIELDS => json_encode( $credential_array + $sheet_array),

              CURLOPT_HTTPHEADER => array(

                "Content-Type: application/json",

                "Postman-Token: d1ee281d-15a1-4aa2-a321-82f364a917c8",

                "cache-control: no-cache"

           ),

    ));



                $response = curl_exec($curl);

                $err = curl_error($curl);



                curl_close($curl);



                if ($err) {

                  echo "cURL Error #:" . $err;

       } 

          else

       {



            $curl_response = json_decode($response);

    }



               $status = array(

                  'success' => 0,

                  'message' => ''

          );





        if (!empty((array) $curl_response)) {



            if ($curl_response->status == 1) {

        

                  $load_sheet_ids = $curl_response->load_sheet_id;



                  $query_insert_sheet = $wpdb->insert($wpdb->prefix . 'leopards_courier_load_sheet', array(

                    'track_no' => $track_id,

                    'load_sheet_id' => $load_sheet_ids,

                    'booking_datetime' => date("Y-m-d h:i:sa"),

                    'courier_name' => $courier_name,

                    'courier_code' => $courier_code,

                    'total_cn' => count($track_id_array)

                )); 

                 

             

                  foreach ($track_id_array as $trackkey => $track_id_value) {

                  

                    $wpdb->update( $wpdb->prefix . 'leopards_courier_sheet',

                    array('is_sheet' => 1 ), array('track_no' => trim($track_id_value)));



               }

                   



                $status['success'] = 1;

                $status['message'] = 'Load sheet '  .$load_sheet_ids. ' generated successfully';

                 



            } else if ($curl_response->status == 0) {



                $message = '';

                foreach($curl_response->error as $key => $error){
                    $message .= ' {'.$key.'--'.$error.'} ';
                    $wpdb->update( $wpdb->prefix . 'leopards_courier_sheet',

                        array('is_sheet' => 1 ), array('track_no' => trim($key)));

                }

                $status['message'] = $message.'  Refresh Page and try again!    ';


            } else {



                $status['message'] = 'Unable to generate id contact leopards courier support';

            }

        } else {



            $status['message'] = 'Unable to generate id contact leopards courier support';

        }

        echo json_encode($status);



    die();

}





     /**

         * 

         * @description Function for showing  load sheet data from database.

         * @global  $wpdb

         * 

     */





    public function lcs_download_sheet() {



      global $wpdb;



      echo '<div class="wrap lcs-disableds"><h3 class="load-sheet-heading">Leopard Load Sheet</h3>

      <p class="generte-btn"><input  name="lcs-btn" type="button" value="Generate Load Sheet" class="lcs_load_sheet_btn button button-primary"></p>';





        $query_dload_sheet = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}leopards_courier_load_sheet");



        $query_dload_sheet = array_reverse($query_dload_sheet);



        if(empty($query_dload_sheet)){



           echo "<h4 class='recmsg'>No Records Found</h4>";

    }

   

      else

   {

 

         $create_table = new Leopardtable();



         $create_table->prepare_items();

 

            echo "<div class='wrap'><form method='post' value='Search IDs' name='lcs_search_post' action='" . $_SERVER['PHP_SELF'] . "?page=lcs_download_sheet'>";

            $create_table->search_box("Search", "search_post_id");



            $available_dates = $wpdb->get_results( "SELECT DATE_FORMAT(DATE(booking_datetime), '%Y%m') AS date_month , DATE_FORMAT(DATE(booking_datetime), '%M %Y') AS date_text FROM {$wpdb->prefix}leopards_courier_load_sheet GROUP by date_month ORDER BY booking_datetime DESC");



            echo '<select name="m" id="filter-by-date">';

            echo '<option value="0">All dates</option>';

                if(!empty($available_dates)){

                  foreach ($available_dates as $key => $date) {

                    echo '<option value="'.$date->date_month.'" '.($date->date_month == $_POST['m'] ? 'selected' : '' ).'>'.$date->date_text.'</option>';

                  }

                }

            echo '</select>';

            echo '<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Filter">';





            echo "</form></div>";

            $create_table->display();



    }



            echo '<div id="leopards-courier-modal-load-sheet" class="leopards-courier-modal">

                  <div class="modal-content lcs-modals">

                  <div class="lcs-modal-header">

                    <h4 class="lcs-modal-title">Active Tracking Numbers</h4>

                      <span class="close">&times;</span>

                  </div>';



            echo '<div class="lcs_main_loadsheet_table"></div>';   

            echo '</div>



    </div>';



}

 



    /**

         * 

         * @description Function for generatng download sheet  API  (PDF)

         * @param object lcs_api_credentials;

     */





     public function lcs_download_pdf(){

  



        $load_sheet_id =  $_REQUEST['leopards_load_sheet_id'];



        $newcurl  =  new LeopardsApi();



        $credential_array =  $newcurl->lcs_api_credentials();



        $response_a = array(

            'success' => 0,

            'message' => '',

            'data'  => null

        );



        $response = wp_remote_post( $newcurl->api_url.'downloadLoadSheet/', array(

            'method'      => 'POST',

            'timeout'     => 45,

            'redirection' => 5,

            'httpversion' => '1.0',

            'blocking'    => true,

            'headers'     => array( 'Content-Type' => 'application/json'),

            'body'        => json_encode( $credential_array + 

                              array(

                                'load_sheet_id' =>  $load_sheet_id,                          

                                'response_type' => 'PDF'

          )

        ),

              'cookies'     => array()

      )

  );

         

            if ( is_wp_error( $response ) ) {

                  $response_a['message'] = $response->get_error_message();  

            } else {



                $filename = "loadsheet_".$load_sheet_id.".pdf";

                $upload = wp_upload_bits($filename, null, $response['body']);



                if($upload['error'] != ''){

                  $response_a['message'] = $upload['error'];

                }else{

                  $response_a['success'] = 1;

                  $response_a['message'] = "successfully";

                  $response_a['data'] = $upload;

            }

        }

               

            echo json_encode($response_a);



        die;         

}





    /**

         * 

         * @description Function for Display the data after Load Sheet ID.

         * @global  $wpdb

         * 

     */





    public function lcs_trackingno_loadsheet(){



       global $wpdb;



       $query_load_sheet = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}leopards_courier_sheet WHERE is_sheet= 0 AND is_cancel = 0",ARRAY_A );



       if(!empty($query_load_sheet)) {

                

                echo '<div class="lcs-modal-inner-content">

                    

                 <ul class="active-track-no">';



                echo  '<li><span>Courier Name:</span><input id="courier_name" name="courier_name" type="text" placeholder="Courier Name" class=""></li>

                  <li><span>Courier Code:</span><input id="courier_code" name="courier_code" type="text" placeholder="Courier Code" class=""></li>

                  </ul>';



           echo   '<table class="scroll wp-list-table striped posts paginated lcs-load-table widefat fixed load-sheet-panel">

                    <thead>

                    <tr>      

                    <th>Track No</th> 

                    <th>Order id</th>

                    <th>Booking Date Time</th>

                    <th>Sheet Status</th>

                    <th>Cancel Status</th>

                    </tr>

                    </thead><tbody>';





      foreach ($query_load_sheet as $key => $query_response) {

        

           echo '<tr>';



              echo'<td scope="row" class="check-column"><input type="checkbox" name="track-id" class="track-id" value="'.$query_response['track_no'].'">

                  '.$query_response['track_no'].'</td>

              <td>'.$query_response['order_id'].'</td>

              <td>'.$query_response['booking_datetime'].'</td>

              <td>'.$query_response['is_sheet'].'</td>

              <td>'.$query_response['is_cancel'].'</td>

            

         </tr>';

     }



        echo '

        </tbody>

        </table>';



              echo '<div class="lcs-submit-btn">

              <input class="button button-primary" type="button" value="Submit" id="lcs_submit_load_sheet_btn"></div>

        </div>



                <div class="error-show idmsg"></div>

        </div>';

     } 



           else 

                {

                  echo "<h4 class='recmsg'>No Active Track Numbers Found</h4>";

             }

                echo '</div>

      </div>';





      die();

 

   }

}