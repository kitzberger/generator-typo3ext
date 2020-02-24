<?php
namespace <%- VendorName %>\<%- ExtKey %>\Command;

use \TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Log\LogLevel;

class <%- controller %>CommandController extends CommandController
{
	/** @var \TYPO3\CMS\Core\Log\Logger */
	protected $logger = null;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var array
	 */
	protected $settings = array();

	public function __construct()
	{
		$this->logger = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);

		// Reads the following TypoScript: module.tx_<%- extkey %>.settings
		$this->settings = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
		);
	}

	/**
	 * ...
	 *
	 * @param int $pid
	 * @param boolean $dryRun
	 */
	public function <%- command %>Command($pid = null, $dryRun = false)
	{
		if (is_null($pid)) {
			$this->outputWarning('Please specify pid!' . "\n");
			exit;
		}
	}

	/**
	 * @param  string $str
	 * @param  int $level
	 */
	protected function log($str, $level = LogLevel::INFO)
	{
		$this->logger->log($level, $str);
	}

	protected function outputError($text, array $arguments = array())
	{
		$text =  "\033[0;31m" . $text . "\033[0m";
		return $this->output($text, $arguments);
	}

	protected function outputWarning($text, array $arguments = array())
	{
		$text =  "\033[0;33m" . $text . "\033[0m";
		return $this->output($text, $arguments);
	}

	protected function runDbOperation()
	{
		// TODO: create $this->records

		$tableName = 'xxxxxxx';

		try {
			$this->sqlQuery('START TRANSACTION');

			$this->deleteAllRecordsBeforehand($tableName);

			foreach ($this->records as $record) {
				$this->upsertRecord($tableName, $record);
			}

			$this->sqlQuery('COMMIT');
		} catch (\Exception $e) {
			$this->sqlQuery('ROLLBACK');
			throw $e;
		}
	}

	/**
	 * Upserts a record
	 *
	 * @param  string $tableName
	 * @param  array $record
	 *
	 * @return mixed
	 */
	protected function upsertRecord($tableName, $record)
	{
		return $this->upsert(
			$tableName,
			[
				'identifier' => (int)$record['identifier'],
				'title' => $GLOBALS['TYPO3_DB']->fullQuoteStr($record['title'], $tableName),
				'url' => $GLOBALS['TYPO3_DB']->fullQuoteStr($record['url'], $tableName),
				'crdate' => $_SERVER['REQUEST_TIME'],
				'tstamp' => $_SERVER['REQUEST_TIME'],
			],
			[
				'identifier=VALUES(identifier)',
				'title=VALUES(title)',
				'url=VALUES(url)',
				'tstamp=VALUES(tstamp)',
				'deleted=0',
			]
		);
	}

	/**
	 * Upserts a table record
	 *
	 * @param  string $tableName
	 * @param  array $insertData
	 * @param  array $updateData
	 *
	 * @return mixed
	 */
	protected function upsert($tableName, $insertData, $updateData)
	{
		$query = sprintf(
			'INSERT INTO %s (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s',
			$tableName,
			implode(',', array_keys($insertData)),
			implode(',', $insertData),
			implode(',', $updateData)
		);

		return $this->sqlQuery($query);
	}

	/**
	 * Soft-deletes all records before importing them again
	 *
	 * @return void
	 */
	protected function deleteAllRecordsBeforehand($table, $where = '1=1')
	{
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, $where, ['deleted' => 1, 'tstamp' => $_SERVER['REQUEST_TIME']]);
	}

	/**
	 * SQL Query (with exception support)
	 *
	 * @param string $query SQL query
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function sqlQuery($query)
	{
		$ret = $GLOBALS['TYPO3_DB']->sql_query($query);
		if (!$ret) {
			throw new \Exception(sprintf(
				'SQL-Error: %s [%s]',
				$GLOBALS['TYPO3_DB']->sql_error(),
				$GLOBALS['TYPO3_DB']->sql_errno()
			));
		}
		return $ret;
	}
}
