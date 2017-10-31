<?php
/**
 * Activate Elggx Fivestar plugin
 *
 */

// Upgrade settings
$oldversion = elgg_get_plugin_setting('version', 'elggx_fivestar');
$new_version = '2.3.3';

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
	$updated_elggx_fivestar_views = array_filter($updated_elggx_fivestar_views);
	$updated_elggx_fivestar_views = array_slice( $updated_elggx_fivestar_views, 0);
	if (is_array($updated_elggx_fivestar_views)) {
		$elggx_fivestar_view = implode("\n", $updated_elggx_fivestar_views);
	}
	elgg_set_plugin_setting('elggx_fivestar_view', $elggx_fivestar_view, 'elggx_fivestar');
}

// On Elgg 2.3 the former attribute_value of "elgg-subtext" used in several default fivestar views needs to be updated to
// "elgg-listing-summary-subtitle elgg-subtext"
if (version_compare('2.3.1', $old_version, '>')) {
	$updated_elggx_fivestar_views = array();
	$lines = explode("\n", elgg_get_plugin_setting('elggx_fivestar_view', 'elggx_fivestar'));
	foreach ($lines as $line) {
		switch ($line) {
			case "elggx_fivestar_view=object/blog, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/blog, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/file, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/file, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/bookmarks, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/bookmarks, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/page_top, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/page_top, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/thewire, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/thewire, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=group/default, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br>":
				$line = "elggx_fivestar_view=groups/profile/summary, tag=div, attribute=class, attribute_value=groups-stats, before_html=<br>";
				break;

			case "elggx_fivestar_view=object/discussion, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/discussion, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/album, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/album, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/image, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/image, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/izap_videos, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/izap_videos, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/event_calendar, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/event_calendar, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/news, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/news, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;

			case "elggx_fivestar_view=object/poll, tag=div, attribute=class, attribute_value=elgg-subtext, before_html=<br />":
				$line = "elggx_fivestar_view=object/poll, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />";
				break;
		}
		$updated_elggx_fivestar_views[] = $line;
	}

	$elggx_fivestar_view = '';
	$updated_elggx_fivestar_views = array_filter($updated_elggx_fivestar_views);
	$updated_elggx_fivestar_views = array_slice( $updated_elggx_fivestar_views, 0);
	if (is_array($updated_elggx_fivestar_views)) {
		$elggx_fivestar_view = implode("\n", $updated_elggx_fivestar_views);
	}
	elgg_set_plugin_setting('elggx_fivestar_view', $elggx_fivestar_view, 'elggx_fivestar');
}


if (version_compare($new_version, $old_version, '!=')) {
	// Set new version
	elgg_set_plugin_setting('version', $new_version, 'elggx_fivestar');
}
