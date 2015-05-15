<?php
/**
 * Slider item model
 * 
 * @author Activated Apps
 */

class Activated_Slider_Model_Reference extends Mage_Core_Model_Abstract
{
	/**
	 * Define resource model
	 */
	protected function _construct()
	{
		$this->_init('slider/reference');
	}
}