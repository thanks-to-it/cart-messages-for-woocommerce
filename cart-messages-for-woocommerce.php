<?php
/*
Plugin Name: Cart Messages for WooCommerce
Plugin URI: https://wpfactory.com/item/cart-messages-for-woocommerce/
Description: Add and customize WooCommerce cart and checkout notices.
Version: 1.5.4
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: cart-messages-for-woocommerce
Domain Path: /langs
WC tested up to: 9.1
Requires Plugins: woocommerce
*/

defined( 'ABSPATH' ) || exit;

if ( 'cart-messages-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.5.1
	 * @since   1.4.0
	 */
	$plugin = 'cart-messages-for-woocommerce-pro/cart-messages-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		defined( 'ALG_WC_CART_MESSAGES_FILE_FREE' ) || define( 'ALG_WC_CART_MESSAGES_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_CART_MESSAGES_VERSION' ) || define( 'ALG_WC_CART_MESSAGES_VERSION', '1.5.4' );

defined( 'ALG_WC_CART_MESSAGES_FILE' ) || define( 'ALG_WC_CART_MESSAGES_FILE', __FILE__ );

require_once( 'includes/class-alg-wc-cart-messages.php' );

if ( ! function_exists( 'alg_wc_cart_messages' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Cart_Messages to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_cart_messages() {
		return Alg_WC_Cart_Messages::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_cart_messages' );
