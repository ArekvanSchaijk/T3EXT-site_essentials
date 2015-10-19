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

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class BaseController
 *
 * @package Ucreation\SiteEssentials
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class BaseController extends ActionController {

	/**
	 * Get Frontend Uri
	 *
	 * @param int $pageId
	 * @param array $arguments
	 * @param bool $addHost
	 * @return string
	 */
	protected function getFrontendUri($pageId, array $arguments = NULL, $addHost = FALSE) {
		$uri = $this->controllerContext->getUriBuilder();
		$uri->setTargetPageUid($pageId);
		$uri->setUseCacheHash(FALSE);
		if ($arguments) {
			$uri->setArguments($arguments);
		}
		$uri = rawurldecode($uri->build());
		if (strpos(substr($uri, 0, 1), '/') !== FALSE) {
			return ($addHost ? GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') : NULL).$uri;
		}
		return ($addHost ? GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') : NULL).'/'.$uri;		
	}

    /**
     * Get TypoScript Frontend Controller
     *
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 * @static
     */
    static protected function getTypoScriptFrontendController() {
		return $GLOBALS['TSFE'];
    }
	
	/**
	 * Get Database Connection
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 * @static
	 */
	static protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}
	
}