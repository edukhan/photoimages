<?php
/**
 * Slider item resource model
 * 
 * @author Activated Apps
 */

class Activated_Slider_Model_Resource_Reference extends 
Mage_Core_Model_Resource_Db_Abstract
{	
	/**
	 * Initialize connection and define main table and primary key
	 */
	protected function _construct()
	{
		$this->_init('slider/reference', 'reference_id');
	}
}