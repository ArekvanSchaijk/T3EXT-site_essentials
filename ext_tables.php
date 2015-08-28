<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Adds a static file entry
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Site Essentials');


// TCA requests a update when the "is_siteroot" field in the page table is changed
$TCA['pages']['ctrl']['requestUpdate'] = 'is_siteroot';

// TCA configuration to inject the "tx_siteessentials_google_analytics_content" field into the pages table
$tempColumns = array();
$tempColumns['tx_siteessentials_google_analytics_content'] = array(
	'exclude' => 0,
	'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_google_analytics_content',
	'displayCond' => 'FIELD:is_siteroot:=:1',
	'config' => array(
		'type' => 'text',
		'cols' => 40,
		'rows' => 10,
		'eval' => ''
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--div--;LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:tabs.siteessentials,tx_siteessentials_google_analytics_content');