<?php
$EM_CONF[$_EXTKEY] = [
	'title' => '<%- ext_name %>',
	'description' => '<%- ext_desc %>',
	'category' => 'system',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'author' => '<%- author_name %>',
	'author_email' => '<%- author_mail %>',
	'author_company' => '',
	'version' => '1.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '<%- t3_version %>-',
		],
		'conflicts' => [],
		'suggests' => [],
	],
];
