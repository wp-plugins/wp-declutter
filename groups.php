<?php
/* 
included in the context of $this->settings_page()

Group definitions: each group is an array of arrays. 
Each sub array has structure $key => (desc, [example], [regex]). 

*/

if(!defined('ABSPATH')) exit;

$wp_headers = array(
	'X-Pingback' => array(
		'desc' => 'X-Pingback header: a link to the WordPress pingback handler. If you do not accept pingbacks on your site, you can remove this header.',
		'example' => 'X-Pingback: http://example.com/xmlrpc.php'),
	'ETag' => array(
		'desc' => 'Etag: an entity tag header used to check whether a feed has changed since it was last accessed. WordPress also sends a "Last-Modified" header which serves the same purpose, so sending both of them is unnecessary.'),
	'Pragma' => array(
		'desc' => 'Pragma: a HTTP/1.0 header used for cache control, sent by WordPress when caching is disallowed. It is superceded by the HTTP/1.1 Cache-Control directive, but some clients may still use it.'),
	'Expires' => array(
		'desc' => 'Expires: a header used for cache control, sent by WordPress when caching is disallowed. It is superfluous when the HTTP/1.1 Cache-Control directive is used, but some clients may still use it.')
);

$wp_head = array(
	'feed_links' => array(
		'desc' => 'Links to the blog and comments feeds.',
		'example' => '<link rel="alternate" type="application/rss+xml" title="Blog Feed" href="http://example.com/feed/" />'),
	'feed_links_extra' => array(
		'desc' => 'Links to additional feeds (such as comments, category, tag, author and search feeds).', 
		'example' => '<link rel="alternate" type="application/rss+xml" title="Uncategorized Category Feed" href="http://example.com/tag/foo/feed/" />'), 
	'rsd_link' => array(
		'desc' => 'Link to the Really Simple Discovery service endpoint. This is an old publishing convention used to allow blogs to share information with other services, and is largely superceded by other methods now.', 
		'example' => '<link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://example.com/xmlrpc.php?rsd" />'),
	'wlwmanifest_link' => array(
		'desc' => 'Link to the Windows Live Writer manifest file. If you have never heard of Windows Live Writer, you definitely don\'t need this.', 
		'example' => '<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://example.com/wp-includes/wlwmanifest.xml" />'),
	'adjacent_posts_rel_link_wp_head' => array(
		'desc' => 'Links to next/previous posts.', 
		'example' => '<link rel="prev" title="Hello world!" href="http://example.com/hello-world/" />'),
	'wp_generator' => array(
		'desc' => 'A "generator" meta tag containing information about the version of WordPress you are running. Some say that exposing details about your software version is a security risk. Others say it isn\'t. Take your pick.', 
		'example' => '<meta name="generator" content="WordPress 3.0" />'),
	'rel_canonical' => array(
		'desc' => 'A canonical link for single posts/pages.', 
		'example' => '<link rel="canonical" href="http://example.com/hello-world/" />'),
	'wp_shortlink_wp_head' => array(
		'desc' => 'Shortlink: a short URL to the current page. If you use pretty permalinks, this will be a query-string based url.', 
		'example' => '<link rel="shortlink" href="http://example.com/?p=1" />'),
);

$template_redirect = array(
	 'wp_shortlink_header' => array(
		'desc' => 'Shortlink: in addition to putting a shortlink in the head of your HTML pages, WordPress also sends an HTTP header with this information.', 
		'example' => 'Link: <http://example.com/?p=1>; rel=shortlink')
);

$feed = array(
	'the_generator' => array(
		'desc' => 'A "generator" tag containing information about the version of WordPress you are running.', 
		'example' => '<generator>http://wordpress.org/?v=3.0</generator>')
);

