<?php
/**
 * 
 * @package  LeopardsCourier
 * 
 */

namespace Inc\Api;
 defined('ABSPATH') or die('No direct script access allowed!');

use Inc\Api\LeopardsApi;
use Inc\libraries\WP_List_Table;


 class Leopardtable extends WP_List_Table{


    /**
         * 
         * @description Function for Prepares the list of items for displaying.
         * 
     */


    public function prepare_items() {

        $orderby = isset($_GET['orderby']) ? trim($_GET['orderby']) : "";
        $order = isset($_GET['order']) ? trim($_GET['order']) : "";

        $search_term = isset($_POST['s']) ? trim($_POST['s']) : "";

        $months_dropdown = isset($_POST['m']) ? trim($_POST['m']) : "";
        $data = $this->items = $this->wp_list_table_data($orderby, $order, $search_term, $months_dropdown);
        

        $per_page = 5;
        $current_page = $this->get_pagenum();
        $total_items = count($data);


         $this->items = $data;
         $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' =>$per_page,
            'total_pages' => ceil( $total_items / $per_page)

        ));

        $this->items = array_slice($data,(($current_page-1) * $per_page), $per_page); 


        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();


        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->current_action();
    }


  /**
       * 
       * @description Function for Display the database from Database.
       * @param  $orderby
       * @param  $order
       * @param  $search_term
       * @param  $months_dropdown
       * @return $load_array;
   */


    public function wp_list_table_data($orderby = '', $order = '', $search_term = '', $months_dropdown = '') {

        global $wpdb;

        if (!empty($search_term) && !empty($months_dropdown))
         {

        $all_posts = $wpdb->get_results( "SELECT *  FROM {$wpdb->prefix}leopards_courier_load_sheet WHERE load_sheet_id = '$search_term' AND DATE_FORMAT(DATE(booking_datetime), '%Y%m') = $months_dropdown");
        } 
        else if(!empty($months_dropdown)) 
        {

          $all_posts = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}leopards_courier_load_sheet WHERE DATE_FORMAT(DATE(booking_datetime), '%Y%m') = $months_dropdown");
       
        }
        else if(!empty($search_term)) 
        {
      
       $all_posts = $wpdb->get_results( "SELECT *  FROM {$wpdb->prefix}leopards_courier_load_sheet WHERE load_sheet_id = '$search_term' OR id = '$search_term'");
        
        } 
        else {
          $all_posts = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}leopards_courier_load_sheet");
            
        }


        $load_array = array();

        $all_posts = array_reverse($all_posts);

        if (count($all_posts)) {

            foreach ($all_posts as $index => $loaddata) {
                
                $load_array[] = array(
                    "id" => $loaddata->id,
                    "load_sheet_id" => $loaddata->load_sheet_id,
                    "booking_datetime" => $loaddata->booking_datetime,
                    "total_cn"=>  $loaddata->total_cn,
                    "report"  => sprintf('<input type="button" class="lcs-download-btn button" data-load-id="%s" value="Download PDF">',$loaddata->load_sheet_id)
                 

                );
            }
        }

        return $load_array;
    }
   

    /**
       * 
       * @description Function for Display the Columns in Wp list table Grid.
       * @return $columns;
   */


    public function get_columns() {

              $columns = array(
                  'id'          => 'S.No #',
                  "load_sheet_id" => "load Sheet Id #",
                  "booking_datetime" => "Booking Date Time ",
                  "total_cn" => "Total CN",
                  "report"  => "Download Report"
       );

          return $columns;
    }


    /**
       * 
       * @description Function for Display the Default Columns .
       * @return $column_name
   */


    public function column_default($item, $column_name) {

              switch ($column_name) {

                  case 'id':
                  case 'load_sheet_id':
                  case 'booking_datetime':
                  case 'total_cn':
                  case 'report':
                      return $item[$column_name];
                  default:
                      return "no value";
            }
        }

}