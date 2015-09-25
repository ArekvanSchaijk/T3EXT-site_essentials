<?php
namespace Ucreation\SiteEssentials\Utility;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class CascadingStyleSheetUtility
 *
 * @package Ucreation\SiteEssentials
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class CascadingStyleSheetUtility {
	
	/**
	 * Set Content
	 *
	 * @param string $content
	 *
	 * @return void
	 * @static
	 * @api
	 */
	static public function setContent($content) {
		if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['css_content'])) {
			$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['css_content'] = array();	
		}
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['css_content'][] = $content;
	}
	
	/**
	 * Set Background Image
	 *
	 * @param string $cssPath
	 * @param string $backgroundImagePath
	 *
	 * @return void
	 * @static
	 * @api
	 */
	static public function setBackgroundImage($cssPath, $backgroundUrl) {
		if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['css_content'])) {
			$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['css_content'] = array();	
		}
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['css_content'][]
			= $cssPath.chr(32).'{'.chr(32).'background-image: url(\'/'.$backgroundUrl.'\');'.chr(32).'}';
	}
	
	/**
	 * Render File
	 *
	 * @return void
	 * @static
	 */
	static public function renderFile() {
		if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['css_content']) {
			self::getTypoScriptFrontendController()->getPageRenderer()->addCssFile(
				self::inline2TempFile(implode(LF, $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['css_content']), 'css')
			);	
		}
	}
	
	/**
	 * Inline 2 Temp File
	 *
	 * @param string $str
	 * @param string $ext
	 *
	 * @return string
	 * @static
	 */
	static protected function inline2TempFile($str, $ext) {
		// Create filename / tags:
		$script = '';
		switch ($ext) {
			case 'js':
				$script = 'typo3temp/site_essentials_' . substr(md5($str), 0, 10) . '.js';
				break;
			case 'css':
				$script = 'typo3temp/site_essentials_' . substr(md5($str), 0, 10) . '.css';
				break;
		}
		// Write file:
		if ($script) {
			if (!@is_file((PATH_site . $script))) {
				GeneralUtility::writeFile(PATH_site . $script, $str);
			}
		}
		return $script;
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
	
}