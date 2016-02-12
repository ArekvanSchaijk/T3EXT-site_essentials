<?php
namespace Ucreation\SiteEssentials\TypoScript;

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

use Ucreation\SiteEssentials\Utility\BrowserUtility;
use TYPO3\CMS\Core\Configuration\TypoScript\ConditionMatching\AbstractCondition;

/**
 * Class InternetExplorer
 *
 * @package Ucreation\SiteEssentials
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class InternetExplorer extends AbstractCondition {
	
	/**
	 * Match Condition
	 *
	 * @param array $conditionParameters
	 * @return boolean
	 */
	public function matchCondition(array $conditionParameters) {
		if ($conditionParameters[0] && substr($conditionParameters[0], 0, 1) == '=') {
			$method = 'isInternetExplorer'.trim(substr($conditionParameters[0], 1));
			if (method_exists(BrowserUtility::class, $method)) {
				return BrowserUtility::$method();
			}
		}
		return FALSE;
	}
	
}