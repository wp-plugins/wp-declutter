=== Declutter WordPress ===
Contributors: solarissmoke
Tags: declutter, wp_head, generator, meta, shortlink, class, body
Requires at least: 3.6
Tested up to: 4.2
Stable tag: trunk

This plugin lets you remove some of the default headers, tags and classes that WordPress inserts into your template by default.

== Description ==

This plugin lets you remove some of the default headers, tags and classes that WordPress inserts into your template by default. I personally find that some of them are unnecessary and just waste bandwidth.

The plugin currently lets you configure the following:

* In the head of HTML and XML documents
	* Feed links (blog and comment feeds)
	* Extra feed links (category, tag, author, search feeds)
	* Really Simple Discovery and Windows Live Manifest endpoint links
	* Links to previous/next posts
	* Generator tag which reveals which version of WordPress you are using
	* Shortlink tag
* In HTTP response headers:
	* Shortlink header
	* Redundant Etag headers
	* X-Pingback header
* Remove HTML body classes that you do not need (there is a huge list of classes that WordPress automatically inserts into the body tag)
* Remove post classes that you do not need (e.g., `type-`, `category-`, `tag-`)
* Remove comment classes that you do not need (e.g., `odd`, `even`, `alt`, `byuser`, `bypostauthor`, `depth-N`)
* Remove menu item classes that you do not need (e.g., `menu-item`, `current-menu-item`, `current_page_ancestor`)

If you come across any bugs or have suggestions, please [contact me](http://rayofsolaris.net) or use the support forums. I can't fix it if I don't know it's broken!

== Changelog ==

= 1.6.3 =
* Bugfix: current-menu-ancestor and current-menu-parent were being wrongly hidden by another filter.

= 1.6.2 =
* Add CSRF validation to settings page.

= 1.6.1 =
* Remove options for start/parent/index rel links - these were dropped from WordPress.
* Minor usability improvements.

= 1.6 =
* Added the option to remove ID attributes from Menu items

= 1.5 =
* Added menu item class filtering

= 1.4 =
* Added version-specific filters for WordPress 3.1
* Modified upgrade routine to account for changes in upgrade procedure in WordPress 3.1
* Performance improvements for class filtering

= 1.3 =
* Added new class filters for classes introduced in WordPress 3.1 beta/RC

= 1.2 =
* Added the option to completely disable body, post and comment classes.
* Added new class filters for classes introduced in WordPress 3.1 alpha

= 1.1 =
* Added filtering for classes in the <body> tag
* Note: some previous options may be reset after upgrade, because of some changes to how the plugin works - so please go to the settings page to check them.

== Installation ==

1. Upload the wp-declutter folder to the `/wp-content/plugins/` directory (or use the WordPress auto-install feature)
2. Activate the plugin through the 'Plugins' page in WordPress
3. The settings for the plugin can be accessed from the Settings administration menu, under "Declutter WordPress".
