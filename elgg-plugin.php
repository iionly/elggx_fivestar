<?php

return [
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
];
