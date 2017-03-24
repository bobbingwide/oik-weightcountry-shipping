<?php
/**
 * Plugin Name: oik Weight/Country Shipping
 * Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-weightcountry-shipping
 * Description: WooCommerce extension for Weight/Country shipping
 * Version: 1.3.4
 * Author: bobbingwide
 * Author URI: https://www.oik-plugins.com/author/bobbingwide
 * License: GPL2
 * Text Domain: oik-weightcountry-shipping
 * Domain Path: /languages/
 
    Copyright Bobbing Wide 2014-2017 ( email : herb@bobbingwide.com ) 
    Copyright 2012 andyswebdesign.ie 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html
*/


/**
 * Implement "plugins_loaded" action for oik-weightcountry-shipping
 * 
 */
function init_oik_shipping() {

	if ( !class_exists( 'WC_Shipping_Method' ) ) {
    return;
  }

  /**
   * Weight/Country shipping class WooCommerce Extension
   *
   * Implements shipping charges by weight and country
   *  
   */
	class OIK_Shipping extends WC_Shipping_Method {
  
  
  	/**
  	 * Titles for the selected country group
  	 *
  	 */
  	public $countrygroup_title;
  
    /**
     * Constructor for OIK_Shipping class
     *
     * Sets the ID to 'awd_shipping'
     * 
     */
		function __construct() {
			$this->id           = 'awd_shipping'; // Retain the original code rather than use 'oik_shipping';
			$this->method_title = __( 'Weight/Country', 'oik-weightcountry-shipping' );

			$this->admin_page_heading     = __( 'Weight and country based shipping', 'oik-weightcountry-shipping' );
			$this->admin_page_description = __( 'Define shipping by weight and country', 'oik-weightcountry-shipping' );

			add_action( 'woocommerce_update_options_shipping_awd_shipping', array( &$this, 'process_admin_options' ) );

			$this->init();
			$this->display_country_groups();
		}

		function init() {
			$this->init_form_fields();
			$this->init_settings();

			$this->enabled          = $this->get_option('enabled');
			$this->title            = $this->get_option('title');
      $this->availability     = 'specific';
			$this->country_group_no	= $this->get_option('country_group_no');
      $this->countries 	    = $this->get_option('countries');
			$this->type             = 'order';
			$this->tax_status       = $this->get_option('tax_status');
			$this->fee              = $this->get_option('fee');
			$this->options			= isset( $this->settings['options'] ) ? $this->settings['options'] : '';
			$this->options			= (array) explode( "\n", $this->options );
      if (empty($this->countries)) {
        $this->availability = $this->settings['availability'] = 'all';
      }
      $this->countrygroup_title = $this->title;
		}

		function init_form_fields() {

      $woocommerce = function_exists('WC') ? WC() : $GLOBALS['woocommerce'];

			$this->form_fields = array(
				'enabled'    => array(
					'title'   => __( 'Enable/Disable', 'oik-weightcountry-shipping' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable this shipping method', 'oik-weightcountry-shipping' ),
					'default' => 'no',
				),
				'title'      => array(
					'title'       => __( 'Method Title', 'oik-weightcountry-shipping' ),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'oik-weightcountry-shipping' ),
					'default'     => __( 'Regular Shipping', 'oik-weightcountry-shipping' ),
				),
				'tax_status' => array(
					'title'       => __( 'Tax Status', 'oik-weightcountry-shipping' ),
					'type'        => 'select',
					'description' => '',
					'default'     => 'taxable',
					'options'     => array(
						'taxable' => __( 'Taxable', 'oik-weightcountry-shipping' ),
						'none'    => __( 'None', 'oik-weightcountry-shipping' ),
					),
				),
				'fee'        => array(
					'title'       => __( 'Handling Fee', 'oik-weightcountry-shipping' ),
					'type'        => 'text',
					'description' => __( 'Fee excluding tax, e.g. 3.50. Leave blank to disable.', 'oik-weightcountry-shipping' ),
					'default'     => '',
				),
				'options'       => array(
					'title'       => __( 'Shipping Rates', 'oik-weightcountry-shipping' ),
					'type'        => 'textarea',
					'description' => sprintf( __( 'Set your weight based rates in %1$s for country groups (one per line). <br /> Syntax: Max weight|Cost|Country Group number|Method Title<br />Example: 10|6.95|3| <br />For decimal, use a dot not a comma.', 'oik-weightcountry-shipping' ),  get_option( 'woocommerce_weight_unit' ) ),
					'default'     => '',
				),
				'country_group_no' => array(
					'title' 		=> __( 'Number of country groups', 'oik-weightcountry-shipping' ),
					'type' 			=> 'text',
					'description'	=> __( 'Number of groups of countries sharing delivery rates (hit "Save changes" button after you have changed this setting).', 'oik-weightcountry-shipping' ),
					'default' 		=> '3',
				),

			);
		}

    /*
    * Displays country group selects in shipping method's options
    */
    function display_country_groups() {

	  	$woocommerce = function_exists('WC') ? WC() : $GLOBALS['woocommerce'];
  		$shippingCountries = method_exists($woocommerce->countries, 'get_shipping_countries')
                                    ? $woocommerce->countries->get_shipping_countries()
                                    : $woocommerce->countries->countries;
      $number = $this->country_group_no;
      for ( $counter = 1; $number >= $counter; $counter++ ) {
        $this->form_fields['countries'.$counter] =  array(
                    'title'     => sprintf(__( 'Country Group %s', 'oik-weightcountry-shipping' ), $counter),
                    'type'      => 'multiselect',
                    'class'     => 'chosen_select',
                    'css'       => 'width: 450px;',
                    'default'   => '',
                    'options'   => $shippingCountries
            );
      }
    }

    /**
     * Calculate shipping rates
     * 
     * @param array $package 
     */
		function calculate_shipping( $package = array() ) {
	  	$woocommerce = function_exists('WC') ? WC() : $GLOBALS['woocommerce'];
      $country_group = $this->get_countrygroup($package);
      $rates = $this->get_rates_by_countrygroup( $country_group );
			//bw_trace2( $rates, "rates" );
      $weight = $woocommerce->cart->cart_contents_weight;
			//bw_trace2( $weight, "cart contents weight" );
      $final_rate = $this->pick_smallest_rate( $rates, $weight );
			
      if ( $final_rate !== false && is_numeric( $final_rate )) {
        $taxable = ($this->tax_status == 'taxable') ? true : false;
        if ( $this->fee > 0 && $package['destination']['country'] ) {
          $final_rate += $this->fee;
        }
        $rate = array(
             'id'        => $this->id,
             'label'     => $this->countrygroup_title,
             'cost'      => $final_rate,
             'taxes'     => '',
             'calc_tax'  => 'per_order'
             );

        $this->add_rate( $rate );
      } else {
				add_filter( "woocommerce_cart_no_shipping_available_html", array( $this, 'no_shipping_available') );
			}	
    }

    /**
     * Retrieve the number of the country group for the country selected by the user on checkout
     *
     * @param array $package - expected to contain ['destination']['country']
     * @return integer - country group - which will be 0 if the country is not present in any of the defined country groups
     * Country group 0 can be used to set up default rates
     */
    function get_countrygroup( $package = array() ) {
      $country = $package['destination']['country'];
      $numbers = $this->country_group_no;
      $country_group = 0;
      for ( $counter=1; $counter <= $numbers; $counter++ ) {
        if ( is_array( $this->settings['countries'.$counter] )) {
          if (in_array( $country, $this->settings['countries'.$counter])) {
            $country_group = $counter;
          }
        }
      }  
      return( $country_group );
    }

    /**
     * Retrieves all rates available for the selected country group
     *
     * Now supports separators of '/' forward slash and ',' comma as well as vertical bar
     * Also trims off blanks.
     *
     * @param integer $country_group 
     * @return array $countrygroup_rate - the subset of options for the given country group returned in array form
     */
    function get_rates_by_countrygroup( $country_group = null ) {
      $countrygroup_rate = array();
      if ( sizeof( $this->options ) > 0) {
        foreach ( $this->options as $option => $value ) {
          $value = trim( $value );
          $value = str_replace( array( "/", "," ), "|", $value );
          $rate = explode( "|", $value );
          foreach ( $rate as $key => $val ) {
            $rate[$key] = trim( $val );
          }
          if ( isset( $rate[2] ) && $rate[2] == $country_group ) {
						if ( !isset( $rate[3] ) ) {
							$rate[3] = null;
						}
            $countrygroup_rate[] = $rate;
            $this->set_countrygroup_title( $rate );
          }
        }
      }  
      return( $countrygroup_rate );
    }
    
    /**
     * Set the title for this country group rate
     * 
     * Note: This includes countrygroup 0 - any country not listed
     * and shipping rate for zero weight carts;
     * 
     * @param array $rate - the current rate that we're going to use
     */
    function set_countrygroup_title( $rate ) {
		  //bw_trace2();
      if ( isset( $rate[3] ) && $rate[3] != "" ) {
        $title = $rate[3];
      } else {
        $title = $this->title;
      }
      $this->countrygroup_title = $title;
    }  

    /**
     * Picks the right rate from available rates based on cart weight
     * 
		 * If you want to set a weight at which shipping is free
		 * then set a rate for the weight at the limit, and another way above the limit to 0
		 *
		 * e.g.
		 * `
		 * 50|100.00| 1 | Not free up to and including 50
		 * 999|0.00| 1 | Free above 50, up to 999
		 * 1000| X | 1 | Maximum weight supported is 999
     * `
		 * 
		 * If the weight is above this highest value then the most expensive rate is chosen.
		 * This is rather silly logic... but it'll do for the moment.
		 * 
     * We also set the countrygroup title for the selected rate.  
     * 
     * @param array $rates - array of rates for the selected countrygroup
     * @param string $weight - the cart weight 
     * @return - rate - may be false if no rate can be determined
     */
    function pick_smallest_rate( $rates_array, $weight) {
      $rate = null;
      $max_rate = false;
			$found_weight = -1;
			$found = false;
			
			
      if ( sizeof( $rates_array ) > 0) {
			  $rates = $this->sort_ascending( $rates_array );
				//bw_trace2( $rates, "rates" );
        foreach ( $rates as $key => $value) {
          if ( $weight <= $value[0] && ( $found_weight < $weight ) ) {
            if ( true || null === $rate || $value[1] < $rate ) {
              $rate = $value[1];
						  //bw_trace2( $rate, "rate is now", false );
							$found_weight = $value[0];
							$found = true;
              $this->set_countrygroup_title( $value );
            }   
          }
          if ( !$found  ) {
            if ( !$max_rate || $value[1] > $max_rate ) {
              $max_rate = $value[1];
              $this->set_countrygroup_title( $value );
            }
          }   
        }
      }
      if ( null === $rate ) {
        $rate = $max_rate;
				//$rate = false;
      }  
      return $rate;
    }
		
		/**
		 * Sort the rates array by ascending weight
		 *
		 * @param array $rates_array array of rates
		 * @return array sorted by ascending weight. 
		 */
		function sort_ascending( $rates_array ) {
		  $weights = array();
			$rates = array();
			//$group = array();
			$labels = array();
			foreach ( $rates_array as $key => $value ) {
			  $weights[ $key ] = $value[0];
        $rates[ $key ] = $value[1];
				$labels[ $key ] = $value[3];
			}
			//bw_trace2();
			array_multisort( $weights, SORT_ASC, SORT_NUMERIC, $rates, $labels );
			//bw_trace2( $weights, "weights", false );
			//bw_trace2( $rates, "weights", false );
			//bw_trace2( $labels, "labels", false );
			foreach ( $weights as $key => $value ) {
			  $new_array[] = array( $value, $rates[ $key ], null, $labels[ $key ] ); 
			} 
			return( $new_array );
		}
		
		/**
		 * Implement "woocommerce_cart_no_shipping_available_html" 
		 *
		 * @param string $html message to be displayed when there are no shipping methods available
		 * @return string Updated with our own version taken from the rates if the default has been overridden
		 */
		function no_shipping_available( $html ) {
			if ( $this->countrygroup_title && $this->countrygroup_title != $this->title ) {
				$html = $this->countrygroup_title;
			}
			return( $html );
		}
    
		/**
		 * Display Weight/Country shipping options
		 * 
		 */
		public function admin_options() {

    	?>
    	<h3><?php _e('Weight and Country based shipping', 'oik-weightcountry-shipping'); ?></h3>
    	<p><?php _e('Lets you calculate shipping based on Country and weight of the cart.', 'oik-weightcountry-shipping' ); ?>
      <br /><?php _e( 'Lets you set unlimited weight bands on per country basis and group countries that share same delivery cost/bands.', 'oik-weightcountry-shipping' ); ?>
      </p>
    	<table class="form-table">
    	<?php
    		// Generate the HTML for the settings form.
    		$this->generate_settings_html();
    	?>
		</table><!--/.form-table-->
    	<?php
    }

	} // end OIK_Shipping
}

  
/**
 * Implement 'woocommerce_shipping_methods' filter for oik-weightcountry-shipping
 *
 * @param array $methods array of shipping method classes
 * @return array array with "OIK_shipping" included
 */  
function add_oik_shipping( $methods ) {
	$methods[] = 'OIK_Shipping';
	return $methods;
}

/**
 * Implement 'woocommerce_init' to load l10n versions and then initialise weight/country shipping
 * 
 */
function init_oik_weightcountry_l10n() {
	load_plugin_textdomain( "oik-weightcountry-shipping", false, 'oik-weightcountry-shipping/languages' );
	init_oik_shipping();
}
	
//add_action( 'plugins_loaded', 'init_oik_shipping', 0 );
add_filter( 'woocommerce_shipping_methods', 'add_oik_shipping' );
add_action( 'woocommerce_init', 'init_oik_weightcountry_l10n' );

if ( !function_exists( "bw_trace2" ) ) {
  function bw_trace2( $p=null ) { return $p; }
	function bw_backtrace() {}
}


