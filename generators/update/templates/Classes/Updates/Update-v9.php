<?php
declare(strict_types = 1);
namespace <%- VendorName %>\<%- ExtKey %>\Updates;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;

/**
 * ...
 * @author <%- author_name %> <<%- author_mail %>>
 */
class <%- UpdateName %>Update implements UpgradeWizardInterface, ConfirmableInterface
{
    protected $confirmation;

    public function __construct()
    {
        $this->confirmation = new Confirmation(
            'Please make sure to read the following carefully:',
            $this->getDescription(),
            false,
            'Yes, I understand!',
            '',
            true
        );
    }

    /**
     * @return string Unique identifier of this updater
     */
    public function getIdentifier(): string
    {
        return '<%- UpdateName %>';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'Update task "<%- UpdateName %>"';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Donec rutrum congue leo eget malesuada.'
             . 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ultricies ligula sed magna dictum porta.'
             . 'Quisque velit nisi, pretium ut lacinia in, elementum id enim. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        return (bool)$queryBuilder->count('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
            )
            ->execute()
            ->fetchColumn(0);
    }

    /**
     * @return string[] All new fields and tables must exist
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    /**
     * Performs the update
     *
     * @return bool Whether everything went smoothly or not
     */
    public function executeUpdate(): bool
    {
    	// TODO

        return true;
    }

    /**
     * Return a confirmation message instance
     *
     * @return \TYPO3\CMS\Install\Updates\Confirmation
     */
    public function getConfirmation(): Confirmation
    {
        return $this->confirmation;
    }
}
