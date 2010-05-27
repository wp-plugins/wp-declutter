=== Declutter Wordpress ===
Contributors: solarissmoke
Tags: declutter, wp_head, generator, meta, shortlink
Requires at least: 2.9
Tested up to: 3.0
Stable tag: trunk

This plugin lets you remove some of the default headers, tags and classes that Wordpress inserts into your template by default.

== Description ==

This plugin lets you remove some of the default headers, tags and classes that Wordpress inserts into your template by default. I personally find that some of them are unnecessary and just waste bandwidth.

The plugin currently lets you configure the following:

* In the head of HTML and XML documents
	* Feed links (blog and comment feeds)
	* Extra feed links (category, tag, author, search feeds)
	* Really Simple Discovery and Windows Live Manifest endpoint links (most people don't even know what these are, let alone use them)
	* Links to the index page, the first post, previous/next posts, parent post
	* Generator tag which reveals which version of Wordpress you are using
	* Shortlink tag
* In HTTP response headers:
	* Shortlink header
	* Redundant Etag headers
	* X-Pingback header
* In posts:
	* Remove post classes that you do not need (e.g., `type-`, `category-`, `tag-`)
* In comments:
	* Remove comment classes that you do not need (e.g., `odd`, `even`, `alt`, `byuser`, `bypostauthor`, `depth-N`)

If you have any problems, please [post a ticket here](http://wordpress.org/tags/wp-declutter).

**This plugin requires PHP version 5. Please do not try installing it in a PHP 4 environment.**

== Frequently Asked Questions ==

= Does this plugin work with PHP 4? =

No. Please use it only with PHP 5.0 and above. Please don't email me about PHP errors when you try to install the plugin on a PHP 4 platform.

== Installation ==

1. Upload the wp-declutter folder to the `/wp-content/plugins/` directory (or use the Wordpress auto-install feature)
2. Activate the plugin through the 'Plugins' page in WordPress
3. The settings for the plugin can be accessed from the Settings administration menu, under "Declutter Wordpress".