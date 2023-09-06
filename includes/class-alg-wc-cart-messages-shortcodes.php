<?php
/**
 * Cart Messages for WooCommerce - Shortcodes Class
 *
 * @version 1.5.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Cart_Messages_Shortcodes' ) ) :

class Alg_WC_Cart_Messages_Shortcodes {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) add `[alg_wc_cm_countdown_timer]` (https://www.w3schools.com/howto/howto_js_countdown.asp)
	 */
	function __construct() {

		// Cart
		add_shortcode( 'alg_wc_cm_cart_contents_total',         array( $this, 'cart_contents_total' ) );
		add_shortcode( 'alg_wc_cm_minus_cart_contents_total',   array( $this, 'minus_cart_contents_total' ) );
		add_shortcode( 'alg_wc_cm_cart_contents_count',         array( $this, 'cart_contents_count' ) );
		add_shortcode( 'alg_wc_cm_applied_coupons',             array( $this, 'cart_applied_coupons' ) );
		add_shortcode( 'alg_wc_cm_cart_function',               array( $this, 'cart_function' ) );

		// Other
		add_shortcode( 'alg_wc_cm_product_titles',              array( $this, 'product_titles' ) );
		add_shortcode( 'alg_wc_cm_product_quantities',          array( $this, 'product_quantities' ) );

	}

	/**
	 * product_quantities.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (feature) customizable glue
	 */
	function product_quantities( $atts, $content = '' ) {
		if ( ! empty( $this->products_and_quantities ) ) {
			return implode( ', ', $this->products_and_quantities );
		}
	}

	/**
	 * product_titles.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (feature) customizable glue
	 */
	function product_titles( $atts, $content = '' ) {
		if ( ! empty( $this->products_and_quantities ) ) {
			return implode( ', ', array_map( 'get_the_title', array_keys( $this->products_and_quantities ) ) );
		}
	}

	/**
	 * cart_function.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (feature) optional function params
	 */
	function cart_function( $atts, $content = '' ) {
		if ( isset( $atts['name'] ) && is_callable( array( WC()->cart, $atts['name'] ) ) ) {
			if ( isset( $atts['calculate_totals'] ) && filter_var( $atts['calculate_totals'], FILTER_VALIDATE_BOOLEAN ) ) {
				WC()->cart->calculate_totals();
			}
			return $this->output( WC()->cart->{$atts['name']}(), $atts );
		}
	}

	/**
	 * cart_applied_coupons.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function cart_applied_coupons( $atts, $content = '' ) {
		return $this->output( implode( ', ', WC()->cart->get_applied_coupons() ), $atts );
	}

	/**
	 * cart_contents_count.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function cart_contents_count( $atts, $content = '' ) {
		return $this->output( WC()->cart->get_cart_contents_count(), $atts );
	}

	/**
	 * minus_cart_contents_total.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) optional `max( $x, 0 )`
	 */
	function minus_cart_contents_total( $atts, $content = '' ) {
		if ( isset( $atts['base'] ) ) {
			$atts['format'] = 'wc_price';
			$diff = max( ( $atts['base'] - WC()->cart->get_cart_contents_total() ), 0 );
			return $this->output( $diff, $atts );
		}
	}

	/**
	 * cart_contents_total.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function cart_contents_total( $atts, $content = '' ) {
		if ( empty( $atts ) ) {
			$atts = array();
		}
		$atts['format'] = 'wc_price';
		return $this->output( WC()->cart->get_cart_contents_total(), $atts );
	}

	/**
	 * output.
	 *
	 * @version 1.5.1
	 * @since   1.0.0
	 */
	function output( $value, $atts ) {
		return ( ! empty( $value ) ?
			( isset( $atts['before'] ) ? $atts['before'] : '' ) . $this->output_value( $value, $atts ) . ( isset( $atts['after'] ) ? $atts['after'] : '' ) :
			( isset( $atts['on_empty'] ) ? '{{{on_empty}}}' . $atts['on_empty'] : '' ) );
	}

	/**
	 * output_value.
	 *
	 * @version 1.2.0
	 * @since   1.0.1
	 */
	function output_value( $value, $atts ) {
		$value = ( isset( $atts['multiply_by'] ) ? $atts['multiply_by'] * $value : $value );
		return ( isset( $atts['format'] ) && function_exists( $atts['format'] ) ? $atts['format']( $value ) : $value );
	}

}

endif;

return new Alg_WC_Cart_Messages_Shortcodes();
