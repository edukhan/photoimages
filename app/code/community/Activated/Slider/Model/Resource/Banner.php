<?php
/**
 * Slide item resource model
 * 
 * @author Activated
 */

class Activated_Slider_Model_Resource_Banner extends 
Mage_Core_Model_Resource_Db_Abstract
{
	/**
	 * Initialize connection and define main table and primary key
	 */
	protected function _construct()
	{
		$this->_init('slider/banner', 'banner_id');
	}
}