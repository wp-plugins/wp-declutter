<?php
/*
Plugin Name: Declutter Wordpress
Plugin URI: http://rayofsolaris.net/blog/plugins/declutter-wordpress/
Description: A plugin to declutter wordpress of many of the default headers, tags and classes that it inserts into posts, pages and feeds.
Version: 1.0
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
		$defaults = array('wp_head', 'template_redirect', 'wp_headers', 'feed', 'post_classes', 'comment_classes');
		foreach($defaults as $d) if( !isset($options[$d]) ) $options[$d] = array();	// empty array
		update_option('wp_declutter_options', $options);
	}
	
	function pre_template_filter() {
		add_action('template_redirect', array(&$this, 'template_filter') );	// stuff to do after template redirect	
		$this->options = get_option('wp_declutter_options');
		
		$unsets = '';
		foreach( $this->options['wp_headers'] as $h ) $unsets .= 'unset($headers["'.$h.'"]); ';
		
		if($unsets) add_filter('wp_headers', create_function('$headers', $unsets.' return $headers;'));
				
		$priorities = array('wp_shortlink_header' => 11);
		foreach( $this->options['template_redirect'] as $func ) {
			$priority = isset($priorities[$func]) ? $priorities[$func] : 10;
			remove_action('template_redirect', $func, $priority);
		}
	}
	
	function template_filter() {		
		// wp_head
		$priorities = array('feed_links' => 2, 'feed_links_extra' => 3);
		foreach( $this->options['wp_head'] as $func) {
			$priority = isset($priorities[$func]) ? $priorities[$func] : 10;
			remove_action('wp_head', $func, $priority);
		}
		
		// feed
		if( in_array('the_generator', $this->options['feed']) ) {
			foreach( array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $hook ) remove_action($hook, 'the_generator');
		}
		
		// post classes
		if($this->options['post_classes']) add_filter('post_class', array(&$this, 'filter_post_classes'));
		
		// post classes
		if($this->options['comment_classes']) add_filter('comment_class', array(&$this, 'filter_comment_classes'));
	}
	
	function filter_post_classes($classes){
		$filters = array();
		foreach( $this->options['post_classes'] as $item ) {
			if( in_array($item, array('post', 'type', 'category', 'tag') ) ) $filters[] = "/^$item-/";
			if( in_array($item, array('sticky', 'hentry') ) ) $filters[] = "/^$item$/";
		}
		foreach($classes as $key => $class) 
			foreach($filters as $filter) if( preg_match($filter, $class) ) unset($classes[$key]);
		return $classes;
	}
	
	function filter_comment_classes($classes){
		$filters = array();
		foreach( $this->options['comment_classes'] as $item ) {
			if('type' == $item) $filters[] = "/^(comment|trackback)$/";
			if( in_array($item, array('comment-author', 'depth') ) ) $filters[] = "/^$item-/"; 
			if( in_array($item, array('byuser', 'bypostauthor', 'odd', 'alt', 'even', 'thread-odd', 'thread-alt', 'thread-even') ) ) $filters[] = "/^$item$/"; 
		}
		foreach($classes as $key => $class) 
			foreach($filters as $filter) if( preg_match($filter, $class) ) unset($classes[$key]);
		return $classes;
	}
	
	function settings_menu() {
		add_submenu_page('options-general.php', 'Declutter Wordpress', 'Declutter Wordpress', 'manage_options', 'wp_declutter_settings', array(&$this, 'settings_page') );
	}
	
	function settings_page() {
		global $wp_version;
		
		$wp_headers = array(
			'X-Pingback' => array('X-Pingback header: a link to the Wordpress pingback handler. If you do not accept pingbacks on your site, you can remove this header.', 'X-Pingback: http://example.com/xmlrpc.php'),
			'ETag' => array('Etag: an entity tag header used to check whether a feed has changed since it was last accessed. Wordpress also sends a "Last-Modified" header which serves the same purpose, so sending both of them is unnecessary.', '')
		);
		
		$wp_head_actions = array(
			'feed_links' => array('Links to the blog and comments feeds.', '<link rel="alternate" type="application/rss+xml" title="Blog Feed" href="http://example.com/feed/" />'),
			'feed_links_extra' => array('Links to additional feeds (such as comments, category, tag, author and search feeds).', '<link rel="alternate" type="application/rss+xml" title="Uncategorized Category Feed" href="http://example.com/tag/foo/feed/" />'), 
			'rsd_link' => array('Link to the Really Simple Discovery service endpoint. This is an old publishing convention used to allow blogs to share information with other services, and is largely superceded by other methods now.', '<link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://example.com/xmlrpc.php?rsd" />'),
			'wlwmanifest_link' => array('Link to the Windows Live Writer manifest file. If you have never heard of Windows Live Writer, you definitely don\'t need this.', '<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://example.com/wp-includes/wlwmanifest.xml" />'),
			'index_rel_link' => array('Link to the front page of your site.', '<link rel="index" title="Blog" href="http://example.com" />'),
			'parent_post_rel_link' => array('Link to the parent of the current page, if it exists.', '<link rel="up" title="Parent post title" href="http://example.com/parent-post/" />'),
			'start_post_rel_link' => array('Link to the first post on your blog.', '<link rel="start" title="Hello world!" href="http://example.com/hello-world/" />'),
			'adjacent_posts_rel_link_wp_head' => array('Links to next/previous posts.', '<link rel="prev" title="Hello world!" href="http://example.com/hello-world/" />'),
			'wp_generator' => array('A "generator" meta tag containing information about the version of Wordpress you are running. Some say that exposing details about your software version is a security risk. Others say it isn\'t. Take your pick.', '<meta name="generator" content="WordPress 3.0" />'),
			'rel_canonical' => array('A canonical link for single posts/pages.', '<link rel="canonical" href="http://example.com/hello-world/" />'),
			'wp_shortlink_wp_head' => array('Shortlink: a short URL to the current page. If you use pretty permalinks, this will be a query-string based url.', '<link rel="shortlink" href="http://example.com/?p=1" />'),
		);
		
		$template_redirect_actions = array(
			 'wp_shortlink_header' => array('Shortlink: in addition to putting a shortlink in the head of your HTML pages, Wordpress also sends an HTTP header with this information.', 'Link: <http://example.com/?p=1>; rel=shortlink')
		);
		
		$feed_actions = array(
			'the_generator' => array('A "generator" tag containing information about the version of Wordpress you are running.', '<generator>http://wordpress.org/?v=3.0</generator>')
		);
		
		$post_classes = array(
			'post' => array('post-<code>ID</code>: the ID of the post being displayed.', '<div class="post-1 ...'),
			'type' => array('type-<code>type</code>: type of the post being displayed, prepended with "type-". Wordpress also creates a class name without the "type-" prepended.', '<div class="type-attachment ...'),
			'sticky' => array('sticky: applied to sticky posts.', '<div class="sticky ...'),
			'hentry' => array('hentry: applied for hAtom compliance.', '<div class="hentry ...'),
			'category' => array('category-<code>category</code>: applied for each category under which the post is filed.', '<div class="category-uncategorized category-politics ...'),
			'tag' => array('tag-<code>tag</code>: applied for each tag under which the post is filed.', '<div class="tag-poems tag-funny ...')
		);
		
		$comment_classes = array(
			'type' => array('<code>type</code>: the type of the comment (e.g., comment, trackback)', ''),
			'byuser' => array('byuser: applied to comments from registered users.', ''),
			'comment-author' => array('comment-author-<code>name</code>: applied to comments from registered users, with their name appended.', '<li class="byuser comment-author-gandalf ...'),
			'bypostauthor' => array('bypostauthor: applied to comments by the post author.', ''),
			'odd' => array('odd: applied to every other (odd) comment.', ''),
			'alt' => array('alt: alias of "odd".', ''),
			'even' => array('even: applied to every other (even) comment.', ''),
			'thread-odd' => array('thread-odd: applied to every other (odd) comment in a threaded reply.', ''),
			'thread-alt' => array('thread-alt: alias of "thread-odd".', ''),
			'thread-even' => array('thread-even: applied to every other (even) comment in a threaded reply.', ''),
			'depth' => array('depth-<code>depth</code>: the depth of a reply. Will always be "depth-1" if you have disabled threaded comments.', ''),
		);
		
		// start out with 3.0 settings and work backwards.
		if( version_compare($wp_version, '3', '<' ) ) {
			// feed_links used to be feed_links_extra
			$wp_head_actions['feed_links_extra'] = $wp_head_actions['feed_links'];
			unset($wp_head_actions['feed_links']);
			
			// adjacent_posts function was changed
			$wp_head_actions['adjacent_posts_rel_link'] = $wp_head_actions['adjacent_posts_rel_link_wp_head'];
			unset($wp_head_actions['adjacent_posts_rel_link_wp_head']);
			
			// shortlinks don't exist
			unset($wp_head_actions['wp_shortlink_wp_head']);
			unset($template_redirect_actions['wp_shortlink_header']);
			
			// feed generator tags don't exist
			unset($feed_actions['the_generator']);
		}
		
		$this->option_groups = array(
			'wp_head' => $wp_head_actions,
			'wp_headers' => $wp_headers,
			'template_redirect' => $template_redirect_actions,
			'feed' => $feed_actions,
			'post_classes' => $post_classes,
			'comment_classes' => $comment_classes
		);
		
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
	
	Jump to a specific section: [<a href="#s_wp_head">The HTML &lt;head&gt; section</a>] [<a href="#s_feeds">Feeds</a>] [<a href="#s_http">HTTP Headers</a>] [<a href="#s_posts">Post classes</a>] [<a href="#s_comments">Comment classes</a>]</p>
	<h3 id="s_wp_head">The HTML &lt;head&gt; section</h3><p>Wordpress inserts the following optional tags into the head of your HTML pages. Most of this information is not visible to people visiting your site - it is intended for browsers and robots. Uncheck those items you wish to remove. Examples of each tag are provided below the descriptions.</p>
	<ul><?php $this->list_items('wp_head'); ?></ul>
	
	<h3 id="s_feeds">Feeds</h3><p>Wordpress inserts the following optional tags into feeds. Uncheck those items you wish to remove.</p>
	<ul><?php $this->list_items('feed'); ?></ul>
	
	<h3 id="s_http">HTTP Headers</h3><p>Wordpress sends some optional HTTP headers by default whenever a page is requested. Uncheck those you do not wish to be sent.</p>
	<ul><?php $this->list_items('wp_headers'); $this->list_items('template_redirect'); ?></ul>
	
	<h3 id="s_posts">Post classes</h3><p>Wordpress inserts various class names into post <code>div</code>s based on the properties of the post. This can sometimes lead to a long list of classes on each post, many of which may not be used for styling. Those which can be removed are listed blow. You can uncheck any you do not use.</p>
	<ul><?php $this->list_items('post_classes'); ?></ul>
	
	<h3 id="s_comments">Comment classes</h3><p>Wordpress also inserts various class names into comments. You can uncheck any you do not want.</p>
	<ul><?php $this->list_items('comment_classes'); ?></ul>
	
	<p class="submit"><input class="button-primary" type="submit" name="submit" value="Update settings" /></p>
	</form>
	</div>
<?php
	}
	
	private function list_items($group) {
		if(empty($this->option_groups[$group])) 
			echo '<li>Your version of Wordpress does not have any entries in this section.</li>';
		else 
			foreach($this->option_groups[$group] as $key => $info) {
				$desc = $info[0];
				$example = $info[1] ? '<p class="example"><code>'.htmlspecialchars($info[1]).'</code></p>' : '';
				$checked = in_array($key, $this->options[$group]) ? '' : "checked='checked'";
				echo "<li><input type='checkbox' name='{$group}__{$key}' $checked /> $desc $example</li>";
			}
	}
	
	private function update() {
		foreach($this->option_groups as $group => $items) {
			$unchecked = array();
			foreach( array_keys($items) as $item ) if( !isset($_POST["{$group}__{$item}"]) ) $unchecked[] = $item;
			$this->options[$group] = $unchecked;
		}
		update_option('wp_declutter_options', $this->options);
		echo '<div id="message" class="updated fade"><p>Options updated.</p></div>';
	}

} // class

// load
$wp_declutter = new wp_declutter();
?>