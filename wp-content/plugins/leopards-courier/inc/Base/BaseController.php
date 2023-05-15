<?php
/**
 * 
 * @package  LeopardsCourier
 * 
 */

namespace Inc\Base;
defined('ABSPATH') or die('No direct script access allowed!');

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class BaseController {

    /**
     * @desciption Variable to store plug-in directory path
     * 
     * @var string 
     */
    public $plugin_path;

    /**
     * @desciption Variable to store plug-in directory path
     * 
     * @var string 
     */
    public $plugin_url;

    /**
     * @desciption Variable to store plug-in main file path
     * 
     * @var string 
     */
    public $plugin;

    /**
     * @desciption Variable to store plug-in status i.e. activated or de-activated
     * 
     * @var string 
     */
    public $is_leopards_plugin_active;

    /**
     * 
     * @desciption Constructor function for setting up the default settings
     * 
     */
    public function __construct() {

        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));

        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));

        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/leopards-courier.php';

        $this->is_leopards_plugin_active();
    }

    /**
     * 
     * @desciption function to set up the Plug-in status 
     * 
     */
    public function is_leopards_plugin_active() {

        if (is_plugin_active($this->plugin)) {

            $this->is_leopards_plugin_active = true;
        } else {

            $this->is_leopards_plugin_active = false;
        }
    }

}
