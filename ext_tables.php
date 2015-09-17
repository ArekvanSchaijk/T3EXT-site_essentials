<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Adds a static file entry
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Site Essentials');

// TCA requests a update when the "is_siteroot" field in the page table is changed
$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] = 'is_siteroot';

// TCA requests a update when the "is_siteroot" field in the page table is changed
if ($GLOBALS['TCA']['pages']['ctrl']['requestUpdate']) {
	$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] .= ',is_siteroot';
} else {
	$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] = 'is_siteroot';
}

// TCA configuration to inject the "tx_siteessentials_google_analytics_content" and "tx_siteessentials_robots_content" field into the pages table
$tempColumns = array(
	'tx_siteessentials_google_analytics_content' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_google_analytics_content',
		'displayCond' => 'FIELD:is_siteroot:=:1',
		'config' => array(
			'type' => 'text',
			'cols' => 40,
			'rows' => 10,
			'eval' => ''
		),
	),
	'tx_siteessentials_robots_content' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_robots_content',
		'displayCond' => 'FIELD:is_siteroot:=:1',
		'config' => array(
			'type' => 'text',
			'cols' => 40,
			'rows' => 10,
			'eval' => ''
		),
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--div--;LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:tabs.siteessentials,tx_siteessentials_google_analytics_content,tx_siteessentials_robots_content');

// TCA configuration to add the field "tx_siteessentials_robots_exclude" in the page table under "miscellaneous"
$tempColumns = array(
	'tx_siteessentials_robots_exclude' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_robots_exclude',
		'displayCond' => 'FIELD:doktype:IN:1,4',
		'config' => array(
			'type' => 'check',
			'items' => array(
				1 => array(
					0 => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_robots_exclude.0',
				),
			),
		),
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', 'miscellaneous', 'tx_siteessentials_robots_exclude');

unset($tempColumns);