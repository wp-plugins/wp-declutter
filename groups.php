<?php
/* 
included in the context of $this->settings_page

Group definitions: each group is an array of arrays. 
Each sub array has structure $key => ($desc, [$example], [$regex]). 
If $regex is supplied, $example MUST be supplied, even if empty

*/

$wp_headers = array(
	'X-Pingback' => array('X-Pingback header: a link to the Wordpress pingback handler. If you do not accept pingbacks on your site, you can remove this header.', 'X-Pingback: http://example.com/xmlrpc.php'),
	'ETag' => array('Etag: an entity tag header used to check whether a feed has changed since it was last accessed. Wordpress also sends a "Last-Modified" header which serves the same purpose, so sending both of them is unnecessary.', ''),
	'Pragma' => array('Pragma: a HTTP/1.0 header used for cache control, sent by Wordpress when caching is disallowed. It is superceded by the HTTP/1.1 Cache-Control directive, but some clients may still use it.', ''),
	'Expires' => array('Expires: a header used for cache control, sent by Wordpress when caching is disallowed. It is superfluous when the HTTP/1.1 Cache-Control directive is used, but some clients may still use it.', '')
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

$body_classes = array(
	'home' => array('home: applied to the front page.'),
	'blog' => array('blog: applied to the home page.'),
	'archive' => array('archive: applied to archives.'),
	'date' => array('date: applied to date archives.'),
	'search' => array('search: applied to search results.'),
	'paged' => array('paged: applied to paged views.'),
	'attachment' => array('attachment: applied to attachment pages.'),
	'error404' => array('error404: applied to 404 pages.'),
	'single' => array('single: applied to single views.'),
	'single-' => array('single-<code>type</code>: applied to single views, with the post type appended.', '<body class="single single-post ...', '/^single-(?!paged-)/'),
	'postid-' => array('postid-<code>ID</code>: applied to single views, with the ID of the post appended.', '<body class="single single-post postid-2 ...', '/^postid-/'),
	'attachment-' => array('attachmentid-<code>ID</code>: applied to attachment pages views, with the ID of the attachment appended.', '<body class="single single-post postid-5 attachmentid-5 ...'),
	'attachment-' => array('attachment-<code>MIME type</code>: applied to attachment pages views, with the MIME type of the attachment appended.', '<body class="single single-post postid-5 attachmentid-5 attachment image/png ...', '/^attachment-.+\/.+/'),
	'author' => array('author: applied to author archives.'),
	'author-name' => array('author-<code>name</code>: applied to author archives, with the name of the author appended.', '<body class="single author author-gandalf ...', '/^author-(?!paged-)/'),
	'category' => array('category: applied to category archives.'),
	'category-name' => array('category-<code>category</code>: applied to category archives, with the name of the category appended.', '', '/^category-(?!paged-)/'),
	'tag' => array('tag: applied to tag archives.'),
	'tag-name' => array('tag-<code>tag</code>: applied to tag archives, with the name of the tag appended.', '', '/^tag-(?!paged-)/'),
	'page' => array('page: applied to pages.'),
	'page-id-id' => array('page-id-<code>ID</code>: applied to pages, with the post ID appended.', '<body class="single single-page postid-6 page-id-6 ...', '/^page-id-/'),
	'post-parent' => array('post-parent: applied to posts which have child posts.'),
	'post-child' => array('post-child: applied to posts which have a parent post.'),
	'parent-pageid-' => array('parent-pageid-<code>ID</code>: applied to posts which have a parent post, with the ID of the parent post appended.', '<body class="single single-page post-child parent-pageid-17 ...'. '/^parent-pageid-/'),
	'page-template' => array('page-template: applied to pages which are using a page template.'),
	'page-template-name' => array('page-template-<code>name</code>: applied to pages which are using a page template, with the name of the template appended.', '', '/^page-template-/'),
	'search-results' => array('search-results: applied to search pages, when results have been found.'),
	'search-no-results' => array('search-no-results: applied to search pages, when no results have been found.'),
	'logged-in' => array('logged-in: applied when a user is logged in.'),
	'paged-N' => array('paged-<code>page number</code>: applied to paged views, with the page number appended.', '<body class="paged-2 ...', '/^paged-\d+/'),
	'single-paged-N' => array('single-paged-<code>page number</code>: applied to paged views for single items.', '', '/^single-paged-\d+/'),
	'page-paged-N' => array('page-paged-<code>page number</code>: applied to paged views for pages.', '', '/^page-paged-\d+/'),
	'category-paged-N' => array('category-paged-<code>page number</code>: applied to paged views for category archives.', '', '/^category-paged-\d+/'),
	'tag-paged-N' => array('tag-paged-<code>page number</code>: applied to paged views for tag archives.', '', '/^tag-paged-\d+/'),
	'date-paged-N' => array('date-paged-<code>page number</code>: applied to paged views for date archives.', '', '/^date-paged-\d+/'),
	'author-paged-N' => array('author-paged-<code>page number</code>: applied to paged views for author archives.', '', '/^author-paged-\d+/'),
	'search-paged-N' => array('search-paged-<code>page number</code>: applied to paged views for searches.', '', '/^search-paged-\d+/'),
);

$post_classes = array(
	'post-id' => array('post-<code>ID</code>: the ID of the post being displayed.', '<div class="post-1 ...', '/^post-\d+/'),
	'type' => array('type-<code>type</code>: type of the post being displayed, prepended with "type-". Wordpress also creates a class name without the "type-" prepended.', '', '/^type-/'),
	'sticky' => array('sticky: applied to sticky posts.'),
	'hentry' => array('hentry: applied for hAtom compliance.'),
	'category' => array('category-<code>category</code>: applied for each category under which the post is filed.', '<div class="category-uncategorized category-politics ...', '/^category-/'),
	'tag' => array('tag-<code>tag</code>: applied for each tag under which the post is filed.', '<div class="tag-poems tag-funny ...', '/^tag-/')
);

$comment_classes = array(
	'comment' => array('comment: applied to comments (as opposed to trackbacks, pingbacks etc).'),
	'trackback' => array('trackback: applied to trackbacks.'),
	'pingback' => array('pingback: applied to pingbacks.'),
	'byuser' => array('byuser: applied to comments from registered users.'),
	'comment-author' => array('comment-author-<code>name</code>: applied to comments from registered users, with their name appended.', '<li class="byuser comment-author-gandalf ...', '/^comment-author-/'),
	'bypostauthor' => array('bypostauthor: applied to comments by the post author.'),
	'odd' => array('odd: applied to every other (odd) comment.'),
	'alt' => array('alt: alias of "odd".'),
	'even' => array('even: applied to every other (even) comment.'),
	'thread-odd' => array('thread-odd: applied to every other (odd) comment in a threaded reply.'),
	'thread-alt' => array('thread-alt: alias of "thread-odd".'),
	'thread-even' => array('thread-even: applied to every other (even) comment in a threaded reply.'),
	'depth' => array('depth-<code>depth</code>: the depth of a reply. Will always be "depth-1" if you have disabled threaded comments.', '', '/^depth-\d+/'),
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