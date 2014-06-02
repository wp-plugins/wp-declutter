<?php
/*
Plugin Name: Declutter WordPress
Plugin URI: http://rayofsolaris.net/code/declutter-wordpress
Description: A plugin to declutter wordpress of many of the default headers, tags and classes that it inserts into posts, pages and feeds.
Version: 1.6.3
Author: Samir Shah
Author URI: http://rayofsolaris.net/
License: GPL2
*/

if(!defined('ABSPATH')) 
	exit;

class WP_Declutter {
	const db_version = 3;	// since 1.4
	private $options, $option_groups;
	
	function __construct(){
		add_action('plugins_loaded', array(&$this,'pre_template_filter') );
		
		if( is_admin() )
			add_action('admin_menu', array(&$this, 'settings_menu') );
	}
	
	private function load_options() {
		// load options, upgrading if necessary
		$options = get_option( 'wp_declutter_options', array() );
		if( !isset( $options['options_version'] ) || $options['options_version'] < self::db_version ) {
			$defaults = array( 'wp_headers', 'wp_head', 'template_redirect', 'feed', 'body_classes', 'post_classes', 'comment_classes', 'menu_classes', 'special', 'other' );
			foreach( $defaults as $d ) if( !isset( $options[$d] ) ) $options[$d] = array();	// empty array

			// delete old wp_head options
			foreach( array( 'index_rel_link', 'parent_post_rel_link', 'start_post_rel_link' ) as $old )
				unset( $options['wp_head'][$old] );

			$options['options_version'] = self::db_version;
			update_option( 'wp_declutter_options' , $options );
		}
		$this->options = $options;
	}
	
	function pre_template_filter(){
		$this->load_options();
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
		$this->remove_actions( 'wp_head', 'wp_head', array('feed_links' => 2, 'feed_links_extra' => 3) );
		
		// feed
		if( array_key_exists('the_generator', $this->options['feed']) ) {
			foreach( array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $hook ) remove_action($hook, 'the_generator');
		}
		
		// body, post, comment, menu classes
		add_filter('body_class', array(&$this, 'filter_body_classes'));
		add_filter('post_class', array(&$this, 'filter_post_classes'));
		add_filter('comment_class', array(&$this, 'filter_comment_classes'));
		add_filter('nav_menu_css_class', array(&$this, 'filter_menu_classes'));
		
		// other
		if( isset( $this->options['other']['menu_ids'] ) )
			add_filter( 'nav_menu_item_id', '__return_false' );
	}
	
	function filter_body_classes($classes){
		return $this->filter_classes($classes, 'body_classes');
	}
	function filter_post_classes($classes){
		return $this->filter_classes($classes, 'post_classes');
	}
	function filter_comment_classes($classes){
		return $this->filter_classes($classes, 'comment_classes');
	}
	function filter_menu_classes($classes){
		return $this->filter_classes($classes, 'menu_classes');
	}
	
	private function remove_actions($hook, $group, $priorities = '') {
		// remove all the actions given by the keys of an option group, for the hook specified
		// if the option is not set, then it will not exist in the this->options[group] array
		if(!$priorities) $priorities = array();
		foreach( array_keys($this->options[$group]) as $act ) {
			$priority = isset($priorities[$act]) ? $priorities[$act] : 10;
			remove_action($hook, $act, $priority);
		}
	}
	
	private function filter_classes($classes, $group){
		// remove everything?
		if( isset($this->options['special'][$group]) )
			return array();
			
		$filters = $this->options[$group];
		
		// otherwise, if options array is not empty...
		if( !empty( $filters ) ) foreach( $classes as $index => $class ){
			foreach( $filters as $key => $maybe_regex ) {
				$simple = empty( $maybe_regex );
				$regex = $simple ? '/^'.preg_quote($key).'$/' : $maybe_regex;	// simple match for key if no regex supplied
				if( @preg_match($regex, $class) ) {
					unset($classes[$index]);
					if( $simple )	// no need to check for this again
						unset( $filters[$key] );
					break;
				}
			}
		}
		return $classes;
	}
	
	function settings_menu() {
		add_submenu_page('options-general.php', 'Declutter WordPress', 'Declutter WordPress', 'manage_options', 'wp_declutter_settings', array(&$this, 'settings_page') );
	}
	
