<?php
 /**
 * Plugin Name: 		WC Update all Prices to Excl.VAT
 * Plugin URI: 			https://roadmapstudios.com/
 * Description: 		Update WooCommerce prices to Excl. VAT
 * Author:        Roadmap Studios
 * Author URI:    https://roadmapstudios.com
 * Version:             1.1.0
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 *
 * @category            Prices
 * @package             WC-Update-Prices
 * @copyright           Copyright © 2019 Roadmap Studios
 * @author              Roadmap Studios
 * @license             http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * WC requires at least: 3.3
 * WC tested up to: 3.5.4
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
