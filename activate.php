<?php
/**
 * Activate Elggx Fivestar plugin
 *
 */

// Upgrade settings
$oldversion = elgg_get_plugin_setting('version', 'elggx_fivestar');
$new_version = '2.0.0';

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

// On Elgg 2.0 we have the object/discussion view instead of the object/groupforumtopic view, so we need to update
if (version_compare('2.0.0', $old_version, '>')) {
	$updated_elggx_fivestar_views = array();
	$lines = explode("\n", elgg_get_plugin_setting('elggx_fivestar_view', 'elggx_fivestar'));
	foreach ($lines as $line) {
		if ($line == "elggx_fivestar_view=object/groupforumtopic, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />") {
			$line = "elggx_fivestar_view=object/discussion, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />";
		}
		$updated_elggx_fivestar_views[] = $line;
	}

	$elggx_fivestar_view = '';
	foreach ($updated_elggx_fivestar_views as $updated_elggx_fivestar_view) {
		$elggx_fivestar_view .= $updated_elggx_fivestar_view . "\n";
	}
	elgg_set_plugin_setting('elggx_fivestar_view', $elggx_fivestar_view, 'elggx_fivestar');
}

if (version_compare($new_version, $old_version, '!=')) {
	// Set new version
	elgg_set_plugin_setting('version', $new_version, 'elggx_fivestar');
}
