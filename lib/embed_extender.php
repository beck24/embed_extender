<?php

	function embed_extender_rewrite($hook, $entity_type, $returnvalue, $params){
		global $CONFIG;
		
		$view = $params['view'];
		$context = get_context();
		
		//Check where embed code - The wire
		$wire_show = get_plugin_setting('wire_show', 'embed_extender');		
		if($wire_show == 'yes'){
			$object_wire = 'object/thewire';
		}
		
		//Check where embed code - Blog posts
		$blog_show = get_plugin_setting('blog_show', 'embed_extender');		
		if($blog_show == 'yes'){
			$object_blog = 'object/blog';
		}
		
		//Check where embed code - Comments
		$comment_show = get_plugin_setting('comment_show', 'embed_extender');		
		if($comment_show == 'yes'){
			$object_comment = 'annotation/generic_comment';
		}
		
		//Check where embed code - Group topics
		$topicposts_show = get_plugin_setting('topicposts_show', 'embed_extender');		
		if($topicposts_show == 'yes'){
			$object_topicposts = 'forum/topicposts';
		}
		
		//Check where embed code - Messageboard
		$messageboard_show = get_plugin_setting('messageboard_show', 'embed_extender');		
		if($messageboard_show == 'yes'){
			$object_messageboard = 'messageboard/messageboard_content';
		}
		
		//Check where embed code - Pages
		$page_show = get_plugin_setting('page_show', 'embed_extender');		
		if($page_show == 'yes'){
			$object_page = 'pages/pageprofile';
		}
		
		$supportedViews = array($object_page
								, $object_blog
								, $object_comment
								, $object_topicposts
								, $object_messageboard
								, $object_wire);
		
		//echo $view . ' Inicio ';
		//echo 'Contexto: ' . get_context();
		
		if (($view) && (in_array($view, $supportedViews))){
			$returnvalue = embed_extender_parser(' ' . $returnvalue . ' ', $view, $context);
			
			return $returnvalue;
			//return $returnvalue . $view . ' Fim ';
		}
    }
	
	function embed_extender_parser($input, $view, $context)
	{		
		//Don't show vídeos in the The Wire widget due formatting issues.
		if ($context == 'profile' || $view == 'messageboard/messageboard_content'){
			$width = get_plugin_setting('widget_width', 'embed_extender');
			if (!isset($width) || !is_numeric($width) || $width < 0) {
				$width = 265; //Size for widgets and messageboard
			}
		}
		else{
			$width = get_plugin_setting('width', 'embed_extender');			
			if (!isset($width) || !is_numeric($width) || $width < 0) {
				$width = 400; //Size for content
			}
		}

		$patterns = array('#(((http://)?)|(^./))(((www.)?)|(^./))youtube\.com/watch[?]v=([^\[\]()<.,\s\n\t\r]+)(?![^<]*</a>)#i'
							,'/(http:\/\/)(www\.)?(vimeo\.com\/groups)(.*)(\/videos\/)([0-9]*)/'
							,'/(http:\/\/)(www\.)?(metacafe\.com\/watch\/)([0-9a-zA-Z_-]*)(\/[0-9a-zA-Z_-]*)(\/)/'
							,'/(http:\/\/)(www\.)?(vimeo.com\/)([0-9]*)/');
		
		$custom_provider = get_plugin_setting('custom_provider', 'embed_extender');		

		if($custom_provider == 'yes'){
			$customPatterns = return_custom_patterns();
		}
							
		$supportedViews = array('object/blog'
								, 'annotation/generic_comment'
								, 'forum/topicposts'
								, 'messageboard/messageboard_content'
								, 'widgets/messageboard/view'
								, 'pages/pageprofile'
								, 'object/thewire');

		// Forget it. Doesn't work yet.
		/*
		if ($view == 'river/object/blog/create') {
		//if ($view == 'annotation/generic_comment') {		
			//Ignore it. Doesn´t work yet
			$regexp = "/http:\/\/[a-z0-9A-Z.]+(?(?=[\/])(.*))/";
			
			foreach ($patterns as $pattern){
				if (preg_match_all($pattern, $input, $matches, PREG_SET_ORDER)){
					foreach($matches as $match){
						$input = str_replace($match[0], videoembed_create_embed_object($match[0], uniqid('embed_'), $width), $input);
					}
				}				
			}
		}
		*/
		
		if (($view) && (in_array($view, $supportedViews))){
			
			//Parses all links
			$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
			
			//Replace video providers with embebed content
			if(preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER)){
				foreach($matches as $match){
					foreach ($patterns as $pattern){
						if (preg_match($pattern, $match[2]) > 0){
							$input = str_replace($match[0], videoembed_create_embed_object($match[2], uniqid('embed_'), $width), $input);
						}				
					}
					
					if($custom_provider == 'yes'){
						foreach ($customPatterns as $pattern){
							if (strpos($match[2], 'yahoo.com') != false){
								echo('bbaabb'.preg_match($pattern, $match[2]));
							}
							if (preg_match($pattern, $match[2]) > 0){
								$input = str_replace($match[0], custom_videoembed_create_embed_object($match[2], uniqid('embed_'), $width), $input);
							}				
						}
					}
				}
			}
		}
		
		return $input;
	}
?>