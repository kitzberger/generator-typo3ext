// BEGIN_FIELD_CHECKBOX
        '<%- propertyName %>' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
// END_FIELD_CHECKBOX
// BEGIN_FIELD_DATE
        '<%- propertyName %>' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'date',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
// END_FIELD_DATE
// BEGIN_FIELD_TEXT
        '<%- propertyName %>' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 6,
                'eval' => 'trim',
            ],
        ],
// END_FIELD_TEXT
// BEGIN_FIELD_RTE
        '<%- propertyName %>' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 6,
                'eval' => 'trim',
                'softref' => 'typolink_tag,images,email[subst],url',
            ],
            'defaultExtras' => 'richtext[]:rte_transform[mode=ts_css]',
        ],
// END_FIELD_RTE
// BEGIN_FIELD_IMAGE
        '<%- propertyName %>' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                '<%- propertyName %>',
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
// END_FIELD_IMAGE
// BEGIN_FIELD_LINK
        '<%- propertyName %>' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>',
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
// END_FIELD_LINK
// BEGIN_FIELD_SELECT
        '<%- propertyName %>' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    [0 => '', 1 => ''],
                    [0 => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>.label-1', 1 => 'value-1'],
                    [0 => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>.label-2', 1 => 'value-2'],
                ],
            ],
        ],
// END_FIELD_SELECT
// BEGIN_FIELD_GROUP_DB_SINGLE
        '<%- propertyName %>' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>.<%- propertyName %>',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => '<%- tx_related_table %>',
                'size' => 1,
                'maxitems' => 1,
                'multiple' => 0,
                'default' => 0
            ],
        ],
// END_FIELD_GROUP_DB_SINGLE
