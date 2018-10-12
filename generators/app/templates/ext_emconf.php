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
			'php' => '7.0.0-7.2.99',
			'typo3' => '<%- t3_version %>',
		],
		'conflicts' => [],
		'suggests' => [],
	],
];
