<?php

/**
 * This method is called when the view plugin hook is triggered.
 * If a matching view config is found then the fivestar widget is
 * called.
 *
 */
function elggx_fivestar_view(\Elgg\Hook $hook) {
	$returnvalue = $hook->getValue();

	if (elgg_in_context('widgets')) {
		return $returnvalue;
	}

	$params = $hook->getParams();

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
