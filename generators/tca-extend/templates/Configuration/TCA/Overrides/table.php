<?php
defined ('TYPO3_MODE') || die ('Access denied.');

// BEGIN
$tca = [
	'columns' => [
		<% if (new_extbase_type) { %>'tx_extbase_type' => [
			'config' => [
				'items' => [
					[
						'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.tx_extbase_type.<%- new_extbase_type %>',
						'<%- new_extbase_type %>',
					],
				],
			],
		],
		<% } %>'new_checkbox_field' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.new_checkbox_field',
			'config' => [
				'type' => 'check',
				'default' => 0,
			],
		],
		'new_date_field' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.new_date_field',
			'config' => [
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'date',
				'checkbox' => 0,
				'default' => 0,
			],
		],
		'new_rte_field' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.new_rte_field',
			'config' => [
				'type' => 'text',
				'cols' => 40,
				'rows' => 6,
				'eval' => 'trim',
				'softref' => 'typolink_tag,images,email[subst],url',
			],
			'defaultExtras' => 'richtext[]:rte_transform[mode=ts_css]',
		],
		'new_image_field' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.new_image_field',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'new_image_field',
				[
					'appearance' => [
						'createNewRelationLinkTitle' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.logo.select',
						'fileUploadAllowed' => false,
					],
					'foreign_types' => [
						'0' => [
							'showitem' => '--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette, --palette--;;filePalette'
						],
						\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
							'showitem' => 'title, --palette--;;filePalette',
						],
					],
					'minitems' => 0,
					'maxitems' => 1,
				],
				$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
			),
		],
		'new_link_field' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.new_link_field',
			'config' => [
				'type' => 'input',
				'size' => '30',
				'softref' => 'typolink',
				'wizards' => [
					'_PADDING' => 2,
					'link' => [
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
						'module' => [
							'name' => 'wizard_link',
						],
						'JSopenParams' => 'height=600,width=500,status=0,menubar=0,scrollbars=1',
						'params' => [
							'blindLinkOptions' => 'file,folder,mail,media,spec,mopps',
						],
					],
				],
			],
		],
	],<% if (new_palette) { %>
	'palettes' => [
		'<%- new_palette %>' => [
			'canNotCollapse' => '1',
			'showitem' => 'new_date_field, --linebreak--, new_rte_field, --linebreak--, new_image_field, --linebreak--, new_link_field',
		],
	],<% } %><% if (new_extbase_type) { %>
	'types' => [
		'<%- new_extbase_type %>' => [
			'showitem' => 'hidden, <% if (new_extbase_type) { %>tx_extbase_type, <% } %>title, --palette--;LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.palette.<%- new_palette %>;<%- new_palette %>, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime',
		],
	],
	'ctrl' => [
		'typeicon_classes' => [
			'new_type' => 'ext-<%- ext_key %>-record-<%- new_extbase_type %>',
		],
	],<% } %>
];

$GLOBALS['TCA']['<%- table %>'] = array_replace_recursive($GLOBALS['TCA']['<%- table %>'], $tca);
<% if (new_palette && !new_extbase_type) { %>
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('<%- table %>', '--palette--;LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.palette.<%- new_palette %>;<%- new_palette %>');
<% } %>
#\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToAllPalettesOfField('<%- table %>', 'existing_xxx_field', 'new_checkbox_field,new_date_field,new_rte_field,new_image_field,new_link_field');
#\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('<%- table %>', 'existing_or_new_xxx_palette', 'new_checkbox_field,new_date_field,new_rte_field,new_image_field,new_link_field');
// END
