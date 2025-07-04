<?php
/*
Plugin Name: Cart & Checkout Notices/Messages for WooCommerce
Plugin URI: https://wordpress.org/plugins/cart-messages-for-woocommerce/
Description: Add and customize WooCommerce cart and checkout notices.
Version: 2.0.0
Author: Algoritmika Ltd
Author URI: https://profiles.wordpress.org/algoritmika/
Requires at least: 4.4
Text Domain: cart-messages-for-woocommerce
Domain Path: /langs
WC tested up to: 9.9
Requires Plugins: woocommerce
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
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
		(
			is_multisite() &&
			array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) )
		)
	) {
		defined( 'ALG_WC_CART_MESSAGES_FILE_FREE' ) || define( 'ALG_WC_CART_MESSAGES_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_CART_MESSAGES_VERSION' ) || define( 'ALG_WC_CART_MESSAGES_VERSION', '2.0.0' );

defined( 'ALG_WC_CART_MESSAGES_FILE' ) || define( 'ALG_WC_CART_MESSAGES_FILE', __FILE__ );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-alg-wc-cart-messages.php';

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
