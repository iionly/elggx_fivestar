<?php

/**
 * Fivestar settings form
 */

elgg_require_js('elggx_fivestar/elggx_fivestar_admin');

$form = '';

$stars = elgg_get_plugin_setting('stars', 'elggx_fivestar', '5');
$form .= elgg_view_field([
	'#type' => 'select',
	'name' => 'params[stars]',
	'id' => 'stars',
	'options_values' => [
		'2'  => '2',
		'3'  => '3',
		'4'  => '4',
		'5'  => '5',
		'6'  => '6',
		'7'  => '7',
		'8'  => '8',
		'9'  => '9',
		'10' => '10'
	],
	'value' => $stars,
	'#label' => elgg_echo('elggx_fivestar:numstars'),
]);

$change_vote = elgg_get_plugin_setting('change_vote', 'elggx_fivestar', '1');
$form .= elgg_view_field([
	'#type' => 'select',
	'name' => 'params[change_vote]',
	'id' => 'change_vote',
	'options_values' => [
		'1' => elgg_echo('elggx_fivestar:settings:yes'),
		'0' => elgg_echo('elggx_fivestar:settings:no')
	],
	'value' => $change_vote,
	'#label' => elgg_echo('elggx_fivestar:settings:change_cancel'),
]);


$content = "<div class='mtl mbl'>";
$content .= elgg_view("output/url", array(
	'href' => elgg_get_site_url() . "action/elggx_fivestar/reset",
	'text' => elgg_echo('elggx_fivestar:settings:defaults'),
	'is_action' => true,
	'class' => 'elgg-button elgg-button-action',
	'confirm' => elgg_echo('elggx_fivestar:settings:defaults:confirm')
));
$content .= "</div>";

$content .= "<div class='mts mbs'>";

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
		$content .= '<fieldset id="row' . $x . '" class="fivestar-collapsible fivestar-collapsed">';
		$content .= '<legend id="row' . $x . '" class="fivestar-collapsible fivestar-collapsed">' . $options['elggx_fivestar_view'] . '</legend>';

		$content .= '<p id="row' . $x . '" style="background-color: transparent; display: none;">';
		$content .= '<input id="txt' . $x . '" class="input-text" type="text" name="elggx_fivestar_views[]" value="' . $line . '" />';
		$content .= '<a class="fivestar-admin-remove-form-field elgg-button elgg-button-action mls" href="#" data-row="'.$x.'">' . elgg_echo('elggx_fivestar:settings:remove_view') . '</a></p>';

		$content .= '</fieldset>';
	}
	$x++;
}

$content .= '<input type="hidden" id="id" value="'.$x.'">';
$content .= '<div id="divTxt"></div>';
$content .= "</div>";

$content .= '<div class="mts mbm"><a class="fivestar-admin-add-form-field elgg-button elgg-button-action">'.elgg_echo('elggx_fivestar:settings:add_view').'</a></div>';

$form .= elgg_view_module('inline', elgg_echo('elggx_fivestar:settings:view_heading'), $content);

$form .= elgg_view('input/submit', [
	'value' => elgg_echo("save"),
]);

echo $form;
