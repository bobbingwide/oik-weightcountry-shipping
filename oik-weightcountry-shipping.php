<?php
/**
 * Plugin Name: oik Weight/Country Shipping
 * Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-weightcountry-shipping
 * Description: WooCommerce extension for Weight/Country shipping
 * Version: 1.0.8
 * Author: bobbingwide
 * Author URI: http://www.oik-plugins.com/author/bobbingwide
 * License: GPL2
 * Text Domain: oik-weightcountry-shipping
 * Domain Path: /languages/
 
    Copyright 2012 andyswebdesign.ie 
    Copyright Bobbing Wide 2014 ( email : herb@bobbingwide.com ) 

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

add_action( 'plugins_loaded', 'init_oik_shipping', 0 );


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
					'description' => __( 'Set your weight based rates in ' . get_option( 'woocommerce_weight_unit' ) . ' for country groups (one per line). Syntax: Max weight|Cost|country group number. Example: 10|6.95|3. For decimal, use a dot not a comma.', 'oik-weightcountry-shipping' ),
					'default'     => '',
				),
				'country_group_no' => array(
					'title' 		=> __( 'Number of country groups', 'oik-weightcountry-shipping' ),
					'type' 			=> 'text',
					'description'	=> __( 'Number of groups of countries sharing delivery rates (hit "Save changes" button after you have changed this setting).' ),
					'default' 		=> '3',
				),
        
        /* Didn't re-add sync-countries option since this has updated in WooCommerce 2.1 Herb 2014/03/27
        // @TODO Need to use network_admin_url( "/wp-admin/admin.php?page=woocommerce_settings&tab=general"
        'sync_countries' => array(
           'title' 		=> __( 'Add countries to allowed', 'oik-weightcountry-shipping' ),
           'type' 			=> 'checkbox',
           'label' 		=> __( 'Countries added to country groups will be automatically added to the Allowed Countries in the General settings tab.
                             This makes sure countries defined in country groups are visible on checkout.
                             Note: Deleting a country from the country group will not delete the country from Allowed Countries.', 'oik-weightcountry-shipping' ),
           'default' 		=> 'no',
        ),
        */

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
      $weight     = $woocommerce->cart->cart_contents_weight;
      $final_rate = $this->pick_smallest_rate($rates, $weight);
      if ( $final_rate !== false) {
        $taxable = ($this->tax_status == 'taxable') ? true : false;
        if ( $this->fee > 0 && $package['destination']['country'] ) {
          $final_rate += $this->fee;
        }
        $rate = array(
             'id'        => $this->id,
             'label'     => $this->title,
             'cost'      => $final_rate,
             'taxes'     => '',
             'calc_tax'  => 'per_order'
             );

        $this->add_rate( $rate );
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
            $countrygroup_rate[] = $rate;
          }
        }
      }  
      return( $countrygroup_rate );
    }

    /**
     * Picks the right rate from available rates based on cart weight
     * 
     * Return the lowest value for any rate where the weight value exceeds the given cart weight
     * If there is no rate for the given cart weight then return the highest rate found
     * @param array $rates
     * @param string $weight 
     * @return - rate - may be false if no rate can be determined
     */
    function pick_smallest_rate( $rates, $weight) {
      $rate = false;
      $postage = array();
      $postage_all_rates = array();
      if ( sizeof($rates) > 0) {
        foreach ( $rates as $key => $value) {
          if ( $weight <= $value[0] ) {
            $postage[] = $value[1];
          }
          $postage_all_rates[] = $value[1];
        }
      }  
      if ( sizeof($postage) > 0) {
        $rate = min($postage);
      } elseif ( sizeof( $postage_all_rates ) > 0 ) {
        $rate = max($postage_all_rates);
      }
      return $rate;
    }

    /**
     * Possibly redundant function
     * @TODO - remove if that's the case
     */
    function etz($etz) {
        if(empty($etz) || !is_numeric($etz)) {
            return 0.00;
        }
    }
    
    /**
     *   For help and how to use go <a href="http://www.andyswebdesign.ie/blog/free-woocommerce-weight-and-country-based-shipping-extension-plugin/" target="_blank">here</a>', 'oik-weightcountry-shipping'); 
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
 */  
function add_oik_shipping( $methods ) {
	$methods[] = 'OIK_Shipping';
	return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'add_oik_shipping' );


