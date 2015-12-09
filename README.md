# Weight/Country Shipping for WooCommerce 
* Contributors: bobbingwide, vsgloik, Andy_P, pozhonks
* Donate link: http://www.oik-plugins.com/oik/oik-donate/
* Tags: woocommerce, commerce, ecommerce, shipping, weight, country, shop
* Requires at least: 3.9
* Tested up to: 4.4
* Stable tag: 1.3.2
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds per-country and weight based shipping cost calculation method to your WooCommerce store.

## Description 

If your WooCommerce store needs to calculate shipping charges based on cart weight and country of delivery then this plugin is for you.

You can group countries that share same delivery costs (e.g. USA and Canada, European Union countries, etc.) or
set the shipping costs on a per-country basis.

# Features 
New in version 1.3.1

* Support for maximum cart weight
* French language version

New in version 1.2

* Ability to set zero cost shipping for certain weight ranges
* No longer chooses the lowest rate from a higher weight range

New in version 1.1

* Enabled for localization in your language.
* Tested with WooCommerce 2.3.8

New in version 1.0.9

* Set the Shipping Method Title per Rate

New in version 1.0.5

* Supports carts with zero weight
* Supports default rates when Country is not listed in a country group

In version 1.0.4

* Set multiple shipping rates based on cart weight and delivery country
* Group countries sharing same rates and set rates once for all of them
* Unlimited groups of countries
* Unlimited rates
* Works with WooCommmerce 2.0 and 2.1
* Works on WPMS

# Known Limitations 

