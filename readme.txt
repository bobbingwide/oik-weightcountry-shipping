=== Weight/Country Shipping for WooCommerce ===
Contributors: bobbingwide, Andy_P, pozhonks
Donate link: http://www.oik-plugins.com/oik/oik-donate/
Tags: woocommerce, commerce, ecommerce, shipping, weight, country, shop
Requires at least: 3.7.1
Tested up to: 3.9-beta3
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds per-country and weight based shipping cost calculation method to your WooCommerce store.  

== Description ==

If your WooCommerce store needs to calculate shipping charges based on cart weight and country of delivery then this plugin is for you.

You can group countries that share same delivery costs (e.g. USA and Canada, European Union countries, etc.) or 
set the shipping costs on a per-country basis. 

= Features =

* Set multiple shipping rates based on cart weight and delivery country
* Group countries sharing same rates and set rates once for all of them
* Unlimited groups of countries
* Unlimited rates
* Works with WooCommmerce 2.0 and 2.1
* Works on WPMS

= Limitations =

* English language only
* Requires Countries to have been added to the Specific Countries list, if your Selling Location(s) option is to 'Sell to specific countries only'
  

= Documentation =

After installation activate this shipping method. 

Go to WooCommerce->Settings->Shipping select  Weight/Country Shipping and tick enable box.

Rates are set based on "Country Groups". 
Country Groups are groups of countries (or a single country) that share same delivery rates.

== Installation ==
1. Upload 'oik-weightcountry-shipping' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set your delivery rates in WooCommerce->Settings->Shipping using the Weight/Country tab


== Frequently Asked Questions ==
= Which version of WooCommerce does this work on? =
Tested with WooCommerce 2.0 and WooCommerce 2.1, up to WooCommerce 2.1.6.

= Will it use my settings for AWD-weightcountry-shipping? =
Yes. 

= How do I switch to oik-weightcountry-shipping? =

1. Install oik-weightcountry-shipping plugin
1. Activate oik-weightcountry-shipping
1. Deactivate AWD-weightcountry-shipping 

= How do I upgrade to WooCommerce 2.1.x? =

1. Backup your site
1. Switch to oik-weightcountry-shipping
1. Upgrade WooCommerce
1. Test
1. Take another backup
== Screenshots ==
1. Weight and Country shipping settings part one
2. Weight and Country shipping settings part two

== Upgrade Notice ==
= 1.0.4 =
Upgrade to resolve errors from missing bw_trace2() and bw_backtrace().
 
= 1.0.3 =
Required for WooCommerce 2.1 and above. Tested with WooCommerce 2.0 and WooCommerce 2.1, up to WooCommerce 2.1.6.

= 1.0.2b =
Required for WooCommerce 2.1 and above. 

== Changelog ==
= 1.0.4 =
* Fixed: Removed calls to trace functions: bw_trace2() and bw_backtrace().

= 1.0.3 =
* New: New version for delivery from oik-plugins.co.uk
* Changed: Removed some links.
* Changed: Fixed some PHP Notify messages.

= 1.0.2b =
* Version created by pozhonks.
* See [New plugin for testing WooCommerce 2.1](http://wordpress.org/support/topic/new-plugin-for-testing-for-woocommerce-21) 

= 1.0.1 =
* Fixed a bug causing tax on shipping not being calculated
* Fixed a bug causing delivery being displayed as free when a country was in allowed countries list, but no rate was specified for this country.
* Fixed a bug causing delivery method being displayed to the user when no rate was specified for selected country of delivery
* Fixed some typos
* Changed some option labels to make them more clear

= 1.0 =
* First release

== Alternatives ==

= Non-working solutions =

As of 27th March 2014 the status of the AWD Weight/Country Shipping plugin was still showing as UNFIXED. 

http://www.andyswebdesign.ie/blog/free-woocommerce-weight-and-country-based-shipping-extension-plugin/


Another version, modified by Mantish to make it US State based, doesn't work with WooCommerce 2.1 either. 

https://gist.github.com/Mantish/5658280

= from WordPress.org =


The oik-weightcountry-shipping plugin was developed from changes by pozhonks that were pasted into

http://wordpress.org/support/topic/new-plugin-for-testing-for-woocommerce-21

An alternative plugin, which also takes dimensions and postcode/state into account, is available at:

http://wordpress.org/plugins/woocommerce-apg-weight-and-postcodestatecountry-shipping/

= Official WooCommerce extensions =
These WooCommerce extensions may satisfy your requirements.

* http://www.woothemes.com/products/table-rate-shipping-2/
* http://www.woothemes.com/products/royal-mail/
* http://www.woothemes.com/products/per-product-shipping/

= Other Premium plugins =
Or you may find other Premium plugins

* http://bolderelements.net/plugins/table-rate-shipping-for-woocommerce/


