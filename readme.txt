=== WC-AC Hook ===
Contributors: mtreherne
Tags: WooCommerce, ActiveCampaign
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=matt@sendmail.me.uk&currency_code=GBP&item_name=Donation+for+WC-AC+Hook
Requires at least: 4.1.1
Tested up to: 4.2.1
Stable tag: 1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrates WooCommerce with ActiveCampaign by adding or updating a contact on ActiveCampaign with specified tags, when an order is created.

== Description ==

Integrates WooCommerce with ActiveCampaign by adding or updating a contact on ActiveCampaign with **specified tags**, when an order is created on WooCommerce.

Using the plugin means that all of your shop customers will be automatically created as contacts on ActiveCampaign. They will have their first name, last name, email and phone number taken from their billing details on their order. You must specify (in the plugin settings) on which ActiveCampaign list contacts are added or updated.

You may **tag** all contacts created in this way with multiple tags e.g. you may want to track that the source is your WooCommerce shop and that an order has been created. It is also possible to add **tags based on each product item** on an order e.g. if you want to know exactly what items a customer has ordered or perhaps a type of item (by using the same tag for multiple products).

This enables you to use ActiveCampaign automations (or integration with other applications) based on shop orders and products.

If a customer already exists as a contact on ActiveCampaign their details will be updated (note that a new contact will have a status of active, but updates will retain the existing contact status for the ActiveCampaign list).

A WooCommerce system status log called `wc-ac-hook*.log` can be checked for errors.

== Installation ==

You must have WooCommerce installed (tested up to 2.3.8) and have an ActiveCampaign account to make use of this plugin.

1. For manual code install upload and extract the plugin to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Goto settings page for this plugin from the 'Plugins' menu or the 'WooCommerce > Settings > Integration' tab then:
4. Enter your Active Campaign URL and your Active Campaign API key;
5. Enter the ActiveCampaign list ID to which contacts are added or updated;
6. Enter the default tag(s) that are added to the contact.
7. If you wish to have tags associated with each product you must enter these on each products 'Advanced Data' fields.

If you deactivate the plugin all settings will be retained until you uninstall.

== Frequently Asked Questions ==

= When will a contact be created or updated on ActiveCampaign? =

By default only when the order status is changed to 'Completed'. Optionally you can change the settings so that it is done when an order is created (i.e. order has a status of 'Processing')

= How do I clear the debug log? =

Your site administrator can remove the file `/wp-content/uploads/wc-logs/wc-ac-hook*.log`

== Screenshots ==

1. Plugin settings page from 'Integration' tab on 'WooCommerce > Settings' menu
2. Advanced product data fields when editing 'Products'

== Changelog ==

= 1.1 =
* Added option to add/update contact when order has status of processing
	
= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.1 =
Option to add/update contact when order status is processing or completed