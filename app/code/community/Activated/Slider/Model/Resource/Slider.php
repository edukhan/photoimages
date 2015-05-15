<?php
/**
 * Slider item resource model
 * 
 * @author Activated Apps
 */

class Activated_Slider_Model_Resource_Slider extends 
Mage_Core_Model_Resource_Db_Abstract
{	
	/**
	 * Slider reference table
	 * 
	 * @return string
	 */
	
	protected $_referenceTable;
	
	/**
	 * Initialize connection and define main table and primary key
	 */
	protected function _construct()
	{
		$this->_init('slider/slider', 'slider_id');
		
		$this->_referenceTable = $this->getTable('slider/reference');
	}
	
	/**
	 * Get banner positions by slider id
	 * 
	 * @param type $slider
	 * @return type
	 */
	public function getBannerPosition($slider)
	{
		$select = $this->_getWriteAdapter()->select()
				->from(array('main_table' => $this->_referenceTable), array('banner_id', 'position'))
				->where('main_table.slider_id = ?' , 1);
		$bind = array('slider_id' => (int)$slider->getId());
		
		return $this->_getWriteAdapter()->fetchPairs($select, $bind);
	}
}