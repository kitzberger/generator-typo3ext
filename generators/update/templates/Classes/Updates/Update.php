<?php
namespace <%- VendorName %>\<%- ExtKey %>\Updates;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ...
 * @author <%- author_name %> <<%- author_mail %>>
 */
class <%- UpdateName %>Update extends \TYPO3\CMS\Install\Updates\AbstractUpdate
{
    /** @var \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools */
    protected static $flexFormTools = null;

    /**
     * @var string
     */
    protected $title = 'Update task "<%- UpdateName %>"';

    public function __construct()
    {
        self::$flexFormTools = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class);
    }

    /**
     * Checks if an update is needed
     *
     * @param string &$description The description for the update
     * @return bool Whether an update is needed (TRUE) or not (FALSE)
     */
    public function checkForUpdate(&$description)
    {
        $legacyTeaserCount = $this->getDatabaseConnection()->exec_SELECTcountRows(
            'uid',
            'tt_content',
            'CType="textpic" AND imageorient=26 AND colPos=4 AND deleted=0'
        );
        if ($this->isWizardDone() || $legacyTeaserCount === 0) {
            return false;
        }

        $description = '<p>This converts legacy teasers (CType: textpic) to modern teaser elements (CType: dce_dceuid2).</p>';
        $description .= '<p>Number of elements to convert: ' . $legacyTeaserCount . '</p>';

        return true;
    }

    /**
     * Performs the database update if backend user's startmodule is help_aboutmodules
     *
     * @param array &$databaseQueries Queries done in this update
     * @param mixed &$customMessages Custom messages
     * @return bool
     */
    public function performUpdate(array &$databaseQueries, &$customMessages)
    {
        $db = $this->getDatabaseConnection();
        $legacyTeasers = $db->exec_SELECTgetRows(
            '*',
            'tt_content',
            'CType="textpic" AND imageorient=26 AND colPos=4 AND deleted=0'
        );
        if (!empty($legacyTeasers)) {
            foreach ($legacyTeasers as $legacyTeaser) {
                $db->exec_UPDATEquery(
                    'tt_content',
                    'uid=' . (int)$legacyTeaser['uid'],
                    self::createUpdateArray($legacyTeaser)
                );
                $databaseQueries[] = $db->debug_lastBuiltQuery;
                $db->exec_UPDATEquery(
                    'sys_file_reference',
                    'tablenames="tt_content" AND fieldname="image" AND uid_foreign=' . (int)$legacyTeaser['uid'],
                    [
                        'fieldname' => 'media',
                    ]
                );
                $databaseQueries[] = $db->debug_lastBuiltQuery;
            }
        }

        $this->markWizardAsDone();
        return true;
    }

    /**
     * Creates an array which will be used in a SQL update statement
     * @param  array $record
     * @return array
     */
    protected static function createUpdateArray($record)
    {
        $update = [
            'CType' => 'dce_dceuid2',
            'imageorient' => 0,
            'image' => 0,
            'media' => $record['image'],
            'header' => '',
            'subheader' => $record['header'],
            'header_link' => self::extractTypolinkFromBodytext($record['bodytext']),
            'bodytext' => '',
        ];

        $update['pi_flexform'] = self::createPiFlexform($update);

        return $update;
    }

    /**
     * Grabs the page uid from a typolink within a given bodytext
     * @param  string $bodytext
     * @return mixed
     */
    protected static function extractTypolinkFromBodytext($bodytext)
    {
        if (preg_match('/<link (\d+)/', $bodytext, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Creates a XML document used as pi_flexform.
     * This will represent the real values of the new DCE record!
     * @param  array $record
     * @return string
     */
    protected static function createPiFlexform($record)
    {
        $newFlexformData = [
            'data' => [
                'sheet.tabGeneral' => [
                    'lDEF' => [
                        'settings.title'    => ['vDEF' => $record['header']],
                        'settings.subtitle' => ['vDEF' => $record['subheader']],
                        'settings.image'    => ['vDEF' => $record['media']],
                        'settings.link'     => ['vDEF' => $record['header_link']],
                        'settings.text'     => ['vDEF' => $record['bodytext']],
                    ],
                ],
            ],
        ];

        $piFlexform = self::$flexFormTools->flexArray2Xml($newFlexformData, true);

        return $piFlexform;
    }
}
