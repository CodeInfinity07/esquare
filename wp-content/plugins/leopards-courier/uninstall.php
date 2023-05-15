<?php

/**
 * @Description Trigger this file on Plug-in un-install
 *
 * @package  LeopardsCourier
 * 
 * @Text Domain: leopards-courier
 */
defined('WP_UNINSTALL_PLUGIN') or die();

global $wpdb;

$table_name = $wpdb->prefix . "leopards_courier";

$sql = "DROP TABLE IF EXISTS $table_name;";

$wpdb->query($sql);

delete_option("my_plugin_db_version");
