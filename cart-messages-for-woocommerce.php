<?php
/*
Plugin Name: Cart Messages for WooCommerce
Plugin URI: https://wpfactory.com/item/cart-messages-for-woocommerce/
Description: Customize WooCommerce cart notices.
Version: 1.3.0
Author: Algoritmika Ltd
Author URI: https://algoritmika.com
Text Domain: cart-messages-for-woocommerce
Domain Path: /langs
WC tested up to: 5.2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Cart_Messages' ) ) :

/**
 * Main Alg_WC_Cart_Messages Class
 *
 * @version 1.2.0
 * @since   1.0.0
 *
 * @class   Alg_WC_Cart_Messages
 */
final class Alg_WC_Cart_Messages {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.3.0';

	/**
	 * @var   Alg_WC_Cart_Messages The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Cart_Messages Instance
	 *
	 * Ensures only one instance of Alg_WC_Cart_Messages is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_WC_Cart_Messages - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Cart_Messages Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Check for active plugins
		if (
			! $this->is_plugin_active( 'woocommerce/woocommerce.php' ) ||
			( 'cart-messages-for-woocommerce.php' === basename( __FILE__ ) && $this->is_plugin_active( 'cart-messages-for-woocommerce-pro/cart-messages-for-woocommerce-pro.php' ) )
		) {
			return;
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Pro
		if ( 'cart-messages-for-woocommerce-pro.php' === basename( __FILE__ ) ) {
			require_once( 'includes/pro/class-alg-wc-cart-messages-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * is_plugin_active.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function is_plugin_active( $plugin ) {
		return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) :
			(
				in_array( $plugin, apply_filters( 'active_plugins', ( array ) get_option( 'active_plugins', array() ) ) ) ||
				( is_multisite() && array_key_exists( $plugin, ( array ) get_site_option( 'active_sitewide_plugins', array() ) ) )
			)
		);
	}

	/**
	 * localize.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function localize() {
		load_plugin_textdomain( 'cart-messages-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
	}

	/**
	 * includes.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once( 'includes/class-alg-wc-cart-messages-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		// Version updated
		if ( get_option( 'alg_wc_cart_messages_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * action_links.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_cart_messages' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'cart-messages-for-woocommerce.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/cart-messages-for-woocommerce/">' .
				__( 'Go Pro', 'cart-messages-for-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * add_woocommerce_settings_tab.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'includes/settings/class-alg-wc-cart-messages-settings.php' );
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function version_updated() {
		update_option( 'alg_wc_cart_messages_version', $this->version );
	}

	/**
	 * plugin_url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * plugin_path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( ! function_exists( 'alg_wc_cart_messages' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Cart_Messages to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return  Alg_WC_Cart_Messages
	 *
	 * @todo    [later] (dev) `plugins_loaded`?
	 */
	function alg_wc_cart_messages() {
		return Alg_WC_Cart_Messages::instance();
	}
}

alg_wc_cart_messages();
