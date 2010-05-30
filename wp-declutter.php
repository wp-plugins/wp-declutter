<?php
/*
Plugin Name: Declutter Wordpress
Plugin URI: http://rayofsolaris.net/blog/plugins/declutter-wordpress/
Description: A plugin to declutter wordpress of many of the default headers, tags and classes that it inserts into posts, pages and feeds.
Version: 1.1
Author: Samir Shah
Author URI: http://rayofsolaris.net/
*/

/* Copyright 2010 Samir Shah (email : samir[at]rayofsolaris.net)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

*/

class wp_declutter {
	private $options;
	
	function __construct() {
		add_action('activate_wp-declutter/wp-declutter.php', array(&$this, 'activate') );
		add_action('admin_menu', array(&$this, 'settings_menu') );
		add_action('plugins_loaded', array(&$this,'pre_template_filter') );
	}
	
	function activate() {
		$options = get_option('wp_declutter_options', array() );
		$defaults = array('wp_head', 'template_redirect', 'wp_headers', 'feed', 'body_classes', 'post_classes', 'comment_classes');
		foreach($defaults as $d) if( !isset($options[$d]) ) $options[$d] = array();	// empty array
		update_option('wp_declutter_options', $options);
	}
	
	function pre_template_filter() {
		$this->options = get_option('wp_declutter_options');
		add_action('template_redirect', array(&$this, 'template_filter') );	// stuff to do after template redirect	
		
		// headers
		if($this->options['wp_headers']) add_filter('wp_headers', array(&$this, 'filter_wp_headers') );
		// shortlink
		$this->remove_actions('template_redirect', 'template_redirect', array('wp_shortlink_header' => 11) );
	}
	
	function filter_wp_headers($headers) {
		// returns only those keys which aren't in the options array
		return array_diff_key($headers, $this->options['wp_headers']);
	}
	
	function template_filter() {
		// wp_head
		$this->remove_actions('wp_head', 'wp_head', array('feed_links' => 2, 'feed_links_extra' => 3) );
		
		// feed
		if( array_key_exists('the_generator', $this->options['feed']) ) {
			foreach( array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $hook ) remove_action($hook, 'the_generator');
		}
		
		// body, post, comment classes
		if($this->options['body_classes']) add_filter('body_class', array(&$this, 'filter_body_classes'));
		if($this->options['post_classes']) add_filter('post_class', array(&$this, 'filter_post_classes'));
		if($this->options['comment_classes']) add_filter('comment_class', array(&$this, 'filter_comment_classes'));
	}
	
	function filter_body_classes($classes){return $this->filter_classes($classes, 'body_classes'); }
	function filter_post_classes($classes){return $this->filter_classes($classes, 'post_classes'); }
	function filter_comment_classes($classes){return $this->filter_classes($classes, 'comment_classes'); }
	
	private function remove_actions($hook, $group, $priorities = '') {
		// remove all the actions given by the keys of an option group, for the hook specified
		if(!$priorities) $priorities = array();
		foreach( array_keys($this->options[$group]) as $act ) {
			$priority = isset($priorities[$act]) ? $priorities[$act] : 10;
			remove_action($hook, $act, $priority);
		}
	}
	
	private function filter_classes($classes, $group){
		foreach( $classes as $index => $class )
			foreach( $this->options[$group] as $key => $maybe_regex ) {
				$regex = empty($maybe_regex) ? '/^'.preg_quote($key).'$/' : $maybe_regex;	// simple match for key if no regex supplied
				if( @preg_match($regex, $class) ) {
					unset($classes[$index]);
					break;
				}
			}
		return $classes;
	}
	
	function settings_menu() {
		add_submenu_page('options-general.php', 'Declutter Wordpress', 'Declutter Wordpress', 'manage_options', 'wp_declutter_settings', array(&$this, 'settings_page') );
	}
	
