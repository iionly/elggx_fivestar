<?php

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

	if ($annotation = elgg_get_annotations([
		'guid' => $entity->guid,
		'type' => $entity->type,
		'annotation_name' => 'fivestar',
		'annotation_owner_guid' => $annotation_owner,
		'limit' => 1,
	])) {
		if ($vote == 0 && (int) elgg_get_plugin_setting('change_cancel', 'elggx_fivestar', '1')) {
			if (!elgg_trigger_plugin_hook('elggx_fivestar:cancel', 'all', ['entity' => $entity], false)) {
				elgg_delete_annotations(['annotation_id' => $annotation[0]->id]);
				$msg = elgg_echo('elggx_fivestar:deleted');
			}
		} else if ((int) elgg_get_plugin_setting('change_cancel', 'elggx_fivestar', '1')) {
			update_annotation($annotation[0]->id, 'fivestar', $vote, 'integer', $annotation_owner, 2);
			$msg = elgg_echo('elggx_fivestar:updated');
		} else {
			$msg = elgg_echo('elggx_fivestar:nodups');
		}
	} else if ($vote > 0) {
		if (!elgg_trigger_plugin_hook('elggx_fivestar:vote', 'all', ['entity' => $entity], false)) {
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

	$rating = ['rating' => 0, 'votes' => 0];
	$entity = get_entity($guid);

	if (count($entity->getAnnotations(['annotation_name' => 'fivestar', 'limit' => false]))) {
		$rating['rating'] = $entity->getAnnotationsAvg('fivestar');
		$rating['votes'] = count($entity->getAnnotations(['annotation_name' => 'fivestar', 'limit' => false]));

		$modifier = 100 / (int) elgg_get_plugin_setting('stars', 'elggx_fivestar', '5');
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
		$widget = elgg_view("elggx_fivestar/voting", ['fivestar_guid' => $guid, 'min' => true]);
	} else {
		$widget = elgg_view("elggx_fivestar/voting", ['fivestar_guid' => $guid]);
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
	return([$match, $returnvalue]);
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

	$annotation = elgg_get_annotations([
		'guids' => $entity->guid,
		'types' => $entity->type,
		'annotation_name' => 'fivestar',
		'annotation_owner_guid' => $annotation_owner,
		'limit' => 1,
	]);

	if (is_object($annotation[0])) {
		return(true);
	}

	return(false);
}

function elggx_fivestar_defaults() {
	$elggx_fivestar_view = 'elggx_fivestar_view=object/blog, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=object/file, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=object/bookmarks, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=object/page, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=object/thewire, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=groups/profile/summary, tag=div, attribute=class, attribute_value=groups-profile-icon, before_html=<br>
	elggx_fivestar_view=object/discussion, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=icon/user/default, tag=div, attribute=class, attribute_value=elgg-avatar elgg-avatar-large, before_html=<br>
	elggx_fivestar_view=object/album, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=object/image, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=object/izap_videos, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=object/event_calendar, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=object/news, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext
	elggx_fivestar_view=object/poll, tag=div, attribute=class, attribute_value=elgg-listing-summary-subtitle elgg-subtext';

	elgg_set_plugin_setting('elggx_fivestar_view', $elggx_fivestar_view, 'elggx_fivestar');
}
