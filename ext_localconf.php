<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Configures Plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ucreation.' . $_EXTKEY,
	'Pi1',
	array(
		'Environment' => 'renderGoogleAnalytics, renderRobotsContent',
		'Sitemap' => 'render',
	),
	array(
		'Environment' => 'renderRobotsContent',
		'Sitemap' => 'render',
	)
);