	function settings_page() {
		require(dirname(__FILE__).'/groups.php');
		if(isset($_POST['submit'])) 
			$this->update();
	?>
	<style>
	#wp-declutter-settings ul, .indent {padding-left: 1.5em}
	#wp-declutter-settings ul {font-size: 11px}
	.example {line-height: 50% !important}
	</style>
	<div class="wrap">
	<h2>Declutter Wordpress</h2>
	<p>Wordpress comes with a bunch of default settings that insert various pieces of code into your site's pages. Some of these are optional (some might say unnecessary), and you can remove them if you wish.</p>
	
	<form action="" method="post" id="wp-declutter-settings">
	<p id="declutter_select" style="display:none">Select a section to configure: <br />[<a id="show_wp_head" href="#">The HTML &lt;head&gt; section</a>] [<a id="show_feeds" href="#">Feeds</a>] [<a id="show_http" href="#">HTTP Headers</a>] [<a id="show_body" href="#">HTML &lt;body&gt;</a>] [<a id="show_posts" href="#">Posts</a>] [<a id="show_comments" href="#">Comments</a>] [<a id="show_all" href="#"><strong>Show All</strong></a>]</p>
	
	<div class="declutter_group" id="s_wp_head">
	<h3>The HTML &lt;head&gt; section</h3><p>Wordpress inserts the following optional tags into the head of your HTML pages. Most of this information is not visible to people visiting your site - it is intended for browsers and robots. Uncheck those items you wish to remove. Examples of each tag are provided below the descriptions.</p><ul><?php $this->list_items('wp_head'); ?></ul>
	</div>
	
	<div class="declutter_group" id="s_feeds">
	<h3>Feeds</h3><p>Wordpress inserts the following optional tags into feeds. Uncheck those items you wish to remove.</p>
	<ul><?php $this->list_items('feed'); ?></ul>
	</div>
	
	<div class="declutter_group" id="s_http">
	<h3>HTTP Headers</h3><p>Wordpress sends some optional HTTP headers by default whenever a page is requested. Uncheck those you do not wish to be sent.</p>
	<ul><?php $this->list_items('wp_headers'); $this->list_items('template_redirect'); ?></ul>
	</div>
	
	<div class="declutter_group" id="s_body">
	<h3>HTML &lt;body&gt; </h3><p>Wordpress inserts the following class names into the &lt;body&gt; tag. As you can see the list is positively huge. You can uncheck any you do not use.</p><ul><?php $this->list_items('body_classes'); ?></ul>
	</div>
	
	<div class="declutter_group" id="s_posts">
	<h3>Posts</h3><p>Wordpress inserts various class names into post <code>div</code>s based on the properties of the post. This can sometimes lead to a long list of classes on each post, many of which may not be used for styling. Those which can be removed are listed blow. You can uncheck any you do not use.</p><ul><?php $this->list_items('post_classes'); ?></ul>
	</div>
	
	<div class="declutter_group" id="s_comments">
	<h3>Comments</h3><p>Wordpress also inserts various class names into comments. You can uncheck any you do not want.</p><ul><?php $this->list_items('comment_classes'); ?></ul>
	</div>
	
	<input type="hidden" name="declutter_current_view" id="declutter_current_view" value="" />
	<p class="submit"><input class="button-primary" type="submit" name="submit" value="Update settings" /></p>
	</form>
	</div>
	<script>
	jQuery(document).ready(function(){
		jQuery('#declutter_select').show(); 
		jQuery('#declutter_select > a').click(function(){
			var aid = jQuery(this).attr('id');
			jQuery('#declutter_current_view').val(aid);
			if(aid == 'show_all') jQuery('.declutter_group').fadeIn('slow');
			else {
				jQuery('.declutter_group').hide(); 
				jQuery('#' + aid.replace('show_','s_') ).fadeIn('slow');
			}
		});
		<?php 
			$current = isset($_POST['declutter_current_view']) ? $_POST['declutter_current_view'] : false;
			if($current) echo "jQuery('#$current').click();";
			else echo "jQuery('.declutter_group').hide();";
		?>
	});
	</script>
<?php
	}
	
	private function list_items($group) {
		if(empty($this->option_groups[$group])) 
			echo '<li>Your version of Wordpress does not have any entries in this section.</li>';
		else {
			ksort($this->option_groups[$group]);	// sort by key for more sensible viewing
			foreach($this->option_groups[$group] as $key => $item) {
				$desc = $item[0];
				$example = isset($item[1]) && $item[1] ? '<p class="example"><code>'.htmlspecialchars($item[1]).'</code></p>' : '';
				$checked = array_key_exists($key, $this->options[$group]) ? '' : "checked='checked'";
				echo "<li><input type='checkbox' name='{$group}__{$key}' $checked /> $desc $example</li>";
			}
		}
	}
	
	private function update() {
		foreach($this->option_groups as $group => $items) {
			$unchecked = array();
			foreach( $items as $key => $item ) if( !isset($_POST["{$group}__{$key}"]) ) {
				// if no regex is supplied, leave the value empty, otherwise an array(key => regex)
				$unchecked[$key] = isset($item[2]) ? $item[2] : '';
			}
			$this->options[$group] = $unchecked;
		}
		update_option('wp_declutter_options', $this->options);
		echo '<div id="message" class="updated fade"><p>Options updated.</p></div>';
	}

} // class

// load
$wp_declutter = new wp_declutter();
?>