# Weight/Country Shipping for WooCommerce 
![banner](assets/oik-weightcountry-shipping-banner-772x250.jpg)
* Contributors: bobbingwide, vsgloik, Andy_P, pozhonks
* Donate link: https://www.oik-plugins.com/oik/oik-donate/
* Tags: woocommerce, commerce, ecommerce, shipping, weight, country, shop
* Requires at least: 3.9
* Tested up to: 6.4-RC1
* Stable tag: 1.4.3
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds per-country and weight based shipping cost calculation method to your WooCommerce store.

## Description 

Please switch to [oik-weight-zone-shipping](https://wordpress.org/plugins/oik-weight-zone-shipping)
as this plugin is integrated with Shipping Zones.

This oik Weight/Country Shipping for WooCommerce plugin is no longer supported.
Having said that, it has been tested with WordPress 6.3 and WooCommerce 7.9.0.

# Known Limitations 

* Requires Countries to have been added to the Specific Countries list, if your Selling Location(s) option is to 'Sell to specific countries only'
* Calculates charges based on the total cart weight; it doesn't pay any attention to shipping classes or product categories
* Doesn't support different charges to state or region
* Doesn't take into account dimensions
* Only returns one shipping rate per weight/country group combination.


# Documentation 

After installation activate this shipping method.

Go to WooCommerce->Settings->Shipping select  Weight/Country Shipping and tick enable box.

Rates are set based on "Country Groups".
Country Groups are groups of countries (or a single country) that share same delivery rates.

## Installation 
1. Upload 'oik-weightcountry-shipping' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set your delivery rates in WooCommerce->Settings->Shipping using the Weight/Country tab


## Frequently Asked Questions 
# Installation 
1. Upload 'oik-weightcountry-shipping' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set your delivery rates in WooCommerce->Settings->Shipping using the Weight/Country tab


# What is the separator for the shipping rate table? 

You can use vertical bars, forward slashes or commas.
Blanks around values will be ignored.

```
0|9.99|0
1 | 92.99 | 0
1 | 92.98 | 2
100 | 93.97 | 2
30|120.00|1
0| 1.23 | 3
1 / 1.24 / 3
2 , 3.45 , 3

```

# How do I set the Method Title? 
If you want to use a different title per rate then add this for each rate where the Method Title should be different from the default.

```
0|9.99|0 | Unknown destination - zero weight
1 | 92.99 | 0 | Country group 0
1 | 92.98 | 2
100 | 93.97 | 2
30|120.00|1
0| 1.23 | 3
1 / 1.24 / 3
2 , 3.45 , 3 / CG3

```

# Does this support multiple rates per weight/country combination? 

No. Use [Weight zone shipping for WooCommerce](https://wordpress.org/plugins/oik-weight-zone-shipping/).


## Screenshots 
1. Weight and Country shipping settings part one
2. Weight and Country shipping settings part two
3. WooCommerce Checkout shipping rate
4. Enable Shipping Debug Mode when modifying rates

## Upgrade Notice 
# 1.4.3 
This plugin is no longer supported. Please switch to [oik-weight-zone-shipping](https://wordpress.org/plugins/oik-weight-zone-shipping).

## Changelog 
# 1.4.3 
* Tested: With WordPress 6.4-RC1 and WordPress Multisite
* Tested: With WooCommerce 8.2.1
* Tested: With PHP 8.0, PHP 8.1 and PHP 8.2
* Tested: With PHPUnit 9.6
