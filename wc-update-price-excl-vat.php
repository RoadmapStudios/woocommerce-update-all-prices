<?php
 /**
 * Plugin Name: 		WC Update Prices to Excl.VAT
 * Plugin URI: 			https://roadmapstudios.com/
 * Description: 		Update WooCommerce prices to Excl. VAT
 * Author:              Roadmap Studios
 * Author URI:          https://roadmapstudios.com
 *
 * Version:             1.0.0
 * Requires at least:   4.9.0
 * Tested up to:        5.0.2
 *
 *
 * @category            Prices
 * @copyright           Copyright © 2018 Roadmap Studios
 * @author              Roadmap Studios
 * @package             WooCommerce
 */


add_filter('admin_menu', 'register_my_custom_submenu_page');

if (!function_exists('register_my_custom_submenu_page')) {
    function register_my_custom_submenu_page()
    {
        add_submenu_page( 'woocommerce', "WC Update Prices", "WC Update Prices", "manage_options", "wc-updated-prices-excl-vat", "wc_update_product_price_form" );
    }
}

if (!function_exists('wc_update_product_price_form')) {
    function wc_update_product_price_form()
    {
        include plugin_dir_path(__FILE__) . 'includes/wc_update_price_vat-form.php'; //
    }
}
