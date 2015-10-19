<?php
namespace Ucreation\SiteEssentials\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Arek van Schaijk <info@ucreation.nl>, Ucreation
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Ucreation\SiteEssentials\Utility\SitemapUtility;
use Ucreation\SiteEssentials\Hooks\AbstractPostCollectPages;
use Ucreation\SiteEssentials\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SitemapController
 *
 * @package Ucreation\SiteEssentials
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class SitemapController extends BaseController {
	
	/**
	 * @var array
	 */
	protected $pages = array();
	
	/**
	 * @var array
	 */
	protected $items = array();
	
	/**
	 * @var string
	 */
	protected $pageSelectFields = NULL;
	
	/**
	 * @var array
	 */
	protected $allowedDokTypes = NULL;
	
	/**
	 * @var \Ucreation\SiteEssentials\Service\SitemapService
	 * @inject
	 */
	protected $sitemapService = NULL;
	
	/**
	 * Render Action
	 *
	 * @return void
	 * @throws \Ucreation\SiteEssentials\Exception\InvalidHookException
	 */
	public function renderAction() {
		// Gets the root page
		$rootPage = $this->getRootPage();		
		// Collect Pages Recursively
		$this->collectPagesRecursively(
			$rootPage['uid']
		);
		// Get all registred "postCollectPages" hook objects
		$hookObjects = SitemapUtility::getRegistredHookObjects(
			SitemapUtility::HOOK_POST_COLLECT_PAGES
		);
		// Processes the "postCollectPages" hook objects
		foreach ($hookObjects as $hookObject) {
			$instance = $this->objectManager->get($hookObject);
			if (!$instance instanceof AbstractPostCollectPages) {
				throw new Exception\InvalidHookException('Hook Object "'.$hookObject.'" must be an instance of "Ucreation\\SiteEssentials\\Hooks\\AbstractPostCollectPages"');	
			}
			$this->pages = $instance->render($this->pages);
		}
		// Adds the pages as items
		$this->addPagesItems();		
		// Get all registred "postProcessItems" hook objects
		$hookObjects = SitemapUtility::getRegistredHookObjects(
			SitemapUtility::HOOK_POST_PROCESS_ITEMS
		);
		// Processes the "postProcessItems" hook objects
		foreach ($hookObjects as $hookObject) {
			$instance = $this->objectManager->get($hookObject);
			if (!$instance instanceof AbstractPostProcessItems) {
				throw new Exception\InvalidHookException('Hook Object "'.$hookObject.'" must be an instance of "Ucreation\\SiteEssentials\\Hooks\\AbstractPostProcessItems"');	
			}
			$this->items = $instance->render($this->items);
		}
		// Attach all items to the sitemap
		$this->sitemapService->processItems($this->items);
		// Outputs the sitemap
		header('Content-type: application/xml');
		echo $this->sitemapService->getXml((bool)$this->settings['sitemap']['formatOutput']);
		exit;
	}
	
	/**
	 * Get Root Page
	 *
	 * @return array
	 */
	protected function getRootPage() {
		$rootPage = self::getDatabaseConnection()->exec_SELECTgetSingleRow(
			self::getPageSelectFields(),
			'pages',
			'uid = '.self::getTypoScriptFrontendController()->rootLine[0]['uid']
		);
		if ((bool)$this->settings['sitemap']['includeRootPage'] && $this->canPageBeIncluded($rootPage)) {
			$this->pages[] = $rootPage;	
		}
		return $rootPage;
	}
	
	/**
	 * Collect Pages Recursively
	 *
	 * @param int $pageId
	 * @param int $level
	 * @return void
	 */
	protected function collectPagesRecursively($pageId, $level = 1) {
		$pages = self::getDatabaseConnection()->exec_SELECTgetRows(
			self::getPageSelectFields(),
			'pages',
			'pid = '.$pageId.' AND deleted = 0'
		);
		$level++;
		if ($pages) {
			foreach ($pages as $page) {
				$this->storePageAndCollectSubpages($page, $level);
			}
		}
	}
	
	/**
	 * Store Page And Collect Subpages
	 *
	 * @param array $page
	 * @param int $level
	 * @return void
	 */
	protected function storePageAndCollectSubpages($page, $level) {
		// Checks if the page can be included
		if ($this->canPageBeIncluded($page)) {
			$this->pages[] = $page;	
		}
		if (!$page['exclude'] || $page['exclude_type'] != SitemapUtility::RECURSION_RECURSIVELY) {
			if (
				!$this->settings['sitemap']['pagesRecursivelyMaxDepth'] ||
				!$level ||
				$level <= $this->settings['sitemap']['pagesRecursivelyMaxDepth']
			) {
				$this->collectPagesRecursively($page['uid'], $level);
			}
		}
	}
	
	/**
	 * Can Page Be Included
	 *
	 * @param array $page
	 * @return bool
	 */
	protected function canPageBeIncluded(array $page) {
		if (
			// The page is not disabled
			(bool)!$page['disabled'] &&
			// The page is not excluded
			(bool)!$page['exclude'] &&
			// When nav_hide items must be included
			(bool)$this->settings['sitemap']['includeHideInMenu'] || !(bool)$page['nav_hide'] &&
			// The doktype is allowed
			$this->isDokTypeAllowed((int)$page['doktype']) &&
			// There is no starttime set or the starttime is lower then the current time
			(!$page['starttime'] || $page['starttime'] <= time()) &&
			// There is no endtime set or when the endtime is higher then the current time
			(!$page['endtime'] || $page['endtime'] >= time())
		) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Get Page Select Fields
	 *
	 * @return string
	 */
	protected function getPageSelectFields() {
		if (is_null($this->pageSelectFields)) {
			$this->pageSelectFields = implode(
				',',
				array_merge(
					array(
						'uid',
						$GLOBALS['TCA']['pages']['ctrl']['tstamp'].' AS timestamp',
						$GLOBALS['TCA']['pages']['ctrl']['enablecolumns']['disabled'].' AS disabled',
						$GLOBALS['TCA']['pages']['ctrl']['enablecolumns']['starttime'].' AS starttime',
						$GLOBALS['TCA']['pages']['ctrl']['enablecolumns']['endtime'].' AS endtime',
						'nav_hide',
						'doktype',
						'tx_siteessentials_sitemap_exclude AS exclude',
						'tx_siteessentials_sitemap_exclude_type AS exclude_type',
						'tx_siteessentials_sitemap_change_frequency AS change_frequency',
						'tx_siteessentials_sitemap_priority AS priority',
					),
					SitemapUtility::getRegistredPageFields()
				)
			);
		}
		return $this->pageSelectFields;
	}
	
	/**
	 * Is Dok Type Allowed
	 *
	 * @param int $dokType
	 * @return bool
	 */
	protected function isDokTypeAllowed($dokType) {
		if (is_null($this->allowedDokTypes)) {
			$this->allowedDokTypes = GeneralUtility::trimExplode(',', $this->settings['sitemap']['allowedDokTypes']);
		}
		return in_array($dokType, $this->allowedDokTypes);
	}
	
	/**
	 * Add Pages Items
	 * 
	 * @return void
	 */
	protected function addPagesItems() {
		foreach ($this->pages as $page) {
			$this->addItem(
				$this->getFrontendUri((int)$page['uid'], NULL, TRUE),
				(int)$page['timestamp'],
				SitemapUtility::getChangeFrequency(
					$page['change_frequency'],
					$this->settings['sitemap']['defaultChangeFrequency']
				),
				(float)$page['priority']
			);
		}
	}
	
	/**
	 * Add Item
	 *
	 * @param string $loc
	 * @param int $timestamp
	 * @param string $changeFrequency
	 * @param float $priority
	 * @return void
	 */
	protected function addItem($loc, $timestamp, $changeFrequency, $priority) {
		$this->items[] = array(
			'loc' => $loc,
			'lastmod' => date((string)$this->settings['sitemap']['dateFormat'], $timestamp),
			'changefreq' => $changeFrequency,
			'priority' => $priority,
		);
	}
	
}