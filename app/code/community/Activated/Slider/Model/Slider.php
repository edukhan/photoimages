<?php
/**
 * Slider item model
 * 
 * @author Activated Apps
 */

class Activated_Slider_Model_Slider extends Mage_Core_Model_Abstract
{
	/**
	 * Define resource model
	 */
	protected function _construct()
	{
		$this->_init('slider/slider');
	}
	
	public function save()
	{
		$speed = $this->getConfSpeed();
		$title = $this->getTitle();
		
		// Validate speed
		if(!is_numeric($speed))
		{
			Mage::getSingleton('core/session')->addError("Speed must be set using numerical value only.");
			return false;
		}
		
		// Validate title
		if(preg_match('/[^-_. 0-9A-Za-z]/', $title)) {
			Mage::getSingleton('core/session')->addError("Title must be alphanumeric and contain only spaces, dashes, or underscores.");
			return false;
		}
		
		return parent::save();
	}
	
	/**
	 * Get banner positions by slider id
	 * 
	 * @param type $slider
	 * @return type
	 */
	public function getBannerPosition()
	{
		if (!$this->getId()) {
			return array();
		}
		
		$array = $this->getData('sliders_position');
		if (is_null($array)) {
			$array = $this->getResource()->getBannerPosition($this);
			$this->setData('sliders_position', $array);
		}
		return $array;
	}
	
	/**
	 * Get sliders for configuration
	 * 
	 * @return type
	 */
	public function toOptionArray()
	{
		$collection = $this->getCollection();
		$array = array(
			array('value' => 0, 'label' => 'None')
		);
		
		foreach ($collection as $slider) {
			$array[] = array('value' => $slider->getId(), 'label' => $slider->getTitle());
		}
			
		return $array;
	}
}