<?php

return [
	'actions' => [
		'elggx_fivestar/rate' => [],
		'elggx_fivestar/settings' => [],
		'elggx_fivestar/reset' => [],
	],
	'routes' => [
		'default:object:e-resume' => [
			'path' => '/e-resume',
			'resource' => 'e-resume/all',
		],
		'edit:object:e-resume' => [
			'path' => '/e-resume/edit/{guid}/{title?}',
			'resource' => 'e-resume/edit',
		],
		'add:object:e-resume' => [
			'path' => '/e-resume/add/{container_guid}',
			'resource' => 'e-resume/edit',
		],
    'view:object:e-resume' => [
      'path' => '/e-resume/view/{guid}/{title?}',
      'resource' => 'e-resume/view',
    ],
    'preview:object:e-resume' => [
      'path' => '/e-resume/preview',
      'resource' => 'e-resume/get_preview',
    ],
    'help:object:e-resume' => [
      'path' => '/e-resume/help',
      'resource' => 'e-resume/help',
    ],

	],
];