$body_classes = array(
	'rtl' => array(
		'desc' => 'rtl: applied to pages whose text direction is declared as "left to right".'),
	'home' => array(
		'desc' => 'home: applied to the front page.'),
	'blog' => array(
		'desc' => 'blog: applied to the home page.'),
	'archive' => array(
		'desc' => 'archive: applied to archives.'),
	'date' => array(
		'desc' => 'date: applied to date archives.'),
	'search' => array(
		'desc' => 'search: applied to search results.'),
	'paged' => array(
		'desc' => 'paged: applied to paged views.'),
	'attachment' => array(
		'desc' => 'attachment: applied to attachment pages.'),
	'error404' => array(
		'desc' => 'error404: applied to 404 pages.'),
	'single' => array(
		'desc' => 'single: applied to single views.'),
	'single-' => array(
		'desc' => 'single-<code>type</code>: applied to single views, with the post type appended.', 
		'example' => '<body class="single single-post ...',
		'regex' => '/^single-(?!paged-)/'),
	'postid-' => array(
		'desc' => 'postid-<code>ID</code>: applied to single views, with the ID of the post appended.', 
		'example' => '<body class="single single-post postid-2 ...',
		'regex' => '/^postid-/'),
	'single-format-' => array(
		'desc' => 'single-format-<code>format</code>: applied to all posts, with the post format appended. The default is <code>single-format-standard</code>.',
		'regex' => '/^single-format-/'),
	'attachment-' => array(
		'desc' => 'attachmentid-<code>ID</code>: applied to attachment pages views, with the post ID appended.', 
		'example' => '<body class="single single-post postid-5 attachmentid-5 ...'),
	'attachment-' => array(
		'desc' => 'attachment-<code>MIME type</code>: applied to attachment pages views, with attachment MIME type appended.', 
		'example' => '<body class="single single-post postid-5 attachmentid-5 attachment image/png ...', 
		'regex' => '/^attachment-.+\/.+/'),
	'post-type-archive' => array(
		'desc' => 'post-type-archive: applied to archives of a particular post type.'),
	'post-type-archive-posttype' => array(
		'desc' => 'post-type-archive-<code>type</code>: applied to archives of a particular post type, with the post type appended.'),
	'author' => array(
		'desc' => 'author: applied to author archives.'),
	'author-name' => array(
		'desc' => 'author-<code>name</code>: applied to author archives, with the name of the author appended.', 
		'example' => '<body class="archive author author-gandalf ...',
		'regex' => '/^author-(?!paged-|\d+$)/'),
	'author-id' => array(
		'desc' => 'author-<code>author-id</code>: applied to author archives, with the database ID of the author appended.', 
		'example' => '<body class="archive author author-12 ...',
		'regex' => '/^author-\d+$/'),
	'category' => array(
		'desc' => 'category: applied to category archives.'),
	'category-name' => array(
		'desc' => 'category-<code>category</code>: applied to category archives, with the databse ID of the category appended.',
		'regex' => '/^category-(?!paged-|\d+$))/'),
	'category-id' => array(
		'desc' => 'category-<code>category-id</code>: applied to category archives, with the name of the category appended.',
		'regex' => '/^category-\d+$/'),
	'tag' => array(
		'desc' => 'tag: applied to tag archives.'),
	'tag-name' => array(
		'desc' => 'tag-<code>tag</code>: applied to tag archives, with the name of the tag appended.',  
		'regex' => '/^tag-(?!paged-|\d+$)/'),
	'tag-id' => array(
		'desc' => 'tag-<code>tag-id</code>: applied to tag archives, with the database ID of the tag appended.',
		'regex' => '/^tag-\d+$/'),
	'tax-name' => array(
		'desc' => 'tax-<code>taxonomy</code>: applied to taxonomy archives, with the taxonomy name appended.',  
		'regex' => '/^tax-(?!paged-)/'),
	'term-name' => array(
		'desc' => 'term-<code>term</code>: applied to taxonomy archives, with the name of the associated term appended.',  
		'regex' => '/^term-(?!paged-|\d+$)/'),
	'term-id' => array(
		'desc' => 'term-<code>term-id</code>: applied to taxonomy archives, with the database ID of the associated term appended.',
		'regex' => '/^term-\d+$/'),
	'page' => array(
		'desc' => 'page: applied to pages.'),
	'page-id-id' => array(
		'desc' => 'page-id-<code>ID</code>: applied to pages, with the post ID appended.', 
		'example' => '<body class="single single-page postid-6 page-id-6 ...',
		'regex' => '/^page-id-/'),
	'page-parent' => array(
		'desc' => 'page-parent: applied to pages which have child pages.'),
	'page-child' => array(
		'desc' => 'page-child: applied to pages which have a parent page.'),
	'parent-pageid-' => array(
		'desc' => 'parent-pageid-<code>ID</code>: applied to posts which have a parent post, with the ID of the parent post appended.', 
		'example' => '<body class="single single-page post-child parent-pageid-17 ...',
		'regex' => '/^parent-pageid-/'),
	'page-template' => array(
		'desc' => 'page-template: applied to pages which are using a page template.'),
	'page-template-name' => array(
		'desc' => 'page-template-<code>name</code>: applied to pages which are using a page template, with the name of the template appended.',
		'regex' => '/^page-template-/'),
	'search-results' => array(
		'desc' => 'search-results: applied to search pages, when results have been found.'),
	'search-no-results' => array(
		'desc' => 'search-no-results: applied to search pages, when no results have been found.'),
	'logged-in' => array(
		'desc' => 'logged-in: applied when a user is logged in.'),
	'admin-bar' => array(
		'desc' => 'admin-bar: applied when the admin bar is showing.'),
	'paged-N' => array(
		'desc' => 'paged-<code>page number</code>: applied to paged views, with the page number appended.', 
		'example' => '<body class="paged-2 ...',
		'regex' => '/^paged-\d+/'),
	'single-paged-N' => array(
		'desc' => 'single-paged-<code>page number</code>: applied to paged views for single items.', 
		'regex' => '/^single-paged-\d+/'),
	'page-paged-N' => array(
		'desc' => 'page-paged-<code>page number</code>: applied to paged views for pages.',
		'regex' => '/^page-paged-\d+/'),
	'category-paged-N' => array(
		'desc' => 'category-paged-<code>page number</code>: applied to paged views for category archives.', 
		'regex' => '/^category-paged-\d+/'),
	'tag-paged-N' => array(
		'desc' => 'tag-paged-<code>page number</code>: applied to paged views for tag archives.',
		'regex' => '/^tag-paged-\d+/'),
	'date-paged-N' => array(
		'desc' => 'date-paged-<code>page number</code>: applied to paged views for date archives.',
		'regex' => '/^date-paged-\d+/'),
	'author-paged-N' => array(
		'desc' => 'author-paged-<code>page number</code>: applied to paged views for author archives.',
		'regex' => '/^author-paged-\d+/'),
	'search-paged-N' => array(
		'desc' => 'search-paged-<code>page number</code>: applied to paged views for searches.',
		'regex' => '/^search-paged-\d+/'),
	'post-type-paged-N' => array(
		'desc' => 'post-type-paged-<code>page number</code>: applied to paged views for post type archives.',
		'regex' => '/^post-type-paged-\d+/')
);

