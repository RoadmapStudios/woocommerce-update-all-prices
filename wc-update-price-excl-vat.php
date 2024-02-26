<?php
/**
 * Plugin Name:         WC Update all Prices to Excl.VAT
 * Plugin URI:          https://commercebird.com/
 * Description:         Update WooCommerce prices to Excl. VAT
 * Author:              CommerceBird
 * Author URI:          https://commercebird.com
 * Version:             1.0.0
 *
 * License:             GNU General Public License v3.0
 * License URI:         http://www.gnu.org/licenses/gpl-3.0.html
 *
 *
 * @category            Prices
 * @package             WC-Update-Prices
 * @copyright           Copyright © 2024 CommerceBird
 * @author              CommerceBird
 * @license             http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * WC requires at least: 8.0
 * WC tested up to: 8.6.1
 */


add_filter( 'admin_menu', 'wcup_register_my_custom_submenu_page' );
function wcup_register_my_custom_submenu_page() {
	add_submenu_page( 'woocommerce', 'WC Update Prices', 'WC Update Prices', 'manage_options', 'wc-updated-prices-excl-vat', 'wc_update_product_price_form' );
}


function wc_update_product_price_form() {
	include plugin_dir_path( __FILE__ ) . 'includes/wc_update_price_vat-form.php'; //
}
