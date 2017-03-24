<?php // (C) Copyright Bobbing Wide 2017

/**
 * @package oik-weightcountry-shipping
 * 
 * Test logic in oik-weightcountry-shipping
 */
class Tests_calclate_shipping extends BW_UnitTestCase {

	
	/**
	 * 
	 * Pre-requisites 
	 * - WooCommerce
	 * - WooCommerce test code
	 * - neither oik-weight-zone-shipping nor oik-weight-zone-shipping-pro to be active. 
	 * - Live data in the database must match what we've hard coded.
	 * 
	 * @TODO We're using live data from qw/wordpress here. This is only just about good enough until we can generate it dynamically.
	 * WooCommerce WC_Helper_product isn't quite enough for our needs.
	 */
	function setUp() {
		parent::setUp();
		oik_require( "oik-weightcountry-shipping.php", "oik-weightcountry-shipping" );
		if ( !class_exists( 'WC_Shipping_Method' ) ) {
			oik_require( "includes/abstracts/abstract-wc-shipping-method.php", "woocommerce" );
		} else {
			//echo . PHP_EOL .  'good' . PHP_EOL;
		}
		oik_require( "tests/framework/helpers/class-wc-helper-product.php", "woocommerce" );
	}
	
	/**
	 * Creates a package to be shipped.
	 * 
	 * We have to simulate a WooCommerce 2.5/2.6 package
	 * with an item in the cart that weighs something 
	 * so that we can determine a shipping cost.
	 */
	function get_package() {  
	
		$package = array();
		$package['destination']['country'] = 'UK';
		
		$this->add_to_cart();
		$package = WC()->cart->get_shipping_packages(); 
		//print_r( $package );
		return( $package );
	
	}
	
	/** 
	 * Create dummy product and add it to the cart
	 * 
	 * WC_Help_Product does not set the weight so it's no use to us.
	 * Unless we can $product->update() after setting it ourselves.
	 * 
	 * Also, the code is changing
	 */
	function add_to_cart() {
	
    $product = WC_Helper_Product::create_simple_product();
		bw_trace2( $product, "product" );
		// set_weight() is in 2.7.. which will become 3.0.0
		//  $product->set_weight( 1.00 );
		$product->weight = 1.0;
    WC()->cart->empty_cart();
    // Add the product to the cart. Methods returns boolean on failure, string on success.
    //WC()->cart->add_to_cart( 31631 /* $product->get_id(), 1 );
		// Note: 31631 weighs 100gms
		// 30114 weights 1 kg
		WC()->cart->add_to_cart( 31631, 1 );
	}

	/**
	 * Unit test calculate_shipping
	 *
	 * - Confirm we're using "awd_shipping" - for oik-weightcountry-shipping not oik-weight-zone-shipping
	 * - Confirm it's enabled
	 * - Create a package to pass
	 * - Calculate shipping
	 
	 * `
	 * array(  
		
		    [awd_shipping] => WC_Shipping_Rate Object
        (
            [id] => awd_shipping
            [label] => UK shipping ( 0.1 )
            [cost] => 4.88
            [taxes] => Array
                (
                )

            [method_id] => awd_shipping
            [meta_data:WC_Shipping_Rate:private] => Array
                (
                )
		 `
		
	 * Note: We're using live data from qw/wordpress here.
	 * This is just about good enough until we can generate it dynamically.
	 * 
	  
	 */
	function test_calculate_shipping() {
		$oik_shipping = new OIK_Shipping();
		$this->assertEquals( "awd_shipping", $oik_shipping->id );
		$this->assertEquals( "yes", $oik_shipping->enabled );
		$expected_output = 4.88; 
      

		$package = $this->get_package();     
		$oik_shipping->calculate_shipping( $package[0] );

		$rates = $oik_shipping->rates;
		$cost = $rates['awd_shipping']->cost;
		bw_trace2( $rates, "rates" );
		
		 	
		$this->assertEquals( $expected_output, $cost );
	}
	
}