$post_classes = array(
	'post-id' => array(
		'desc' => 'post-<code>ID</code>: the ID of the post being displayed.', 
		'example' => '<div class="post-1 ...',
		'regex' => '/^post-\d+$/'),
	'type' => array(
		'desc' => 'type-<code>type</code>: type of the post being displayed, prepended with "type-". WordPress also creates a class name without the "type-" prepended.',
		'regex' => '/^type-/'),
	'status' => array(
		'desc' => 'status-<code>status</code>: the status of the post being displayed (e.g., "publish", "draft", "private").', 
		'example' => '<div class="status-private status-publish ...',
		'regex' => '/^status-/'),
	'format-' => array(
		'desc' => 'format-<code>format</code>: applied to all posts, with the post format appended. The default is <code>format-standard</code>.',
		'regex' => '/^format-/'),
	'post-password-required' => array(
		'desc' => 'post-password-required: applied to password-protected posts.'),
	'sticky' => array(
		'desc' => 'sticky: applied to sticky posts.'),
	'hentry' => array(
		'desc' => 'hentry: applied for hAtom compliance.'),
	'category' => array(
		'desc' => 'category-<code>category</code>: applied for each category under which the post is filed.', 
		'example' => '<div class="category-uncategorized category-politics ...',
		'regex' => '/^category-/'),
	'tag' => array(
		'desc' => 'tag-<code>tag</code>: applied for each tag under which the post is filed.', 
		'example' => '<div class="tag-poems tag-funny ...',
		'regex' => '/^tag-/')
);

$comment_classes = array(
	'comment' => array(
		'desc' => 'comment: applied to comments (as opposed to trackbacks, pingbacks etc).'),
	'trackback' => array(
		'desc' => 'trackback: applied to trackbacks.'),
	'pingback' => array(
		'desc' => 'pingback: applied to pingbacks.'),
	'byuser' => array(
		'desc' => 'byuser: applied to comments from registered users.'),
	'comment-author' => array(
		'desc' => 'comment-author-<code>name</code>: applied to comments from registered users.', 
		'example' => '<li class="byuser comment-author-gandalf ...',
		'regex' => '/^comment-author-/'),
	'bypostauthor' => array(
		'desc' => 'bypostauthor: applied to comments by the post author.'),
	'odd' => array(
		'desc' => 'odd: applied to every other (odd) comment.'),
	'alt' => array(
		'desc' => 'alt: alias of "odd".'),
	'even' => array(
		'desc' => 'even: applied to every other (even) comment.'),
	'thread-odd' => array(
		'desc' => 'thread-odd: applied to every other (odd) comment in a threaded reply.'),
	'thread-alt' => array(
		'desc' => 'thread-alt: alias of "thread-odd".'),
	'thread-even' => array(
		'desc' => 'thread-even: applied to every other (even) comment in a threaded reply.'),
	'depth' => array(
		'desc' => 'depth-<code>depth</code>: the depth of a reply. Will always be "depth-1" if you have disabled threaded comments.',
		'regex' => '/^depth-\d+/'),
);

