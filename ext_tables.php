<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Adds a static file entry
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Site Essentials');