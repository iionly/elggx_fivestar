<?php
/**
 * Activate Elggx Fivestar plugin
 *
 */

// Upgrade settings
$oldversion = elgg_get_plugin_setting('version', 'elggx_fivestar');
$new_version = '3.0.1';

if (version_compare($new_version, $old_version, '!=')) {
	// Set new version
	elgg_set_plugin_setting('version', $new_version, 'elggx_fivestar');
}
