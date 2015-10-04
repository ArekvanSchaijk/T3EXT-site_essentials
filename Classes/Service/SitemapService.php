<?php
namespace Ucreation\SiteEssentials\Service;

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
 * Class SitemapService
 *
 * @package Ucreation\SiteEssentials
 * @author Arek van Schaijk <info@ucreation.nl>
 * @api
 */
class SitemapService {
	
	/**
	 * @var \SimpleXMLElement
	 */
	protected $xml = NULL;
	
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
	}
	
	/**
	 * Process Items
	 *
	 * @return void
	 */
	public function processItems(array $items) {
		foreach ($items as $item) {
			$url = $this->xml->addChild('url');
			$url->addChild(
				'loc',
				str_replace(
					array(
						chr(38),
						chr(39),
						chr(34),
						chr(60),
						chr(62)
					), 
					array(
						'&amp;',
						'&apos;',
						'&quot;',
						'&gt;',
						'&lt;'
					),
					$item['loc']
				)
			);
			$url->addChild('lastmod', $item['lastmod']);
			$url->addChild('changefreq', $item['changefreq']);
			$url->addChild('priority', $item['priority']);
		}
	}
	
	/**
	 * Get Xml
	 *
	 * @param bool $formatOutput
	 *
	 * @return string
	 */
	public function getXml($formatOutput = TRUE) {
		if ($formatOutput) {	
			$dom = new \DOMDocument('1.0');
			$dom->preserveWhiteSpace = FALSE;
			$dom->formatOutput = TRUE;
			$dom->loadXML($this->xml->asXML());
			return $dom->saveXML();
		}
		return $this->xml->asXML();
	}
	
}