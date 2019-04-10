<?php

/**
 * Save Elggx Fivestar settings
 *
 */

$plugin = elgg_get_plugin_from_id('elggx_fivestar');

$plugin_name = $plugin->getDisplayName();

$result = false;

$params = (array) get_input('params');
foreach ($params as $k => $v) {
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_name]));
	}
}

$result = false;

$change_vote = (int) $params['change_vote'];
if ($change_vote == 0) {
	$result = $plugin->setSetting('change_cancel', '0');
} else {
	$result = $plugin->setSetting('change_cancel', '1');
}
if (!$result) {
	return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_name]));
}

$elggx_fivestar_view = '';
$values = get_input('elggx_fivestar_views');
$values = array_filter($values);
$values = array_slice($values, 0);

$result = false;

if (is_array($values)) {
	$elggx_fivestar_view = implode("\n", $values);
	$result = $plugin->setSetting('elggx_fivestar_view', $elggx_fivestar_view);
}
if (!$result) {
	return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_name]));
}

return elgg_ok_response('', elgg_echo('elggx_fivestar:settings:save:ok', [$plugin_name]));
