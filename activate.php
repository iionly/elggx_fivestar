<?php
/**
 * Activate Elggx Fivestar plugin
 *
 */

// Upgrade settings
$oldversion = elgg_get_plugin_setting('version', 'elggx_fivestar');
$new_version = '1.10.10';

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
	// Set new version
	elgg_set_plugin_setting('version', $new_version, 'elggx_fivestar');
} else if (version_compare($new_version, $old_version, '!=')) {
	// Set new version
	elgg_set_plugin_setting('version', $new_version, 'elggx_fivestar');
}
