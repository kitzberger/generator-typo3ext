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
			echo 'Please specify pid!' . "\n";
			exit;
		}
	}

}
