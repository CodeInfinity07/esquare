<?php





/**

 * @package  LeopardsCourier

 * Plugin Name: Leopards Courier add-on for WooCommerce

 * Plugin URI: http://new.leopardscod.com/extensions

 * Description: This plug-in is to provide LCS COD services within the WordPress Plug-in, in order to perform multiple actions like CREATE and TRACK shipments.

 * Version: 1.3.0

 * Author: Team MatechCo

 * Author URI: https://www.matechco.com

 * License: GPLv2 or later

 * Text Domain: leopards-courier

 */

/**

  This program is free software; you can redistribute it and/or

  modify it under the terms of the GNU General Public License

  as published by the Free Software Foundation; either version 2

  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,

  but WITHOUT ANY WARRANTY; without even the implied warranty of

  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License

  along with this program; if not, write to the Free Software

  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

  Copyright 2005-2015 Automatic, Inc.

 */

// If this file is called directly, abort!!!

defined('ABSPATH') or die('No direct script access allowed!');



// Require once the Composer Autoload

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {

    require_once dirname(__FILE__) . '/vendor/autoload.php';

}

if(!class_exists('WP_List_Table')){

    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

}



/**

 * 

 * @description Function to be executed on plug-in activation

 * 

 */

function activate_leopards_plugin() {

    Inc\Base\Activate::activate();

}



register_activation_hook(__FILE__, 'activate_leopards_plugin');



/**

 * 

 * @description Function to be executed on plug-in de-activation

 * 

 */

function deactivate_leopards_plugin() {

    Inc\Base\Deactivate::deactivate();

}



register_deactivation_hook(__FILE__, 'deactivate_leopards_plugin');



/**

 * 

 * @description Initializing all core classes of the plug-in

 * 

 */

if (class_exists('Inc\\Init')) {

    Inc\Init::register_services();

}