<?php // (C) Copyright Bobbing Wide 2017-2020

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
	 * Set these in WooCommerce / Settings / Shipping / Weight/Country
	 *
	 * Field     | Value
	 * ---------------- | ---------
	 * Shipping method | Enabled
	 * Method Title | Regular Shipping
	 * Tax Status | Taxable
	 * Handling Fee | 1.23
	 * Shippping Rates | See owcs.csv
	 * Number of country groups | 4
	 * Country Group 1 | UK, Portugal
	 * Country Group 2 | France
	 * Country Group 3 | Canada, US
	 * Country Group 4 |
	 *
	 * Note: It doesn't really matter about the country groups so long as UK is in Country Group 1
	 * 
	 * We're using live data from s.b/wordpress here.
	 * This is only just about good enough until we can generate it dynamically.
	 * WooCommerce WC_Helper_product isn't quite enough for our needs.
	 */
	function setUp() :void  {
		parent::setUp();
		oik_require( "oik-weightcountry-shipping.php", "oik-weightcountry-shipping" );
		if ( !class_exists( 'WC_Shipping_Method' ) ) {
			oik_require( "includes/abstracts/abstract-wc-shipping-method.php", "woocommerce" );
		} else {
			//echo . PHP_EOL .  'good' . PHP_EOL;
		}
		oik_require( "tests/legacy/framework/helpers/class-wc-helper-product.php", "woocommerce-source" );
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
		// In s.b the product is ID: 10499, Title: 100gm is 0.1 kilos
		WC()->cart->add_to_cart( 10499, 1 );
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
