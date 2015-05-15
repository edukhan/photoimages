<?php
/**
 * Slider data helper
 * 
 * @author Activated
 */

class Activated_Slider_Helper_Data extends
Mage_Core_Helper_Abstract
{
	/**
	 * Path to store config if frontend output is enabled
	 * 
	 * @var string
	 */
	const XML_PATH_ENABLED				= 'slider/view/enabled';
	
	/**
	 * Slider item instance for lazy loading
	 * 
	 * @var Activated_Slider_Model_Slides
	 */
	protected $_sliderItemInstance;
	
	/**
	 * Banner item instance for lazy loading
	 * 
	 * @var Activated_Slider_Model_Banner
	 */
	protected $_bannerItemInstance;
	
	/**
	 * Checks whether banner can be displayed in frontend
	 * 
	 * @param integer|string|Mage_Core_Model_Store $store
	 * @return boolean
	 */
	public function isEnabled($store = null)
	{
		return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store);
	}
	
	/**
	 * Return current slider instance from registry
	 * 
	 * @return Activated_Slider_Model_Banner
	 */
	public function getSliderItemInstance()
	{
		if (!$this->_sliderItemInstance) {
			$this->_sliderItemInstance = Mage::registry('slider');
			
			if (!$this->_sliderItemInstance) {
				Mage::throwException($this->__('Slider item instance does not exist in registry.'));
			}
		}
		return $this->_sliderItemInstance;
	}
	
	public function getBannerItemInstance()
	{
		if (!$this->_bannerItemInstance) {
			$this->_bannerItemInstance = Mage::registry('banner');
			
			if (!$this->_bannerItemInstance) {
				Mage::throwException($this->__('Banner item instance does not exist in registry.'));
			}
		}
		return $this->_bannerItemInstance;
	}
	
	/**
	 * Get slider id for home page
	 * 
	 * @return integer
	 */
	public function getHomeId()
	{
		Mage::log(Mage::getStoreConfig('slider/placement/home'), null, 'banners.log');
		return Mage::getStoreConfig('slider/placement/home');
	}
}