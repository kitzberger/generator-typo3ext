<?php
defined ('TYPO3_MODE') || die ('Access denied.');

// BEGIN
$tca = [
	'columns' => [
		'<%- slug_column %>' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- slug_column %>',
			'config' => [
				'type' => 'slug',
				'generatorOptions' => [
					'fields' => ['<%- title_column %>'],
					'fieldSeparator' => '/',
					'prefixParentPageSlug' => false,
					'replacements' => [
						'/' => '',
					],
				],
				'fallbackCharacter' => '-',
				'eval' => 'uniqueInSite',
				'default' => ''
			],
		],
	],
];

$GLOBALS['TCA']['<%- table %>'] = array_replace_recursive($GLOBALS['TCA']['<%- table %>'], $tca);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToAllPalettesOfField('<%- table %>', '<%- title_column %>', '<%- slug_column %>');
// END
