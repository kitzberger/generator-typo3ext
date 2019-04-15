<?php

// BEGIN
if (TYPO3_MODE === 'BE' || TYPO3_MODE === 'CLI') {
	$updateClass = \<%- VendorName %>\<%- ExtKey %>\Updates\<%- UpdateName %>Update::class;
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][$updateClass] = $updateClass;
}
// END
