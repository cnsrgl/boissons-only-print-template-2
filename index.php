<?php
/**
 * Plugin Name: Boissons Only Print Template for BizPrint
 * Plugin URI: http://www.bizswoop.com/wp/print
 * Description: Custom Boissons Only Template for Printing Only Beverage Category Products with BizPrint Cloud Print
 * Version: 1.0.0
 * WC requires at least: 2.4.0
 * WC tested up to: 8.9.3
 * Author: Your Company Name
 * Author URI: http://www.yourcompanywebsite.com
 */


// Text domain yükleme
function boissons_print_load_textdomain() {
	load_plugin_textdomain('boissons-print-template', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'boissons_print_load_textdomain');

// Template kaydetme
add_action('zprint_loaded', function () {
	require_once __DIR__ . '/template.php';
	\Zprint\Templates::registerTemplate(new BoissonsOnlyTemplate());
});

// Compatibility declaration
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});
