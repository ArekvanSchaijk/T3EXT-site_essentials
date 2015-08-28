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
				// Retrieves the Google Analytics content
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
	 * Is Production Environment
	 *
	 * @return boolean
	 */
	static protected function isProductionEnvironment() {
		return GeneralUtility::getApplicationContext()->isProduction();
	}
	
}