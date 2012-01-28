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
	register_plugin_hook('view', 'all', 'embed_extender_rewrite');
	elgg_extend_view('css','embed_extender/css');

}

register_elgg_event_handler('init', 'system', 'embed_extender_init');