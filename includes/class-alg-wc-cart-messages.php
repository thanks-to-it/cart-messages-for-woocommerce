<?php
/**
 * Cart Messages for WooCommerce - Main Class
 *
 * @version 1.5.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Cart_Messages' ) ) :

final class Alg_WC_Cart_Messages {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_WC_CART_MESSAGES_VERSION;

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
	 * @version 1.5.1
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Check for active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Declare compatibility with custom order tables for WooCommerce
		add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

		// Pro
		if ( 'cart-messages-for-woocommerce-pro.php' === basename( ALG_WC_CART_MESSAGES_FILE ) ) {
			require_once( 'pro/class-alg-wc-cart-messages-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * localize.
	 *
	 * @version 1.4.0
	 * @since   1.2.0
	 */
	function localize() {
		load_plugin_textdomain( 'cart-messages-for-woocommerce', false, dirname( plugin_basename( ALG_WC_CART_MESSAGES_FILE ) ) . '/langs/' );
	}

	/**
	 * wc_declare_compatibility.
	 *
	 * @version 1.5.1
	 * @since   1.5.1
	 *
	 * @see     https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book#declaring-extension-incompatibility
	 */
	function wc_declare_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			$files = ( defined( 'ALG_WC_CART_MESSAGES_FILE_FREE' ) ?
				array( ALG_WC_CART_MESSAGES_FILE, ALG_WC_CART_MESSAGES_FILE_FREE ) : array( ALG_WC_CART_MESSAGES_FILE ) );
			foreach ( $files as $file ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $file, true );
			}
		}
	}

	/**
	 * includes.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once( 'class-alg-wc-cart-messages-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_WC_CART_MESSAGES_FILE ), array( $this, 'action_links' ) );
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
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_cart_messages' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'cart-messages-for-woocommerce.php' === basename( ALG_WC_CART_MESSAGES_FILE ) ) {
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
		$settings[] = require_once( 'settings/class-alg-wc-cart-messages-settings.php' );
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
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WC_CART_MESSAGES_FILE ) );
	}

	/**
	 * plugin_path.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WC_CART_MESSAGES_FILE ) );
	}

}

endif;
