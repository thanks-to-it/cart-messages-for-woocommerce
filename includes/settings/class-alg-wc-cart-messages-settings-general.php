<?php
/**
 * Cart Messages for WooCommerce - General Section Settings
 *
 * @version 1.3.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Cart_Messages_Settings_General' ) ) :

class Alg_WC_Cart_Messages_Settings_General extends Alg_WC_Cart_Messages_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'cart-messages-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_messages_settings.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function get_messages_settings( $cart_or_checkout ) {
		$heading  = ( 'cart' === $cart_or_checkout ? __( 'Cart', 'cart-messages-for-woocommerce' ) : __( 'Checkout', 'cart-messages-for-woocommerce' ) );
		$settings = array(
			array(
				'title'    => sprintf( __( '%s Messages', 'cart-messages-for-woocommerce' ), $heading ),
				'desc'     => sprintf( __( 'Shortcodes: %s', 'cart-messages-for-woocommerce' ), '<code>' . implode( '</code>, <code>', array(
					'[alg_wc_cm_cart_contents_total]',
					'[alg_wc_cm_minus_cart_contents_total]',
					'[alg_wc_cm_cart_contents_count]',
					'[alg_wc_cm_applied_coupons]',
					'[alg_wc_cm_cart_function]',
				) ) . '</code>' ),
				'type'     => 'title',
				'id'       => 'alg_wc_cart_messages_' . $cart_or_checkout . '_options',
			),
			array(
				'title'    => sprintf( __( '%s messages', 'cart-messages-for-woocommerce' ), $heading ),
				'desc'     => '<strong>' . __( 'Enable section', 'cart-messages-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_cart_messages_' . $cart_or_checkout . '_section_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Total number', 'cart-messages-for-woocommerce' ),
				'desc'     => apply_filters( 'alg_wc_cart_messages_settings',
					'You will need <a href="https://wpfactory.com/item/cart-messages-for-woocommerce/" target="_blank">Cart Messages for WooCommerce Pro</a> plugin version to add more than one message.' ),
				'desc_tip' => __( 'New settings fields will be displayed if you change this number and save changes.', 'cart-messages-for-woocommerce' ),
				'id'       => 'alg_wc_cart_messages_' . $cart_or_checkout . '_message_total_number',
				'default'  => 1,
				'type'     => 'number',
				'custom_attributes' => apply_filters( 'alg_wc_cart_messages_settings', array( 'readonly' => 'readonly' ), 'total_number' ),
			),
		);
		for ( $i = 1; $i <= apply_filters( 'alg_wc_cart_messages_total_number', 1, $cart_or_checkout ); $i++ ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => sprintf( __( 'Message #%d', 'cart-messages-for-woocommerce' ), $i ),
					'id'       => "alg_wc_cart_messages_{$cart_or_checkout}_messages[{$i}]",
					'default'  => '',
					'type'     => 'textarea',
					'css'      => 'width:100%;',
				),
				array(
					'desc'     => __( 'Type', 'cart-messages-for-woocommerce' ),
					'desc_tip' => __( 'Please note that "error" messages are always displayed first, "success" messages in the middle and "notice" messages are always displayed last.', 'cart-messages-for-woocommerce' ) . ' ' .
						__( 'Also please note that all "error" messages are merged into single section on frontend.', 'cart-messages-for-woocommerce' ),
					'id'       => "alg_wc_cart_messages_{$cart_or_checkout}_message_types[{$i}]",
					'default'  => 'notice',
					'type'     => 'select',
					'class'    => 'chosen_select',
					'options'  => array(
						'notice'  => __( 'Notice', 'cart-messages-for-woocommerce' ),
						'success' => __( 'Success', 'cart-messages-for-woocommerce' ),
						'error'   => __( 'Error', 'cart-messages-for-woocommerce' ),
					),
				),
				array(
					'desc'     => __( 'Type', 'cart-messages-for-woocommerce' ) . ' <code>on_empty</code>',
					'id'       => "alg_wc_cart_messages_{$cart_or_checkout}_message_types_on_empty[{$i}]",
					'default'  => 'default',
					'type'     => 'select',
					'class'    => 'chosen_select',
					'options'  => array(
						'default' => __( 'No changes (i.e. same as "Type")', 'cart-messages-for-woocommerce' ),
						'notice'  => __( 'Notice', 'cart-messages-for-woocommerce' ),
						'success' => __( 'Success', 'cart-messages-for-woocommerce' ),
						'error'   => __( 'Error', 'cart-messages-for-woocommerce' ),
					),
				),
				array(
					'desc'     => __( 'Visibility', 'cart-messages-for-woocommerce' ),
					'desc_tip' => sprintf( __( 'For URL param: %s', 'cart-messages-for-woocommerce' ), "<br><em>/?alg-wc-{$cart_or_checkout}-message={$i}</em>" ),
					'id'       => "alg_wc_cart_messages_{$cart_or_checkout}_message_visibilities[{$i}]",
					'default'  => 'always',
					'type'     => 'select',
					'class'    => 'chosen_select',
					'options'  => array(
						'always'    => __( 'Always', 'cart-messages-for-woocommerce' ),
						'url_param' => __( 'URL param', 'cart-messages-for-woocommerce' ),
					),
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_cart_messages_' . $cart_or_checkout . '_options',
			),
		) );
		return $settings;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) split into separate sections
	 * @todo    (desc) better section descriptions
	 */
	function get_settings() {

		$plugin_settings = array(
			array(
				'title'    => __( 'Cart Messages Options', 'cart-messages-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_cart_messages_plugin_options',
			),
			array(
				'title'    => __( 'Cart Messages', 'cart-messages-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'cart-messages-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_cart_messages_plugin_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_cart_messages_plugin_options',
			),
		);

		$cart_settings     = $this->get_messages_settings( 'cart' );

		$checkout_settings = $this->get_messages_settings( 'checkout' );

		$add_to_cart_settings = array(
			array(
				'title'    => __( 'Add to Cart Messages', 'cart-messages-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_cart_messages_add_to_cart_options',
			),
			array(
				'title'    => __( 'Add to cart messages', 'cart-messages-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'cart-messages-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_cart_messages_add_to_cart_section_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Custom text', 'cart-messages-for-woocommerce' ),
				'desc'     => __( 'Enable', 'cart-messages-for-woocommerce' ),
				'desc_tip' => __( 'Custom add to cart message.', 'cart-messages-for-woocommerce' ),
				'id'       => 'alg_wc_cart_messages_add_to_cart_custom_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'desc'     => sprintf( __( 'Shortcodes: %s', 'cart-messages-for-woocommerce' ), '<code>' . implode( '</code>, <code>', array(
					'[alg_wc_cm_cart_contents_total]',
					'[alg_wc_cm_minus_cart_contents_total]',
					'[alg_wc_cm_cart_contents_count]',
					'[alg_wc_cm_applied_coupons]',
					'[alg_wc_cm_cart_function]',
					'[alg_wc_cm_product_titles]',
					'[alg_wc_cm_product_quantities]',
				) ) . '</code>' ),
				'desc_tip' => __( 'You can use shortcodes and/or HTML here.', 'cart-messages-for-woocommerce' ) . ' ' .
					__( 'If empty - no add to cart message will be displayed.', 'cart-messages-for-woocommerce' ),
				'id'       => 'alg_wc_cart_messages_add_to_cart_custom_text',
				'default'  => '',
				'type'     => 'textarea',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Product title by URL', 'cart-messages-for-woocommerce' ),
				'desc'     => __( 'Enable', 'cart-messages-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'Will get product title from %s parameter in URL.', 'cart-messages-for-woocommerce' ), '<code>add-to-cart</code>' ) . ' ' .
					__( 'If enabled - can override "Custom text" option.', 'cart-messages-for-woocommerce' ),
				'id'       => 'alg_wc_cart_messages_add_to_cart_by_url_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_cart_messages_add_to_cart_options',
			),
		);

		return array_merge( $plugin_settings, $cart_settings, $checkout_settings, $add_to_cart_settings );
	}

}

endif;

return new Alg_WC_Cart_Messages_Settings_General();
