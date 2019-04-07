<?php

/**
 * Save Elggx Fivestar settings
 *
 */

// Params array (text boxes and drop downs)
$params = get_input('params');
foreach ($params as $k => $v) {
	if (!elgg_set_plugin_setting($k, $v, 'elggx_fivestar')) {
		register_error(elgg_echo('plugins:settings:save:fail', array('elggx_fivestar')));
		forward(REFERER);
	}
}

$change_vote = (int)$params['change_vote'];
if ($change_vote == 0) {
	elgg_set_plugin_setting('change_cancel', 0, 'elggx_fivestar');
} else {
	elgg_set_plugin_setting('change_cancel', 1, 'elggx_fivestar');
}

$elggx_fivestar_view = '';

$values = get_input('elggx_fivestar_views');

$values = array_filter($values);
$values = array_slice( $values, 0);

if (is_array($values)) {
	$elggx_fivestar_view = implode("\n", $values);
}

elgg_set_plugin_setting('elggx_fivestar_view', $elggx_fivestar_view, 'elggx_fivestar');

system_message(elgg_echo('elggx_fivestar:settings:save:ok'));

forward(REFERER);