	function settings_page() {
		require(dirname(__FILE__).'/groups.php');
		if(isset($_POST['submit'])) {
			check_admin_referer( 'wp-declutter-admin' );
			$this->update();
		}
	?>
	<style>
	#wp-declutter-settings ul, .indent {padding-left: 1.5em}
	#wp-declutter-settings ul {font-size: 11px}
	.example {line-height: 50% !important}
	.special_note {color: brown}
	</style>
	<div class="wrap">
	<?php screen_icon('plugins');?>
	<h2>Declutter WordPress</h2>
	<p>WordPress comes with a bunch of default settings that insert various pieces of code into your site's pages. Some of these are optional (some might say unnecessary), and you can remove them if they are not used by your WordPress theme.</p>
	
	<form action="" method="post" id="wp-declutter-settings">
	<p id="declutter_select" style="display:none">Select a section to configure: <br />[<a id="show_wp_head" href="#">The HTML &lt;head&gt; section</a>] [<a id="show_feeds" href="#">Feeds</a>] [<a id="show_http" href="#">HTTP Headers</a>] [<a id="show_body" href="#">HTML &lt;body&gt;</a>] [<a id="show_posts" href="#">Posts</a>] [<a id="show_comments" href="#">Comments</a>] [<a id="show_menu" href="#">Menus</a>] [<a id="show_all" href="#"><strong>Show All</strong></a>]</p>
	
	<div class="declutter_group" id="s_wp_head">
	<h3>The HTML &lt;head&gt; section</h3><p>WordPress inserts the following optional tags into the head of your HTML pages. Most of this information is not visible to people visiting your site - it is intended for browsers and robots. Uncheck those items you wish to remove. Examples of each tag are provided below the descriptions.</p><ul><?php $this->list_items('wp_head'); ?></ul>
	</div>
	
	<div class="declutter_group" id="s_feeds">
	<h3>Feeds</h3><p>WordPress inserts the following optional tags into feeds. Uncheck those items you wish to remove.</p>
	<ul><?php $this->list_items('feed'); ?></ul>
	</div>
	
	<div class="declutter_group" id="s_http">
	<h3>HTTP Headers</h3><p>WordPress sends some optional HTTP headers by default whenever a page is requested. Uncheck those you do not wish to be sent.</p>
	<ul><?php $this->list_items('wp_headers'); $this->list_items('template_redirect'); ?></ul>
	</div>
	
	<div class="declutter_group" id="s_body">
	<h3>HTML &lt;body&gt; </h3><p>WordPress inserts a number of class names into the <code>&lt;body&gt;</code> tag.</p>
	<p><strong></strong></p>
	<p><input type="checkbox" name="special__body_classes" class="special_declutter_option" <?php if(isset($this->options['special']['body_classes'])) echo 'checked="checked"';?> /> Remove all classes from the <code>&lt;body&gt;</code> tag. <strong>Selecting this option will remove *all* classes, including those that are added by other plugins.</strong></p>
	<p id="body_classes_special_note" class="special_note" style="display:none">You have chosen to disable all classes in the <code>&lt;body&gt;</code> tag. Deselect the option above to filter classes individually.</p>
	<div id="body_classes">
		<p>Otherwise, <strong>deselect</strong> the individual items below that you do not want to appear.</p>
		<ul id="body_classes"><?php $this->list_items('body_classes'); ?></ul>
	</div>
	</div>
	
	<div class="declutter_group" id="s_posts">
	<h3>Posts</h3><p>WordPress inserts various class names into post <code>&lt;div&gt;</code> elements based on the properties of the post. This can sometimes lead to a long list of classes on each post, many of which may not be used for styling.</p>
	<p><input type="checkbox" name="special__post_classes" class="special_declutter_option" <?php if(isset($this->options['special']['post_classes'])) echo 'checked="checked"';?> /> Remove all classes from post <code>&lt;div&gt;</code> elements. <strong>Selecting this option will remove *all* classes, including those that are added by other plugins.</strong></p>
	<p id="post_classes_special_note" class="special_note" style="display:none">You have chosen to disable all classes in post <code>&lt;div&gt;</code> elements. Deselect the option above to filter classes individually.</p>
	<div id="post_classes">
		<p>Otherwise, <strong>deselect</strong> the individual items below that you do not want to appear.</p>
		<ul><?php $this->list_items('post_classes'); ?></ul>
	</div>
	</div>
	
	<div class="declutter_group" id="s_comments">
	<h3>Comments</h3><p>WordPress also inserts various class names into comments.</p>
	<p><input type="checkbox" name="special__comment_classes" class="special_declutter_option" <?php if(isset($this->options['special']['comment_classes'])) echo 'checked="checked"';?> /> Remove all classes from comment elements (normally <code>&lt;li&gt;</code> elements, but it depends on your theme). <strong>Selecting this option will remove *all* classes, including those that are added by other plugins.</strong></p>
	<p id="comment_classes_special_note" class="special_note" style="display:none">You have chosen to disable all classes in comment elements. Deselect the option above to filter classes individually.</p>
	<div id="comment_classes">
		<p>Otherwise, <strong>deselect</strong> the individual items below that you do not want to appear.</p>
		<ul><?php $this->list_items('comment_classes'); ?></ul>
	</div>
	</div>
	
	<div class="declutter_group" id="s_menu">
	<h3>Menus</h3><p>WordPress also inserts various class names into navigation menus.</p>
	<p><input type="checkbox" name="special__menu_classes" class="special_declutter_option" <?php if(isset($this->options['special']['menu_classes'])) echo 'checked="checked"';?> /> Remove all classes from menu items. <strong>Selecting this option will remove *all* classes, including those that are added by other plugins.</strong></p>
	<p id="menu_classes_special_note" class="special_note" style="display:none">You have chosen to disable all classes in menu. Deselect the option above to filter classes individually.</p>
	<div id="menu_classes">
		<p>Otherwise, <strong>deselect</strong> the individual items below that you do not want to appear.</p>
		<ul><?php $this->list_items('menu_classes'); ?></ul>
	</div>
	<h4>Other menu tweaks</h4>
	<p><label for="other__menu_ids"><input type="checkbox" id="other__menu_ids" name="other__menu_ids" <?php if(isset($this->options['other']['menu_ids'])) echo 'checked="checked"';?> /> Remove ID attributes from Menu items</label></p><p class="example"><code>&lt;li id="menu-item-1023" ...</code></p>
	</div>
	
	<input type="hidden" name="declutter_current_view" id="declutter_current_view" value="" />
	<?php wp_nonce_field( 'wp-declutter-admin' ); ?>
	<p class="submit"><input class="button-primary" type="submit" name="submit" value="Update settings" /></p>
	</form>
	</div>
	<script>
	jQuery(document).ready(function($){
		$('#declutter_select').show(); 
		$('#declutter_select > a').click(function(){
			var aid = $(this).attr('id');
			$('#declutter_current_view').val(aid);
			if(aid == 'show_all') $('.declutter_group').fadeIn('slow');
			else {
				$('.declutter_group').hide(); 
				$('#' + aid.replace('show_','s_') ).fadeIn('slow');
			}
		});
		<?php 
			$current = isset($_POST['declutter_current_view']) ? $_POST['declutter_current_view'] : false;
			if($current) echo "$('#$current').click();";
			else echo "$('.declutter_group').hide();";
		?>
		// Special option handling
		$(".special_declutter_option").change(function(){
			var i = $(this);
			var refto = i.attr("name").replace("special__", "");
			if( i.is(":checked") ){
				$("#" + refto).hide();
				$("#" + refto + "_special_note").show();
			}
			else {
				$("#" + refto).show();
				$("#" + refto + "_special_note").hide();
			}
		});
		$(".special_declutter_option").change();	// trigger the function
	});
	</script>
<?php
	}
	
