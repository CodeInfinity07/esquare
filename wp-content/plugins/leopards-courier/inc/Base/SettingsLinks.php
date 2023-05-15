<?php
/**
 * 
 * @package  LeopardsCourier
 * 
 */

namespace Inc\Base;
defined('ABSPATH') or die('No direct script access allowed!');

use Inc\Base\BaseController;

class SettingsLinks extends BaseController {

    public function register() {

        if ($this->is_leopards_plugin_active) {

            add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));
        }
    }

    /**
     * @description Function for setting up the settings tab URL
     * 
     * @param string $links
     * 
     * @return array
     */
    public function settings_link($links) {

        $settings_link = '<a href="admin.php?page=wc-settings&tab=leopards-courier-settings">Settings</a>';

        array_push($links, $settings_link);

        return $links;
    }

}
