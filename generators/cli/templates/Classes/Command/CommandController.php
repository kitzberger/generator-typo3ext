<?php
namespace <%- VendorName %>\<%- ExtKey %>\Command;

use \TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

class <%- controller %>CommandController extends CommandController
{

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
}
