<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Adds static file entry
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Site Essentials');

// Configures Plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ucreation.' . $_EXTKEY,
	'Pi1',
	array(
		'Environment' => 'renderGoogleAnalytics',
	),
	array(
		
	)
);

