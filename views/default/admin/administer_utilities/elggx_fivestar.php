<?php

/**
 * Fivestar settings form
 */

elgg_require_js('elggx_fivestar/elggx_fivestar_admin');

$stars = elgg_get_plugin_setting('stars', 'elggx_fivestar', '5');
$form = "<div class='mbs'>" . elgg_echo('elggx_fivestar:numstars');
$form .= elgg_view('input/select', array(
	'name' => 'params[stars]',
	'options_values' => array(
		'2'  => '2',
		'3'  => '3',
		'4'  => '4',
		'5'  => '5',
		'6'  => '6',
		'7'  => '7',
		'8'  => '8',
		'9'  => '9',
		'10' => '10'),
	'value' => $stars
));
$form .= "</div>";

$change_vote = elgg_get_plugin_setting('change_vote', 'elggx_fivestar', '1');
$form .= "<div class='mbl'>" . elgg_echo('elggx_fivestar:settings:change_cancel');
$form .= elgg_view('input/select', array(
	'name' => 'params[change_vote]',
	'options_values' => array(
		'1' => elgg_echo('elggx_fivestar:settings:yes'),
		'0' => elgg_echo('elggx_fivestar:settings:no')),
	'value' => $change_vote
));
$form .= "</div>";

$form .= "<div class='mbl'><h3>".elgg_echo('elggx_fivestar:settings:view_heading')."</h3></div>";
$form .= "<div class='mbl'>";
$form .= elgg_view("output/url", array(
	'href' => elgg_get_site_url() . "action/elggx_fivestar/reset",
	'text' => elgg_echo('elggx_fivestar:settings:defaults'),
	'is_action' => true,
	'class' => 'elgg-button elgg-button-action',
	'confirm' => elgg_echo('elggx_fivestar:settings:defaults:confirm')
));
$form .= "</div>";

$form .= "<div class='mts mbs'>";

$x = 1;
$lines = explode("\n", elgg_get_plugin_setting('elggx_fivestar_view', 'elggx_fivestar'));

foreach ($lines as $line) {
	$options = array();
	$parms = explode(",", $line);
	foreach ($parms as $parameter) {
		preg_match("/^(\S+)=(.*)$/", trim($parameter), $match);
		$options[$match[1]] = $match[2];
	}

	if ($line) {
		$form .= '<fieldset id="row' . $x . '" class="fivestar-collapsible fivestar-collapsed">';
		$form .= '<legend id="row' . $x . '" class="fivestar-collapsible fivestar-collapsed">' . $options['elggx_fivestar_view'] . '</legend>';

		$form .= '<p id="row' . $x . '" style="background-color: transparent; display: none;">';
		$form .= '<input id="txt' . $x . '" class="input-text" type="text" name="elggx_fivestar_views[]" value="' . $line . '" />';
		$form .= '<a class="fivestar-admin-remove-form-field elgg-button elgg-button-action mls" href="#" data-row="'.$x.'">' . elgg_echo('elggx_fivestar:settings:remove_view') . '</a></p>';

		$form .= '</fieldset>';
	}
	$x++;
}

$form .= '<input type="hidden" id="id" value="'.$x.'">';
$form .= '<div id="divTxt"></div>';
$form .= "</div>";

$form .= '<div class="mts mbl"><a class="fivestar-admin-add-form-field elgg-button elgg-button-action">'.elgg_echo('elggx_fivestar:settings:add_view').'</a></div>';

$form .= elgg_view("input/securitytoken");
$form .= '<div><br>' . elgg_view('input/submit', array('value' => elgg_echo("save"))) . '</div>';

$action = elgg_get_site_url() . 'action/elggx_fivestar/settings';

echo elgg_view('input/form', array('action' => $action, 'body' => $form));
