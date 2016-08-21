<?php
/**
 * Activate Elggx Fivestar plugin
 *
 */

// Upgrade settings
$oldversion = elgg_get_plugin_setting('version', 'elggx_fivestar');
$new_version = '1.10.12';

// Check if we need to run an upgrade
if (!$oldversion) {
	$plugin = elgg_get_plugin_from_id('elggx_fivestar');
	// Old versions of Elggx Fivestar used an array named view to save the Fivestar views which
	// results in an issue with the plugin to be shown in the plugin list in the admin section.
	// New name is elggx_fivestar_view and the old array needs to get deleted from the database.
	$view = $plugin->view;
	if (is_string($view)) {
		$plugin->__unset('view');
		$plugin->save();
	}
}

// Set defaults if not yet set
if (!(int)elgg_get_plugin_setting('stars', 'elggx_fivestar')) {
	elgg_set_plugin_setting('stars', '5', 'elggx_fivestar');
}
$change_vote = (int)elgg_get_plugin_setting('change_vote', 'elggx_fivestar');
if ($change_vote == 0) {
	elgg_set_plugin_setting('change_cancel', 0, 'elggx_fivestar');
} else {
	elgg_set_plugin_setting('change_cancel', 1, 'elggx_fivestar');
}
$values = elgg_get_plugin_setting('elggx_fivestar_view', 'elggx_fivestar');
if ($values) {
	$elggx_fivestar_view = '';
	$values = explode("\n", $values);
	$values = array_filter($values);
	$values = array_slice( $values, 0);
	if (is_array($values)) {
		$elggx_fivestar_view = implode("\n", $values);
	}
	elgg_set_plugin_setting('elggx_fivestar_view', $elggx_fivestar_view, 'elggx_fivestar');
} else {
	elggx_fivestar_defaults();
}

// Set new version
if (version_compare($new_version, $old_version, '!=')) {
	// Set new version
	elgg_set_plugin_setting('version', $new_version, 'elggx_fivestar');
}