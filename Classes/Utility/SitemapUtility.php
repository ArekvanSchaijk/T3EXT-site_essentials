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

/**
 * Class SitemapUtility
 *
 * @package Ucreation\SiteEssentials
 * @author Arek van Schaijk <info@ucreation.nl>
 * @api
 */
class SitemapUtility {
	
	/**
	 * @const int
	 */
	const	RECURSION_RECURSIVELY = 0,
			RECURSION_NONE = 1;
		
	/**
	 * @const int
	 */	
	const	CHANGEFREQ_DEFAULT = 0,
			CHANGEFREQ_ALWAYS = 1,
			CHANGEFREQ_HOURLY = 2,
			CHANGEFREQ_DAILY = 3,
			CHANGEFREQ_WEEKLY = 4,
			CHANGEFREQ_MONTHLY = 5,
			CHANGEFREQ_YEARLY = 6,
			CHANGEFREQ_NEVER = 7;
			
	/**
	 * @const string
	 */
	const	HOOK_POST_COLLECT_PAGES = 'postCollectPages',
			HOOK_POST_PROCESS_ITEMS = 'postProcessItems';
			
	/**
	 * @var array
	 */
	static protected $changeFrequencies = array(
		self::CHANGEFREQ_ALWAYS => 'always',
		self::CHANGEFREQ_HOURLY => 'hourly',
		self::CHANGEFREQ_DAILY => 'daily',
		self::CHANGEFREQ_WEEKLY => 'weekly',
		self::CHANGEFREQ_MONTHLY => 'monthly',
		self::CHANGEFREQ_YEARLY => 'yearly',
		self::CHANGEFREQ_NEVER => 'never',		
	);
	
	/**
	 * Get Change Frequency
	 *
	 * @param int $changeFrequency
	 * @param string $default
	 * @return string
	 * @static
	 * @api
	 */
	static public function getChangeFrequency($changeFrequency, $default) {
		if (self::$changeFrequencies[(int)$changeFrequency]) {
			return self::$changeFrequencies[(int)$changeFrequency];	
		}
		if (!in_array($default, self::$changeFrequencies)) {
			$default = $changeFrequencies[self::CHANGEFREQ_MONTHLY];
		}
		return $default;
	}

	/**
	 * Register Page Field
	 *
	 * @param string $fieldName
	 * @return void
	 * @static
	 * @api
	 */
	static public function registerSelectPageField($fieldName) {
		if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['page_fields'])) {
			$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['page_fields'] = array();	
		}
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['page_fields'][] = $fieldName;
	}
	
	/**
	 * Get Registred Page Fields
	 *
	 * @return array
	 * @static
	 */
	static public function getRegistredPageFields() {
		return ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['page_fields'] ? : array());
	}
	
	/**
	 * Register Hook Object
	 *
	 * @param string $hookName
	 * @param string $userFunc
	 * @return void
	 * @static
	 * @api
	 */
	static public function registerHookObject($hookName, $userFunc) {
		if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['hooks'][$hookName])) {
			$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['hooks'][$hookName] = array();
		}
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['hooks'][$hookName][] = $userFunc;
	}
	
	/**
	 * Get Registred Hook Objects
	 *
	 * @param string $hookName
	 * @return array
	 * @static
	 */
	static public function getRegistredHookObjects($hookName) {
		return ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['site_essentials']['hooks'][$hookName] ? : array());
	}
	
}