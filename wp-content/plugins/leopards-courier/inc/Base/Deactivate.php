<?php
/**
 * 
 * @package  LeopardsCourier
 * 
 */

namespace Inc\Base;

defined('ABSPATH') or die('No direct script access allowed!');

class Deactivate {

    public static function deactivate() {

        flush_rewrite_rules();
    }

}
