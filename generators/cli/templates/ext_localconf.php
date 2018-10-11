<?php

// BEGIN
//
if (TYPO3_MODE === 'BE' || TYPO3_MODE === 'CLI') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['<%- ExtKey %>-<%- controller %>'] =
		\<%- VendorName %>\<%- ExtKey %>\Command\<%- controller %>CommandController::class;
}
// END
