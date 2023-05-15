<?php
/**
 * @package  LeopardsCourier
 */
namespace Inc\Base;
use Inc\Base\Requirments;


class Activate
{
    public static function activate() {
        if ( Requirments::is_woocommerce_active() ) { 
            
            global $wpdb;
            $table_name = $wpdb->prefix .'leopards_courier'; 
            $table_sheetname = $wpdb->prefix .'leopards_courier_sheet';
            $table_load_sheet = $wpdb->prefix .'leopards_courier_load_sheet';

            $wpdb_collate = $wpdb->collate;
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            
            $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
                id int(11) NOT NULL AUTO_INCREMENT,
                store_key VARCHAR(200) NOT NULL,
                store_value LONGTEXT NOT NULL,
                PRIMARY KEY  (id)
                );
                COLLATE {$wpdb_collate}";
            $result = dbDelta($sql);

            $sql = "CREATE TABLE IF NOT EXISTS {$table_sheetname} (
                id int(11) NOT NULL AUTO_INCREMENT,
                track_no VARCHAR(200) NOT NULL,
                user_id int(11) NOT NULL,
                order_id int(20) NOT NULL,
                booking_datetime VARCHAR(200) NOT NULL,
                is_sheet int(11) NOT NULL,
                is_cancel int(11) NOT NULL,
                slip_url TEXT NOT NULL,
                PRIMARY KEY  (id)
                ); COLLATE {$wpdb_collate}";  
            $result = dbDelta($sql);

            $sql = "CREATE TABLE IF NOT EXISTS {$table_load_sheet} (
                id int(11) NOT NULL AUTO_INCREMENT,
                track_no VARCHAR(200) NOT NULL,
                load_sheet_id int(11) NOT NULL,
                booking_datetime VARCHAR(200) NOT NULL,
                total_cn int(20) NOT NULL,
                courier_name VARCHAR(200) NOT NULL,
                Courier_code VARCHAR(200) NOT NULL,
                PRIMARY KEY  (id)
                ); COLLATE {$wpdb_collate}";  
            $result = dbDelta($sql);

            flush_rewrite_rules();
        } else {
            wp_die('Woocommerce Should Install First to Activate Plugin "Leopards Courier" ', 'leopards-courier');
        }
    }
}