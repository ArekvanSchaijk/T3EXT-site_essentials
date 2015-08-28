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

use Ucreation\SiteEssentials\Domain\Model\Page;
use Ucreation\SiteEssentials\Utility\GeneralUtility as _GeneralUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class EnvironmentController
 *
 * @package Ucreation\SiteEssentials
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class EnvironmentController extends BaseController {
	
	/**
	 * @var array
	 */
	protected $robotsExcludedPages = array();
	
	/**
	 * @var \Ucreation\SiteEssentials\Domain\Repository\PageRepository
	 * @inject
	 */
	protected $pageRepository = NULL;

	/**
	 * Render Google Analytics Action
	 *
	 * @global \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE
	 * @return boolean
	 */
	public function renderGoogleAnalyticsAction() {
		global $TSFE;
		// If the setting "renderOnlyInProduction" is given true then we're loading the Google Analytics content only in production 
		if (!(bool)$this->settings['googleAnalytics']['renderOnlyInProduction'] || self::isProductionEnvironment()) {
			$content = '';
			// Retrieves the Root Page Id
			$rootPageId = _GeneralUtility::getRootPageId();
			// Finds the page from the repository
			if ($page = $this->pageRepository->findOneByUid($rootPageId)) {
				// Retrieves the Google Analytics content and strips the tags
				$content = strip_tags($page->getGoogleAnalyticsContent());
				if ($content) {
					if ((bool)$this->settings['googleAnalytics']['includeInFooter']) {
						$TSFE->getPageRenderer()->addJsFooterInlineCode('Google Analytics', $content);
					} else {
						$TSFE->getPageRenderer()->addJsInlineCode('Google Analytics', $content);
					}
				}
			}
		}
		return FALSE;
	}
	
	/**
	 * Render Robots Content Action
	 *
	 * @global \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE
	 * @global array $TYPO3_CONF_VARS
	 * @return void
	 */
	public function renderRobotsContentAction() {
		global $TSFE, $TYPO3_CONF_VARS;
		$content = '';
		// If we're not in production we check for the setting "disallowIfNotInProduction" when we need to show the 'Not In Production Content'
		if (!self::isProductionEnvironment() && (bool)$this->settings['robots']['disallowIfNotInProduction']) {
			$notInProductionContentFilePath = GeneralUtility::getFileAbsFileName($this->settings['robots']['notInProductionContentFilePath']);
			if (file_exists($notInProductionContentFilePath)) {
				$content = file_get_contents($notInProductionContentFilePath);
			}
		// Shows the content from the page
		} else {
			$robots = array();
			// Retrieves the Root Page Id
			$rootPageId = _GeneralUtility::getRootPageId();
			// Finds the page from the repository
			if ($page = $this->pageRepository->findOneByUid($rootPageId)) {
				$lines = array();
				$content = trim($page->getRobotsContent());
				// Adds the 'User-agent' if not given 
				if (strpos(strtolower($content), 'user-agent') === FALSE && (bool)$this->settings['robots']['addUserAgentIfNotGiven']) {
					$robots[] = 'User-agent: *';	
				}
				if ($content) {
					$lines = explode(PHP_EOL, $content);
					// Adds the lines from the robots content if they contain 'allow' or 'user-agent'
					foreach ($lines as $line) {
						if (strpos(strtolower($line), 'allow') !== FALSE || strpos(strtolower($line), 'user-agent') !== FALSE) {
							$robots[] = trim($line);
						}
					}
				// Adds 'Allow: /' if no $content is given
				} else if ((bool)$this->settings['robots']['allowAllPagesIfNoContentIsGiven']){
					$robots[] = 'Allow: /';
				}
				// Adds robots excluded pages
				$this->collectRecursivelyRobotsExcludedPages($page);				
				foreach ($this->robotsExcludedPages as $excludedPage) {
					$robots[] = 'Disallow: '.$this->getFrontendUri($excludedPage->getUid());
				}
			}
			$content = implode(PHP_EOL, $robots);
		}
		// Parses the content directly in the output
		// This is because there are user cases where TYPO3 adds additional information to the output
		header('Content-Type: text/plain');
		die($content);
	}
	
	/**
	 * Collect Recursively Robots Excluded Pages
	 *
	 * @param \Ucreation\SiteEssentials\Domain\Model\Page $currentPage
	 * @return void
	 */
	protected function collectRecursivelyRobotsExcludedPages(Page $currentPage) {
		if ($currentPage->isRobotsExclude()) {
			$this->robotsExcludedPages[] = $currentPage;
		} else {
			$subpages = $this->pageRepository->findByRobotsExcludedOrDeleted($currentPage->getUid());
			foreach ($subpages as $subpage) {
				$this->collectRecursivelyRobotsExcludedPages($subpage);
			}
		}
	}
	
	/**
	 * Is Production Environment
	 *
	 * @return boolean
	 */
	static protected function isProductionEnvironment() {
		return GeneralUtility::getApplicationContext()->isProduction();
	}
	
}