$menu_classes = array(
	'menu-item' => array(
		'desc' => 'menu-item: applied to each item in a custom navigation menu.'),
	'menu-item-type-' => array(
		'desc' => 'menu-item-type-<code>type</code>: applied to each menu item in a custom navigation menu, where the type is the type of the object being referenced (e.g., taxonomy, post_type).',
		'example' => '<li class="menu-item menu-item-type-taxonomy ...',
		'regex' => '/^menu-item-type-/'),
	'menu-item-object-' => array(
		'desc' => 'menu-item-object-<code>object</code>: applied to each menu item in a custom navigation menu, where the object is the object being used to populate the item (e.g., category, tag, post, page).',
		'example' => '<li class="menu-item menu-item-object-post ...',
		'regex' => '/^menu-item-object-/'),
	'current-menu-item' => array(
		'desc' => 'current-menu-item: applied to a menu item if it corresponds to the currently queried object.'),
	'page_item' => array(
		'desc' => 'page_item: applied to menu items for pages.'),
	'page-item-' => array(
		'desc' => 'page-item-<code>ID</code>: applied to menu items for pages, with the ID of the page appended.',
		'regex' => '/^page-item-\d+/'),
	'current_page_item' => array(
		'desc' => 'current_page_item: applied to a menu item if it is for a page, and that page is being viewed.'),
	'menu-item-home' => array(
		'desc' => 'menu-item-home: applied to the menu item that corresponds to the site home page.'),
	'current_page_parent' => array(
		'desc' => 'current_page_parent: applied to the parent menu item of the currently queried object, if it is a page. Also applied to a static home page if one is used.'),
	'current_page_ancestor' => array(
		'desc' => 'current_page_ancestor: applied to ancestor menu items of the currently queried object, if it is a page.'),
	'current-object-ancestor' => array(
		'desc' => 'current-<code>object</code>-ancestor: applied to menu items for ancestors of the queried object, for heirarchical post types and taxonomies.',
		'example' => '<li class="menu-item current-page-ancestor ...',
		'regex' => '/^current-(?!menu)\w+-ancestor$/'),
	'current-menu-ancestor' => array(
		'desc' => 'current-menu-ancestor: applied to ancestors of the current menu item.'),
	'current-menu-parent' => array(
		'desc' => 'current-menu-parent: applied to the immediate parent of the current menu item.'),
	'current-object-parent' => array(
		'desc' => 'current-<code>object</code>-parent: applied to menu items for ancestors of the queried object, for heirarchical post types and taxonomies.',
		'example' => '<li class="menu-item current-category-parent ...',
		'regex' => '/^current-(?!menu)\w+-parent$/'),
	'menu-item-id' => array(
		'desc' => 'menu-item-<code>ID</code>: applied to menu items with the ID of the corresponding object appended.',
		'example' => '<li class="menu-item current_page_item menu-item-1023 ...',
		'regex' => '/^menu-item-\d+$/'),	
);

// start out with 3.0 settings and work backwards.
global $wp_version;
if( version_compare($wp_version, '3', '<' ) ) {
	// feed_links used to be feed_links_extra
	$wp_head['feed_links_extra'] = $wp_head['feed_links'];
	unset( $wp_head['feed_links'] );
	
	// adjacent_posts function was changed
	$wp_head['adjacent_posts_rel_link'] = $wp_head['adjacent_posts_rel_link_wp_head'];
	unset( $wp_head['adjacent_posts_rel_link_wp_head'] );
	
	// shortlinks don't exist
	unset( $wp_head['wp_shortlink_wp_head'] );
	unset( $template_redirect['wp_shortlink_header'] );
	
	// feed generator tags don't exist
	unset( $feed['the_generator'] );
	
	// no menus
	$menu_classes = array();
}
if( version_compare($wp_version, '3.1', '<' ) ) {
	// new post classes
	foreach( array( 'format-', 'post-password-required' ) as $v )
		unset( $post_classes[$v] );
	
	// new body classes
	foreach( array( 'single-format-', 'post-type-archive', 'post-type-archive-posttype', 'author-id', 'category-id', 'tag-id', 'tax-name', 'term-name', 'term-id', 'admin-bar', 'post-type-paged-N' ) as $v )
		unset( $body_classes[$v] );
}

$this->option_groups = compact('wp_headers', 'wp_head', 'template_redirect', 'feed', 'body_classes', 'post_classes', 'comment_classes', 'menu_classes');
?>