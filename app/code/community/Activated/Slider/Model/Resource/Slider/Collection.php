<?php
/**
 * Slides collection
 * 
 * @author Activated
 */

class Activated_Slider_Model_Resource_Slider_Collection extends
Mage_Core_Model_Resource_Db_Collection_Abstract
{
	/**
	 * Define collection model
	 */
	protected function _construct()
	{
		$this->_init('slider/slider');
	}
	
	/**
	 * Prepare for displaying in list
	 * 
	 * @param integer $page
	 * @return Activated_Banner_Model_Resource_Banner_Collection
	 */
	public function prepareForList($page)
	{
		$this->setCurPage($page)->setOrder('position',
				Varien_Data_Collection::SORT_ORDER_DESC);
		return $this;
	}
}