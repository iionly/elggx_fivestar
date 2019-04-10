<?php

/**
 * Save Elggx Fivestar settings
 *
 */

$plugin = elgg_get_plugin_from_id('elggx_fivestar');

$result = false;

$params = (array) get_input('params');
foreach ($params as $k => $v) {
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		register_error(elgg_echo('plugins:settings:save:fail', ['elggx_fivestar']));
		forward(REFERER);
		exit;
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
	register_error(elgg_echo('plugins:settings:save:fail', ['elggx_fivestar']));
	forward(REFERER);
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
	register_error(elgg_echo('plugins:settings:save:fail', ['elggx_fivestar']));
	forward(REFERER);
}

system_message(elgg_echo('elggx_fivestar:settings:save:ok'));
forward(REFERER);
