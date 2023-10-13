<?php
/**
 * Cart Messages for WooCommerce - Core Class
 *
 * @version 1.5.3
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Cart_Messages_Core' ) ) :

class Alg_WC_Cart_Messages_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) cart and checkout hooks: customizable (or at least check if current hooks are the best picks)
	 */
	function __construct() {

		if ( 'yes' === get_option( 'alg_wc_cart_messages_plugin_enabled', 'yes' ) ) {

			// Cart & Checkout Messages
			foreach ( array( 'cart', 'checkout' ) as $cart_or_checkout ) {
				if ( 'yes' === get_option( 'alg_wc_cart_messages_' . $cart_or_checkout . '_section_enabled', 'no' ) ) {
					$hook_name = ( 'cart' === $cart_or_checkout ? 'woocommerce_before_cart' : 'woocommerce_before_checkout_form' );
					$hook_name = apply_filters( "alg_wc_cart_messages_{$cart_or_checkout}_hook_name", $hook_name );
					$priority  = apply_filters( "alg_wc_cart_messages_{$cart_or_checkout}_hook_priority", 9 );
					add_action( $hook_name, array( $this, $cart_or_checkout . '_notices' ), $priority );
				}
			}

			// Add to Cart Messages
			if ( 'yes' === get_option( 'alg_wc_cart_messages_add_to_cart_section_enabled', 'no' ) ) {
				if ( 'yes' === get_option( 'alg_wc_cart_messages_add_to_cart_custom_enabled', 'no' ) ) {
					add_filter( 'wc_add_to_cart_message_html', array( $this, 'add_to_cart_message_custom' ), PHP_INT_MAX, 3 );
				}
				if ( 'yes' === get_option( 'alg_wc_cart_messages_add_to_cart_by_url_enabled', 'no' ) ) {
					add_filter( 'wc_add_to_cart_message_html', array( $this, 'add_to_cart_message_html_by_url' ), PHP_INT_MAX, 3 );
				}
			}

			// Shortcodes
			$this->shortcodes = require_once( 'class-alg-wc-cart-messages-shortcodes.php' );

		}

	}

	/**
	 * add_notices.
	 *
	 * @version 1.5.3
	 * @since   1.0.0
	 */
	function add_notices( $cart_or_checkout ) {
		$messages = get_option( 'alg_wc_cart_messages_' . $cart_or_checkout . '_messages', array() );
		if ( ! empty( $messages ) && 0 != ( $total_number = apply_filters( 'alg_wc_cart_messages_total_number', 1, $cart_or_checkout ) ) ) {
			$messages       = array_slice( $messages, 0, $total_number, true );
			$types          = get_option( 'alg_wc_cart_messages_' . $cart_or_checkout . '_message_types',          array() );
			$types_on_empty = get_option( 'alg_wc_cart_messages_' . $cart_or_checkout . '_message_types_on_empty', array() );
			$visibilities   = get_option( 'alg_wc_cart_messages_' . $cart_or_checkout . '_message_visibilities',   array() );
			foreach ( $messages as $i => $message ) {
				if (
					apply_filters( "alg_wc_cart_messages_{$cart_or_checkout}_add_notice", true, $i ) &&
					'' != $message && '' != ( $message = do_shortcode( $message ) ) &&
					(
						! isset( $visibilities[ $i ] ) || 'always' === $visibilities[ $i ] ||
						( 'url_param' === $visibilities[ $i ] && isset( $_GET[ 'alg-wc-' . $cart_or_checkout . '-message' ] ) && $i == intval( $_GET[ 'alg-wc-' . $cart_or_checkout . '-message' ] ) )
					)
				) {
					$type = ( isset( $types[ $i ] ) ? $types[ $i ] : 'notice' );
					if ( false !== strpos( $message, '{{{on_empty}}}' ) ) {
						$message = str_replace( '{{{on_empty}}}', '', $message );
						$type    = ( isset( $types_on_empty[ $i ] ) && 'default' != $types_on_empty[ $i ] ? $types_on_empty[ $i ] : $type );
					}
					if ( ! wc_has_notice( $message, $type ) ) {
						wc_add_notice( $message, $type );
					}
				}
			}
		}
	}

	/**
	 * cart_notices.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function cart_notices() {
		$this->add_notices( 'cart' );
	}

	/**
	 * checkout_notices.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function checkout_notices() {
		$this->add_notices( 'checkout' );
	}

	/**
	 * add_to_cart_message_custom.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function add_to_cart_message_custom( $message, $products, $show_qty ) {
		$this->shortcodes->products_and_quantities = $products;
		$result = do_shortcode( get_option( 'alg_wc_cart_messages_add_to_cart_custom_text', '' ) );
		$this->shortcodes->products_and_quantities = false;
		return $result;
	}

	/**
	 * add_to_cart_message_html_by_url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_to_cart_message_html_by_url( $message, $products, $show_qty ) {
		if ( isset( $_GET['add-to-cart'] ) ) {
			$product_id = sanitize_key( $_GET['add-to-cart'] );
			$added_text = sprintf( __( '%s has been added to your cart.', 'woocommerce' ),
				apply_filters( 'woocommerce_add_to_cart_item_name_in_quotes',
					sprintf( _x( '&ldquo;%s&rdquo;', 'Item name in quotes', 'woocommerce' ), strip_tags( get_the_title( $product_id ) ) ), $product_id ) );
			return $this->add_to_cart_message_html_get_success_message( $added_text );
		}
		return $message;
	}

	/**
	 * add_to_cart_message_html_get_success_message.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_to_cart_message_html_get_success_message( $added_text ) {
		if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			$return_to = apply_filters( 'woocommerce_continue_shopping_redirect', wc_get_raw_referer() ?
				wp_validate_redirect( wc_get_raw_referer(), false ) : wc_get_page_permalink( 'shop' ) );
			$message = sprintf( '<a href="%s" tabindex="1" class="button wc-forward">%s</a> %s',
				esc_url( $return_to ), esc_html__( 'Continue shopping', 'woocommerce' ), esc_html( $added_text ) );
		} else {
			$message = sprintf( '<a href="%s" tabindex="1" class="button wc-forward">%s</a> %s',
				esc_url( wc_get_page_permalink( 'cart' ) ), esc_html__( 'View cart', 'woocommerce' ), esc_html( $added_text ) );
		}
		return $message;
	}

}

endif;

return new Alg_WC_Cart_Messages_Core();
