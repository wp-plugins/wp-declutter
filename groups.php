<?php
/* 
included in the context of $this->settings_page

Group definitions: each group is an array of arrays. 
Each sub array has structure $key => (desc, [example], [regex]). 

*/

$wp_headers = array(
	'X-Pingback' => array(
		'desc' => 'X-Pingback header: a link to the Wordpress pingback handler. If you do not accept pingbacks on your site, you can remove this header.',
		'example' => 'X-Pingback: http://example.com/xmlrpc.php'),
	'ETag' => array(
		'desc' => 'Etag: an entity tag header used to check whether a feed has changed since it was last accessed. Wordpress also sends a "Last-Modified" header which serves the same purpose, so sending both of them is unnecessary.'),
	'Pragma' => array(
		'desc' => 'Pragma: a HTTP/1.0 header used for cache control, sent by Wordpress when caching is disallowed. It is superceded by the HTTP/1.1 Cache-Control directive, but some clients may still use it.'),
	'Expires' => array(
		'desc' => 'Expires: a header used for cache control, sent by Wordpress when caching is disallowed. It is superfluous when the HTTP/1.1 Cache-Control directive is used, but some clients may still use it.')
);

$wp_head_actions = array(
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
	'index_rel_link' => array(
		'desc' => 'Link to the front page of your site.', 
		'example' => '<link rel="index" title="Blog" href="http://example.com" />'),
	'parent_post_rel_link' => array(
		'desc' => 'Link to the parent of the current page, if it exists.', 
		'example' => '<link rel="up" title="Parent post title" href="http://example.com/parent-post/" />'),
	'start_post_rel_link' => array(
		'desc' => 'Link to the first post on your blog.', 
		'example' => '<link rel="start" title="Hello world!" href="http://example.com/hello-world/" />'),
	'adjacent_posts_rel_link_wp_head' => array(
		'desc' => 'Links to next/previous posts.', 
		'example' => '<link rel="prev" title="Hello world!" href="http://example.com/hello-world/" />'),
	'wp_generator' => array(
		'desc' => 'A "generator" meta tag containing information about the version of Wordpress you are running. Some say that exposing details about your software version is a security risk. Others say it isn\'t. Take your pick.', 
		'example' => '<meta name="generator" content="WordPress 3.0" />'),
	'rel_canonical' => array(
		'desc' => 'A canonical link for single posts/pages.', 
		'example' => '<link rel="canonical" href="http://example.com/hello-world/" />'),
	'wp_shortlink_wp_head' => array(
		'desc' => 'Shortlink: a short URL to the current page. If you use pretty permalinks, this will be a query-string based url.', 
		'example' => '<link rel="shortlink" href="http://example.com/?p=1" />'),
);

$template_redirect_actions = array(
	 'wp_shortlink_header' => array(
		'desc' => 'Shortlink: in addition to putting a shortlink in the head of your HTML pages, Wordpress also sends an HTTP header with this information.', 
		'example' => 'Link: <http://example.com/?p=1>; rel=shortlink')
);

$feed_actions = array(
	'the_generator' => array(
		'desc' => 'A "generator" tag containing information about the version of Wordpress you are running.', 
		'example' => '<generator>http://wordpress.org/?v=3.0</generator>')
);

$body_classes = array(
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
	'attachment-' => array(
		'desc' => 'attachmentid-<code>ID</code>: applied to attachment pages views, with the post ID appended.', 
		'example' => '<body class="single single-post postid-5 attachmentid-5 ...'),
	'attachment-' => array(
		'desc' => 'attachment-<code>MIME type</code>: applied to attachment pages views, with attachment MIME type appended.', 
		'example' => '<body class="single single-post postid-5 attachmentid-5 attachment image/png ...', 
		'regex' => '/^attachment-.+\/.+/'),
	'author' => array(
		'desc' => 'author: applied to author archives.'),
	'author-name' => array(
		'desc' => 'author-<code>name</code>: applied to author archives, with the name of the author appended.', 
		'example' => '<body class="single author author-gandalf ...',
		'regex' => '/^author-(?!paged-)/'),
	'category' => array(
		'desc' => 'category: applied to category archives.'),
	'category-name' => array(
		'desc' => 'category-<code>category</code>: applied to category archives, with the name of the category appended.',
		'regex' => '/^category-(?!paged-)/'),
	'tag' => array(
		'desc' => 'tag: applied to tag archives.'),
	'tag-name' => array(
		'desc' => 'tag-<code>tag</code>: applied to tag archives, with the name of the tag appended.',  
		'regex' => '/^tag-(?!paged-)/'),
	'page' => array(
		'desc' => 'page: applied to pages.'),
	'page-id-id' => array(
		'desc' => 'page-id-<code>ID</code>: applied to pages, with the post ID appended.', 
		'example' => '<body class="single single-page postid-6 page-id-6 ...',
		'regex' => '/^page-id-/'),
	'post-parent' => array(
		'desc' => 'post-parent: applied to posts which have child posts.'),
	'post-child' => array(
		'desc' => 'post-child: applied to posts which have a parent post.'),
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
);

$post_classes = array(
	'post-id' => array(
		'desc' => 'post-<code>ID</code>: the ID of the post being displayed.', 
		'example' => '<div class="post-1 ...',
		'regex' => '/^post-\d+/'),
	'type' => array(
		'desc' => 'type-<code>type</code>: type of the post being displayed, prepended with "type-". Wordpress also creates a class name without the "type-" prepended.',
		'regex' => '/^type-/'),
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

// start out with 3.0 settings and work backwards.
global $wp_version;
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
	'body_classes' => $body_classes,
	'post_classes' => $post_classes,
	'comment_classes' => $comment_classes
);
?>