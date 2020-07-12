<?php

if (!function_exists('str_get_html')) {
	require_once(dirname(__FILE__) . '/lib/simple_html_dom.php');
}
require_once(dirname(__FILE__) . '/lib/functions.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');

return [
	'bootstrap' => \ElggxFivestar\ElggxFivestarBootstrap::class,
	'actions' => [
		'elggx_fivestar/rate' => [
			'access' => 'logged_in',
		],
		'elggx_fivestar/reset' => [
			'access' => 'admin',
		],
		'elggx_fivestar/settings/save' => [
			'access' => 'admin',
		],
	],
	'settings' => [
		'stars' => '5',
		'change_vote' => '1',
		'change_cancel' => '1',
	],
	'views' => [
		'default' => [
			'elggx_fivestar/' => __DIR__ . '/graphics',
		],
	],
	'view_extensions' => [
		'css/elgg' => [
			'elggx_fivestar/css' => [],
		],
		'css/admin' => [
			'elggx_fivestar/css' => [],
		],
	],
// 	'hooks' => [
// 		'register' => [
// 			'view' => [
// 				'all' => [
// 					'elggx_fivestar_view' => [],
// 				],
// 			],
// 		],
// 	],
];
