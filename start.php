<?php
function embed_extender_init()
{
	global $CONFIG;
	
	if (is_plugin_enabled('embedvideo')){
		include_once $CONFIG->pluginspath . 'embedvideo/lib/embedvideo.php';
		//die('embed');
	}
	else {
		include_once $CONFIG->pluginspath . 'embed_extender/lib/embedvideo.php';
		//die('extender');
	}
	
	include_once $CONFIG->pluginspath . 'embed_extender/lib/custom.php';
	include_once $CONFIG->pluginspath . 'embed_extender/lib/embed_extender.php';

	//register_plugin_hook('display', 'view', 'embed_extender_rewrite');
	//register_plugin_hook('view', 'all', 'embed_extender_rewrite');
	
	//Check where embed code - The wire
	$wire_show = elgg_get_plugin_setting('wire_show', 'embed_extender');		
	if($wire_show == 'yes'){
		register_plugin_hook('view', 'object/thewire', 'embed_extender_rewrite');
	}
	
	//Check where embed code - Blog posts
	$blog_show = elgg_get_plugin_setting('blog_show', 'embed_extender');		
	if($blog_show == 'yes'){
		register_plugin_hook('view', 'object/blog', 'embed_extender_rewrite');
	}
	
	//Check where embed code - Comments
	$comment_show = elgg_get_plugin_setting('comment_show', 'embed_extender');		
	if($comment_show == 'yes'){
		register_plugin_hook('view', 'annotation/generic_comment', 'embed_extender_rewrite');
		register_plugin_hook('view', 'annotation/default', 'embed_extender_rewrite');
	}
	
	//Check where embed code - Group topics
	$topicposts_show = elgg_get_plugin_setting('topicposts_show', 'embed_extender');		
	if($topicposts_show == 'yes'){
		register_plugin_hook('view', 'object/groupforumtopic', 'embed_extender_rewrite');
	}
	
	//Check where embed code - Messageboard
	$messageboard_show = elgg_get_plugin_setting('messageboard_show', 'embed_extender');
	if($messageboard_show == 'yes'){
		register_plugin_hook('view', 'annotation/default', 'embed_extender_rewrite');
	}

	//Check where embed code - Pages
	$page_show = elgg_get_plugin_setting('page_show', 'embed_extender');		
	if($page_show == 'yes'){
		register_plugin_hook('view', 'object/page_top', 'embed_extender_rewrite');
	}
	
	//Check where embed code - Pages
	$page_show = elgg_get_plugin_setting('bookmark_show', 'embed_extender');		
	if($page_show == 'yes'){
		register_plugin_hook('view', 'object/bookmarks', 'embed_extender_rewrite');
	}
	elgg_extend_view('css','embed_extender/css');

}

register_elgg_event_handler('init', 'system', 'embed_extender_init');