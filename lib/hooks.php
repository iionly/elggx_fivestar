<?php

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
		$options = [];
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
