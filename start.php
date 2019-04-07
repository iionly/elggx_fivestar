<?php

elgg_register_event_handler('init','system','elggx_fivestar_init');

function elggx_fivestar_init() {
	if (!function_exists('str_get_html')) {
		require_once __DIR__ . "/lib/simple_html_dom.php";
	}

	elgg_extend_view('css/elgg', 'elggx_fivestar/css');
	elgg_extend_view('css/admin', 'elggx_fivestar/css');

	elgg_register_plugin_hook_handler('view', 'all', 'elggx_fivestar_view');

	elgg_register_menu_item('page', [
		'name' => 'elggx_fivestar',
		'href' => "admin/administer_utilities/elggx_fivestar",
		'text' => elgg_echo("admin:administer_utilities:elggx_fivestar"),
		'context' => 'admin',
		'parent_name' => 'administer_utilities',
		'section' => 'administer'
	]);

}

/**
 * This method is called when the view plugin hook is triggered.
 * If a matching view config is found then the fivestar widget is
 * called.
 *
 * @param  integer  $hook The hook being called.
 * @param  integer  $type The type of entity you're being called on.
 * @param  string   $return The return value.
 * @param  array    $params An array of parameters for the current view
 * @return string   The html
 */
function elggx_fivestar_view($hook, $entity_type, $returnvalue, $params) {
	if (elgg_in_context('widgets')) {
		return $returnvalue;
	}
	$lines = explode("\n", elgg_get_plugin_setting('elggx_fivestar_view', 'elggx_fivestar'));
	foreach ($lines as $line) {
		$options = array();
		$parms = explode(",", $line);
		foreach ($parms as $parameter) {
			preg_match("/^(\S+)=(.*)$/", trim($parameter), $match);
			$options[$match[1]] = $match[2];
		}

		if ($options['elggx_fivestar_view'] == $params['view']) {
			list($status, $html) = elggx_fivestar_widget($returnvalue, $params, $options);
			if (!$status) {
				continue;
			} else {
				return($html);
			}
		}
	}
	return $returnvalue;
}

/**
 * Handles voting on an entity
 *
 * @param  integer  $guid  The entity guid being voted on
 * @param  integer  $vote The vote
 * @return string   A status message to be returned to the client
 */
function elggx_fivestar_vote($guid, $vote) {

	$entity = get_entity($guid);
	$annotation_owner = elgg_get_logged_in_user_guid();

	$msg = null;

	if ($annotation = elgg_get_annotations(array(
		'guid' => $entity->guid,
		'type' => $entity->type,
		'annotation_name' => 'fivestar',
		'annotation_owner_guid' => $annotation_owner,
		'limit' => 1
	))) {
		if ($vote == 0 && (int)elgg_get_plugin_setting('change_cancel', 'elggx_fivestar', 1)) {
			if (!elgg_trigger_plugin_hook('elggx_fivestar:cancel', 'all', array('entity' => $entity), false)) {
				elgg_delete_annotations(array('annotation_id' => $annotation[0]->id));
				$msg = elgg_echo('elggx_fivestar:deleted');
			}
		} else if ((int)elgg_get_plugin_setting('change_cancel', 'elggx_fivestar', 1)) {
			update_annotation($annotation[0]->id, 'fivestar', $vote, 'integer', $annotation_owner, 2);
			$msg = elgg_echo('elggx_fivestar:updated');
		} else {
			$msg = elgg_echo('elggx_fivestar:nodups');
		}
	} else if ($vote > 0) {
		if (!elgg_trigger_plugin_hook('elggx_fivestar:vote', 'all', array('entity' => $entity), false)) {
			$entity->annotate('fivestar', $vote, 2, $annotation_owner);
		}
	} else {
		$msg = elgg_echo('elggx_fivestar:novote');
	}

	elggx_fivestar_setRating($entity);

	return($msg);
}

/**
 * Set the current rating for an entity
 *
 * @param  object   $entity  The entity to set the rating on
 * @return array    Includes the current rating and number of votes
 */
function elggx_fivestar_setRating($entity) {

	$access = elgg_set_ignore_access(true);

	$rating = elggx_fivestar_getRating($entity->guid);
	$entity->elggx_fivestar_rating = $rating['rating'];

	elgg_set_ignore_access($access);

	return;
}

/**
 * Get an the current rating for an entity
 *
 * @param  integer  $guid  The entity guid being voted on
 * @return array    Includes the current rating and number of votes
 */
function elggx_fivestar_getRating($guid) {

	$rating = array('rating' => 0, 'votes' => 0);
	$entity = get_entity($guid);

	if (count($entity->getAnnotations(array('annotation_name' => 'fivestar', 'limit' => false)))) {
		$rating['rating'] = $entity->getAnnotationsAvg('fivestar');
		$rating['votes'] = count($entity->getAnnotations((array('annotation_name' => 'fivestar', 'limit' => false))));

		$modifier = 100 / (int)elgg_get_plugin_setting('stars', 'elggx_fivestar', 5);
		$rating['rating'] = round($rating['rating'] / $modifier, 1);
	}

	return($rating);
}

/**
 * Inserts the fivestar widget into the current view
 *
 * @param  string   $returnvalue  The original html
 * @param  array    $params  An array of parameters for the current view
 * @param  array    $guid  The fivestar view configuration
 * @return string   The original view or the view with the fivestar widget inserted
 */
function elggx_fivestar_widget($returnvalue, $params, $options) {

	$guid = $params['vars']['entity']->guid;

	if (!$guid) {
		return;
	}

	if (elgg_in_context('widgets')) {
		$widget = elgg_view("elggx_fivestar/voting", array('fivestar_guid' => $guid, 'min' => true));
	} else {
		$widget = elgg_view("elggx_fivestar/voting", array('fivestar_guid' => $guid));
	}

	// get the DOM
	$html = str_get_html($returnvalue);

	$match = 0;
	foreach ($html->find($options['tag']) as $element) {
		if ($element->{$options['attribute']} == $options['attribute_value']) {
			$element->innertext .= $options['before_html'] . $widget . $options['after_html'];
			$match = 1;
			break;
		}
	}

	$returnvalue = $html;
	return(array($match, $returnvalue));
}

/**
 * Checks to see if the current user has already voted on the entity
 *
 * @param  guid   The entity guid
 * @return bool   Returns true/false
 */
function elggx_fivestar_hasVoted($guid) {

	$entity = get_entity($guid);
	$annotation_owner = elgg_get_logged_in_user_guid();

	$annotation = elgg_get_annotations(array(
		'guids' => $entity->guid,
		'types' => $entity->type,
		'annotation_name' => 'fivestar',
		'annotation_owner_guid' => $annotation_owner,
		'limit' => 1
	));

	if (is_object($annotation[0])) {
		return(true);
	}

	return(false);
}

function elggx_fivestar_defaults() {

$elggx_fivestar_view = 'elggx_fivestar_view=object/blog, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=object/file, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=object/bookmarks, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=object/page_top, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=object/thewire, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=groups/profile/summary, tag=div, attribute=class, attribute_value=groups-stats, before_html=<br>
elggx_fivestar_view=object/discussion, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=icon/user/default, tag=div, attribute=class, attribute_value=elgg-avatar elgg-avatar-large, before_html=<br>
elggx_fivestar_view=object/album, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=object/image, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=object/izap_videos, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=object/event_calendar, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=object/news, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />
elggx_fivestar_view=object/poll, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext, before_html=<br />';

elgg_set_plugin_setting('elggx_fivestar_view', $elggx_fivestar_view, 'elggx_fivestar');
}
