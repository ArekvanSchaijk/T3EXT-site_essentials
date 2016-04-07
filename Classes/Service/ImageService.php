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

use Ucreation\SiteEssentials\Utility\CascadingStyleSheetUtility;

/**
 * Class ImageService
 *
 * @package Ucreation\SiteEssentials
 * @author Arek van Schaijk <info@ucreation.nl>
 * @api
 */
class ImageService {
	
	/**
	 * @var string
	 */
	protected $lastProcessedImageUri = NULL;
	
	/**
	 * @var string
	 */
	protected $src = NULL;
	
	/**
	 * @var FileInterface|AbstractFileFolder
	 */
	protected $image = NULL;
	
	/**
	 * @var string
	 */
	protected $width = NULL;

	/**
	 * @var string
	 */
	protected $height = NULL;
	
	/**
	 * @var int
	 */
	protected $minWidth = NULL;
	
	/**
	 * @var int
	 */
	protected $minHeight = NULL;
	
	/**
	 * @var int
	 */
	protected $maxWidth = NULL;
	
	/**
	 * @var int
	 */
	protected $maxHeight = NULL;
	
	/**
	 * @var bool
	 */
	protected $treatIdAsReference = FALSE;
	
	/**
	 * @var \TYPO3\CMS\Extbase\Service\ImageService
	 * @inject
	 */
	protected $imageService = NULL;
	
	/**
	 * Set Src
	 *
	 * @param string $src
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function setSrc($src) {
		$this->src = $src;
		return $this;
	}

	/**
	 * Set Image
	 *
	 * @param FileInterface|AbstractFileFolder $image
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function setImage($image) {
		$this->image = $image;
		return $this;
	}
	
	/**
	 * Set Width
	 *
	 * @param string $width
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function setWidth($width) {
		$this->width = $width;
		return $this;
	}
	
	/**
	 * Set Height
	 *
	 * @param string $height
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function setHeight($height) {
		$this->height = $height;
		return $this;
	}
	
	/**
	 * Set Min Width
	 *
	 * @param int $minWidth
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function setMinWidth($minWidth) {
		$this->minWidth = $minWidth;
		return $this;
	}
	
	/**
	 * Set Min Height
	 *
	 * @param int $minHeight
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function setMinHeight($minHeight) {
		$this->minHeight = $minHeight;
		return $this;
	}
	
	/**
	 * Set Max Width
	 *
	 * @param int $maxWidth
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function setMaxWidth($maxWidth) {
		$this->maxWidth = $maxWidth;
		return $this;
	}

	/**
	 * Set Max Height
	 *
	 * @param int $maxHeight
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function setMaxHeight($maxHeight) {
		$this->maxHeight = $maxHeight;
		return $this;
	}

	/**
	 * Set Image Settings
	 *
	 * @param array $imageSettings
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 */
	public function setImageSettings(array $imageSettings) {
		foreach ($imageSettings as $key => $value) {
			if ($value) {
				$methodName = 'set'.ucfirst($key);
				if (method_exists($this, $methodName)) {
					$this->$methodName($value);
				}
			}
		}
		return $this;
	}
	
	/**
	 * Set Treat Id As Reference
	 *
	 * @param bool $treatIdAsReference
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function setTreatIdAsReference($treatIdAsReference) {
		$this->treatIdAsReference = $treatIdAsReference;
		return $this;
	}

	/**
	 * Process
	 *
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 * @api
	 */
	public function process() {
		$options = array(
			'width'		=> $this->width,
			'height'	=> $this->height,
			'minWidth'	=> $this->minWidth,
			'minHeight'	=> $this->minHeight,
			'maxWidth'	=> $this->maxWidth,
			'maxHeight'	=> $this->maxHeight,
		);
		if (version_compare(TYPO3_branch, '7.0', '>=')) {
			$options['crop'] = $this->image->getOriginalResource()->getReferenceProperty('crop');
		}
		$this->lastProcessedImageUri = $this->imageService->getImageUri(
			$this->imageService->applyProcessingInstructions(
				$this->imageService->getImage($this->src, $this->image, $this->treatIdAsReference),
				$options
			)
		);
		return $this->reset();
	}
	
	/**
	 * Reset
	 *
	 * @return \Ucreation\SiteEssentials\Service\ImageService
	 */
	protected function reset() {
		unset(
			$this->src,
			$this->image,
			$this->width,
			$this->height,
			$this->minWidth,
			$this->minHeight,
			$this->maxWidth,
			$this->maxHeight,
			$this->treatIdAsReference
		);
		return $this;
	}
	
	/**
	 * Get
	 *
	 * @return string
	 * @api
	 */ 
	public function get() {
		return $this->lastProcessedImageUri;
	}
	
	/**
	 * Write In Css
	 *
	 * @param string $cssPath
	 * @return void
	 */
	public function writeInCss($cssPath) {
		CascadingStyleSheetUtility::setBackgroundImage($cssPath, $this->lastProcessedImageUri);
	}
	
}