<?php
/**
 * Slides item model
 * 
 * @author Activated
 */

class Activated_Slider_Model_Banner extends Mage_Core_Model_Abstract
{
	/**
	 * Define resource model
	 */
	protected function _construct()
	{
		$this->_init('slider/banner');
	}
}