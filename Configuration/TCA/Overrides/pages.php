<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Gets the extension configuration
$extConf = \Ucreation\SiteEssentials\Utility\SitemapUtility::getExtensionConfiguration();
$sitemapFields = [
	'tx_siteessentials_sitemap_exclude',
	'tx_siteessentials_sitemap_exclude_type',
	'tx_siteessentials_sitemap_change_frequency',
	'tx_siteessentials_sitemap_priority'
];
$enableSitemapFunctionality = (bool)$extConf['enableXmlSitemap'];

// TCA requests a update when the "is_siteroot" field in the page table is changed
if ($GLOBALS['TCA']['pages']['ctrl']['requestUpdate']) {
	$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] .= ', is_siteroot'.($enableSitemapFunctionality ? : ', tx_siteessentials_sitemap_exclude');
} else {
	$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] = 'is_siteroot'.($enableSitemapFunctionality ? : ', tx_siteessentials_sitemap_exclude');
}

// Adds a palette for the xml_sitemap
$GLOBALS['TCA']['pages']['palettes']['xml_sitemap'] = array(
	'showitem' => 'tx_siteessentials_sitemap_exclude, tx_siteessentials_sitemap_exclude_type, tx_siteessentials_sitemap_change_frequency, tx_siteessentials_sitemap_priority',
	'canNotCollapse' => TRUE
);

// Adds a palette for google_analytics
$GLOBALS['TCA']['pages']['palettes']['google_analytics'] = array(
	'showitem' => 'tx_siteessentials_google_analytics_content',
	'canNotCollapse' => TRUE
);

// Adds a palette for robots_txt
$GLOBALS['TCA']['pages']['palettes']['robots_txt'] = array(
	'showitem' => 'tx_siteessentials_robots_content',
	'canNotCollapse' => TRUE
);


// TCA configuration to inject the "tx_siteessentials_google_analytics_content" and "tx_siteessentials_robots_content" field into the pages table
$tempColumns = array(
	'tx_siteessentials_google_analytics_content' => array(
		'exclude' => FALSE,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_google_analytics_content',
		'displayCond' => 'FIELD:is_siteroot:=:1',
		'config' => array(
			'type' => 'text',
			'cols' => 30,
			'rows' => 4,
			'eval' => 'trim'
		),
	),
	'tx_siteessentials_robots_content' => array(
		'exclude' => FALSE,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_robots_content',
		'displayCond' => 'FIELD:is_siteroot:=:1',
		'config' => array(
			'type' => 'text',
			'cols' => 30,
			'rows' => 4,
			'eval' => 'trim'
		),
	),
	'tx_siteessentials_robots_exclude' => array(
		'exclude' => FALSE,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_robots_exclude',
		'displayCond' => array(
			'OR' => array(
				'FIELD:doktype:=:'.\Ucreation\SiteEssentials\Domain\Model\Page::DOKTYPE_NORMAL,
				'FIELD:doktype:=:'.\Ucreation\SiteEssentials\Domain\Model\Page::DOKTYPE_SHORTCUT,
			),
		),
		'config' => array(
			'type' => 'check',
			'items' => array(
				1 => array(
					0 => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_robots_exclude.0',
				),
			),
		),
	),
	'tx_siteessentials_sitemap_exclude' => array(
		'exclude' => FALSE,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_exclude',
		'config' => array(
			'type' => 'check',
			'items' => array(
				1 => array(
					0 => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_exclude.0',
				),
			),
		),
	),
	'tx_siteessentials_sitemap_exclude_type' => array(
		'exclude' => FALSE,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_exclude_type',
		'displayCond' => 'FIELD:tx_siteessentials_sitemap_exclude:=:1',
		'config' => array(
			'type' => 'select',
			'items' => array(
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_exclude_type.recursively',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::RECURSION_RECURSIVELY,
				),
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_exclude_type.thispage',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::RECURSION_NONE,
				),
			),
		),
	),
	'tx_siteessentials_sitemap_change_frequency' => array(
		'exclude' => FALSE,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_change_frequency',
		'config' => array(
			'type' => 'select',
			'items' => array(
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_change_frequency.default',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::CHANGEFREQ_DEFAULT,
				),
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_change_frequency.always',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::CHANGEFREQ_ALWAYS,
				),
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_change_frequency.hourly',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::CHANGEFREQ_HOURLY,
				),
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_change_frequency.daily',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::CHANGEFREQ_DAILY,
				),
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_change_frequency.weekly',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::CHANGEFREQ_WEEKLY,
				),
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_change_frequency.monthly',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::CHANGEFREQ_MONTHLY,
				),
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_change_frequency.yearly',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::CHANGEFREQ_YEARLY,
				),
				array(
					'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_change_frequency.never',
					\Ucreation\SiteEssentials\Utility\SitemapUtility::CHANGEFREQ_NEVER,
				),
			),
		),
	),
	'tx_siteessentials_sitemap_priority' => array(
		'exclude' => FALSE,
		'label' => 'LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:pages.tx_siteessentials_sitemap_priority',
		'config' => array(
			'type' => 'select',
			'default' => 0.5,
			'items' => array(
				array('1.0', '1.0'),
				array('0.9', 0.9),
				array('0.8', 0.8),
				array('0.7', 0.7),
				array('0.6', 0.6),
				array('0.5', 0.5),
				array('0.4', 0.4),
				array('0.3', 0.3),
				array('0.2', 0.2),
				array('0.1', 0.1),
				array('0.0', '0.0'),
			),
		),
	),
);

if (!$enableSitemapFunctionality) {
	foreach ($sitemapFields as $sitemapField) {
		$tempColumns[$sitemapField]['config'] = ['type' => 'passthrough'];
	}
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
	'pages', 
	'--div--;LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:tabs.siteessentials,
		--palette--;LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:palette.xml_sitemap;xml_sitemap,
		--palette--;LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:palette.google_analytics;google_analytics,
		--palette--;LLL:EXT:site_essentials/Resources/Private/Language/locallang_db.xlf:palette.robots_txt;robots_txt
	'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', 'miscellaneous', 'tx_siteessentials_robots_exclude');

unset($tempColumns);