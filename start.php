<?php

if (!function_exists('str_get_html')) {
	require_once(dirname(__FILE__) . '/lib/simple_html_dom.php');
}
require_once(dirname(__FILE__) . '/lib/functions.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');

elgg_register_event_handler('init','system','elggx_fivestar_init');

function elggx_fivestar_init() {
	elgg_extend_view('css/elgg', 'elggx_fivestar/css');
	elgg_extend_view('css/admin', 'elggx_fivestar/css');

	elgg_register_plugin_hook_handler('view', 'all', 'elggx_fivestar_view');

	elgg_register_action("elggx_fivestar/settings/save", dirname(__FILE__) . '/actions/elggx_fivestar/settings/save.php');
	elgg_register_action("elggx_fivestar/rate", dirname(__FILE__) . '/actions/elggx_fivestar/rate.php', 'logged_in');
	elgg_register_action("elggx_fivestar/reset", dirname(__FILE__) . '/actions/elggx_fivestar/reset.php', 'admin');
}
