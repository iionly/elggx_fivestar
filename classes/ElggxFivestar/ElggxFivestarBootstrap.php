<?php

namespace ElggxFivestar;

use Elgg\DefaultPluginBootstrap;

class ElggxFivestarBootstrap extends DefaultPluginBootstrap {

	public function init() {
		elgg_register_plugin_hook_handler('view', 'all', 'elggx_fivestar_view');
	}

	public function activate() {
		// Upgrade settings
		$oldversion = elgg_get_plugin_setting('version', 'elggx_fivestar');
		$new_version = '3.3.0';

		if (version_compare($new_version, $old_version, '!=')) {
			// Set new version
			elgg_set_plugin_setting('version', $new_version, 'elggx_fivestar');
		}
	}
}
