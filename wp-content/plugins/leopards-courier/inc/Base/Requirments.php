<?php
/**
 * 
 * @package  LeopardsCourier
 * 
 */

namespace Inc\Base;
defined('ABSPATH') or die('No direct script access allowed!');

use Inc\Base\BaseController;

class Requirments extends BaseController {

    public function register() {

        add_action('admin_init', array($this, 'check_woocommerce'));
    }

    /**
     * 
     * @description Getter function for WooCommerce plug-in activation status
     * 
     * @return boolean
     * 
     */
    public static function is_woocommerce_active() {

        if (class_exists('woocommerce')) {

            return true;
        } else {

            return false;
        }
    }

    /**
     * 
     * @description Function for checking WooCommerce plug-in activation status
     * 
     */
    public function check_woocommerce() {

        if ($this->is_woocommerce_active()) {

            flush_rewrite_rules();
        } else {

            if (is_plugin_active($this->plugin)) {

                deactivate_plugins($this->plugin);
            }

            add_action('admin_notices', array($this, 'woocommerce_admin_notice__error'));
        }
    }

    /**
     * 
     * @description Function for showing Admin notices when WooCommerce is not activated
     * 
     */
    public function woocommerce_admin_notice__error() {

        $class = 'notice notice-error';

        $message = __("Woocommerce Plug-in should be activated first to activate the 'Leopards Courier add-on for WooCommerce'", 'leopards-courier');

        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }

}
