<?php
return [
    'ctrl' => [
        'label' => 'title',
        //'label_alt' => 'uid,title',
        //'label_alt_force' => 1,

        //'descriptionColumn' => 'description',
        'tstamp' => 'tstamp',
        'title' => 'LLL:EXT:<%- ext_key %>/Resources/Private/Language/<%- table %>.xlf:<%- table %>',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime'
        ],

        'adminOnly' => true,
        //'rootLevel' => 1,
        'default_sortby' => 'title',

        // different types?
        'type' => 'type',
        'typeicon_column' => 'type',
        'typeicon_classes' => [
            'default' => 'type-xxx',
            '0' => 'type-xxx',
            '1' => 'type-yyy',
        ],

        'searchFields' => 'title',

        //'useColumnsForDefaultValues' => '',
        //'versioningWS_alwaysAllowLiveEdit' => true,
    ],
    'interface' => [
    	// todo all fields comma separated
        'showRecordFieldList' => ''
    ],
    'columns' => [
// BEGIN_COLUMN_DEF
// END_COLUMN_DEF
    ],
    'types' => [
        '0' => ['showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                title
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;timeRestriction,
        '],
        // more types possible here
    ],
    'palettes' => [
        'timeRestriction' => ['showitem' => 'starttime, endtime']
    ],
];