* Requires Countries to have been added to the Specific Countries list, if your Selling Location(s) option is to 'Sell to specific countries only'
* Calculates charges based on the total cart weight; it doesn't pay any attention to shipping classes or product categories
* Doesn't support different charges to state or region
* Doesn't take into account dimensions
* Only returns one shipping rate per weight/country group combination.
* For multiple shipping rates upgrade to [Multi rate weight/country shipping for WooCommerce plugin](http://www.oik-plugins.com/oik-plugins/oik-weightcountry-shipping-pro/)

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
# Which version of WooCommerce does this work on? 

Tested with WooCommerce 2.0 up to WooCommerce 2.4.11

# What is the separator for the shipping rate table? 

You can use vertical bars, forward slashes or commas.
Blanks around values will be ignored.
`
0|9.99|0
1 | 92.99 | 0
1 | 92.98 | 2
100 | 93.97 | 2
30|120.00|1
0| 1.23 | 3
1 / 1.24 / 3
2 , 3.45 , 3

`

# How do I set the Method Title? 
If you want to use a different title per rate then add this for each rate where the Method Title should be different from the default.
`
0|9.99|0 | Unknown destination - zero weight
1 | 92.99 | 0 | Country group 0
1 | 92.98 | 2
100 | 93.97 | 2
30|120.00|1
0| 1.23 | 3
1 / 1.24 / 3
2 , 3.45 , 3 / CG3

`

# Does this support multiple rates per weight/country combination? 

If you have a requirement to offer multiple shipping rates per weight / country combination then we recommend the
[Multi rate weight/country shipping for WooCommerce plugin](http://www.oik-plugins.com/oik-plugins/oik-weightcountry-shipping-pro/)

The Multi rate version supports multiple rates per weight/country combination
e.g.
`
100 | 1.23 | 1 | UK standard
100 | 2.34 | 1 | UK premium
`


# Are there any other FAQs? 

Yes. See [oik weight/country shipping FAQS](http://www.oik-plugins.com/wordpress-plugins-from-oik-plugins/oik-weightcountry-shipping-faqs)
and [Multi rate weight/country shipping for WooCommerce FAQ's](http://www.oik-plugins.com/oik-plugins/oik-weightcountry-shipping-pro/?oik-tab=faq)

## Screenshots 
1. Weight and Country shipping settings part one
2. Weight and Country shipping settings part two
3. WooCommerce Checkout shipping rate
4. Enable Shipping Debug Mode when modifying rates

## Upgrade Notice 
# 1.3.2 
Contains a fix for Issue #6. Now tested with WooCommerce 2.4.11

# 1.3.1 
Upgrade to v1.3.1 for support for maximum cart weight or to use the French language version.

# 1.3 
Upgrade to v1.3 if you get Notices due to missing Method title in rate line

# 1.2 
Upgrade if v1.1 caused Fatal errors or if you want to set zero cost shipping for certain weight bands

# 1.1 
First version ready for localization. Sample bbboing language supported ( locale bb_BB )

# 1.0.9 
Prototype version tested with WordPress 4.1 and WooCommerce 2.2.10

# 1.0.8 
Tested with WordPress 4.1 and WooCommerce up to 2.2.10

# 1.0.7 
Tested with WordPress 4.0 and WooCommerce 2.2.4

# 1.0.6 
Quick fix for sites where oik base plugin is loaded after oik-weightcountry-shipping

# 1.0.5 
Tested with WooCommerce 2.1.7 and WordPress 3.9-RC1

# 1.0.4 
Upgrade to resolve errors from missing bw_trace2() and bw_backtrace().

# 1.0.3 
Required for WooCommerce 2.1 and above. Tested with WooCommerce 2.0 and WooCommerce 2.1, up to WooCommerce 2.1.6.

# 1.0.2b 
Required for WooCommerce 2.1 and above.

## Changelog 
# 1.3.2 
* Fixed: Issue #6 - Correct dummy bw_trace2() function
* Tested: With WordPress 4.4 and WooCommerce 2.4.11

# 1.3.1 
* Added: Support for maximum cart weight
* Added: French language version. Props to R&eacute;my Perona and WooCommerce
* Changed: Now using semantic version numbering

# 1.3 
* Fixed: Notice: Undefined offset from line 331

# 1.2 
* Fixed: Responds to 'woocommerce_init' rather than 'init'
* Changed: Changed the pick_smallest_rate method to pick the rate for the given weight band.

# 1.1 
* Added: Responds to 'init' to load language versions and initialise the logic
* Added: Sample language files for the bbboing language ( locale bb_BB )

# 1.0.9 
* Changed: Supports optional setting of the Method Title on each line in the shipping rate table

# 1.0.8 
* Changed: Supports separator characters of |=vertical bar, /=forward slash and/or ,=comma in the shipping rates table
* Tested: With WordPress 4.1 and WooCommerce 2.2.10

# 1.0.7 
* Changed: Support blanks in the shipping rates table
* Changed: Set text domain to "oik-weightcountry-shipping" instead of "woocommerce". No language versions yet.
* Changed: Added some docblocks for API documentation
* Tested: With WordPress 4.0 and WooCommmerce 2.2.4

# 1.0.6 
* Fixed: Remove dummy bw_trace2() and bw_backtrace() functions and calls.

# 1.0.5 
* Changed: Calculates shipping charges for zero weight carts
* Changed: Calculates charges for "any other country" using country group 0
* Fixed: Should no longer produce Warning messages

# 1.0.4 
* Fixed: Removed calls to trace functions: bw_trace2() and bw_backtrace().

# 1.0.3 
* New: New version for delivery from oik-plugins.co.uk
* Changed: Removed some links.
* Changed: Fixed some PHP Notify messages.

# 1.0.2b 
* Version created by pozhonks.
* See [New plugin for testing WooCommerce 2.1](http://wordpress.org/support/topic/new-plugin-for-testing-for-woocommerce-21)

# 1.0.1 
* Fixed a bug causing tax on shipping not being calculated
* Fixed a bug causing delivery being displayed as free when a country was in allowed countries list, but no rate was specified for this country.
* Fixed a bug causing delivery method being displayed to the user when no rate was specified for selected country of delivery
* Fixed some typos
* Changed some option labels to make them more clear

# 1.0 
* First release


