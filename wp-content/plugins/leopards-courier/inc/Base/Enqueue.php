<?php
/**
 * 
 * @package  LeopardsCourier
 * 
 */

namespace Inc\Base;

defined('ABSPATH') or die('No direct script access allowed!');

use Inc\Base\BaseController;

class Enqueue extends BaseController {

    public function register() {

        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue'));
    }

    /**
     * 
     * @description Function for adding all the required style scripts and javascript files
     * 
     */
    function enqueue() {

        wp_register_script('leopards-courier-js', $this->plugin_url . 'assets/js/script.js');

  
        wp_register_style('leopards-courier-css', $this->plugin_url . 'assets/css/style.css');

        wp_localize_script('leopards-courier-js', 'leopards_courier_vars', array('pluginurl' => $this->plugin_url));

        wp_enqueue_style('leopards-courier-css');

        wp_enqueue_script('leopards-courier-js');
    }

    function frontend_enqueue() {


         wp_register_script('leopards-courier-uijs', $this->plugin_url . 'assets/js/lcs-ui.js','','1.2.8',true);
        wp_register_style('leopards-courier-ui-css', $this->plugin_url . 'assets/css/lcs-front.css','','1.2.8');

         wp_localize_script('leopards-courier-uijs', 'leopards_courier_vars', array(
            'pluginurl' => $this->plugin_url,
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce('ajax_nonce')
        ));

         wp_enqueue_script('leopards-courier-uijs');
          wp_enqueue_style('leopards-courier-ui-css');
    }


}