	private function list_items($group) {
		if(empty($this->option_groups[$group])) 
			echo '<li>Your version of WordPress does not have any entries in this section.</li>';
		else {
			ksort($this->option_groups[$group]);	// sort by key for more sensible viewing
			foreach($this->option_groups[$group] as $key => $item) {
				$desc = $item['desc'];
				$example = isset($item['example']) ? '<p class="example"><code>'.htmlspecialchars($item['example']).'</code></p>' : '';
				$checked = isset($this->options[$group][$key]) ? '' : "checked='checked'";
				$id = $group . '__' . $key;
				echo "<li><label for='$id'><input type='checkbox' id='$id' name='$id' $checked /> $desc</label> $example</li>";
			}
		}
	}
	
	private function update() {
		// option groups, UNchecked means active
		foreach($this->option_groups as $group => $items) {
			$unchecked = array();
			foreach( $items as $key => $item ) if( !isset($_POST["{$group}__{$key}"]) ) {
				// if no regex is supplied, leave the value empty, otherwise an array(key => regex)
				$unchecked[$key] = isset($item['regex']) ? $item['regex'] : '';
			}
			$this->options[$group] = $unchecked;
		}
		
		// special options stored in $options['special'], checked means active
		foreach( array( 'body_classes', 'post_classes', 'comment_classes', 'menu_classes' ) as $opt ){
			if( isset($_POST["special__$opt"]) )
				$this->options['special'][$opt] = true;
			else
				unset( $this->options['special'][$opt] );
		}
		
		// some other tweaks
		foreach( array( 'menu_ids' ) as $opt ) {
			if( isset($_POST["other__$opt"]) )
				$this->options['other'][$opt] = true;
			else
				unset( $this->options['other'][$opt] );
		}

		update_option( 'wp_declutter_options', $this->options );
		echo '<div id="message" class="updated fade"><p>Options updated.</p></div>';
	}

} // class

new WP_Declutter();
