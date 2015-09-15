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
 * Class BrowserUtility
 *
 * @package Ucreation\SiteEssentials
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class BrowserUtility {
	
	/**
	 * Is Internet Explorer 6
	 *
	 * @return array
	 */
	static public function isInternetExplorer6() {		
		return (preg_match('/(?i)msie [6]/', GeneralUtility::getIndpEnv('HTTP_USER_AGENT')) ? TRUE : FALSE);
	}
	
	/**
	 * Is Internet Explorer 7
	 *
	 * @return array
	 */
	static public function isInternetExplorer7() {		
		return (preg_match('/(?i)msie [7]/', GeneralUtility::getIndpEnv('HTTP_USER_AGENT')) ? TRUE : FALSE);
	}
	
	/**
	 * Is Internet Explorer 8
	 *
	 * @return array
	 */
	static public function isInternetExplorer8() {		
		return (preg_match('/(?i)msie [8]/', GeneralUtility::getIndpEnv('HTTP_USER_AGENT')) ? TRUE : FALSE);
	}
	
	/**
	 * Is Internet Explorer 9
	 *
	 * @return array
	 */
	static public function isInternetExplorer9() {		
		return (preg_match('/(?i)msie [9]/', GeneralUtility::getIndpEnv('HTTP_USER_AGENT')) ? TRUE : FALSE);
	}
	
	/**
	 * Is Internet Explorer 10
	 *
	 * @return array
	 */
	static public function isInternetExplorer10() {		
		return (preg_match('/(?i)msie [10]/', GeneralUtility::getIndpEnv('HTTP_USER_AGENT')) ? TRUE : FALSE);
	}
	
}