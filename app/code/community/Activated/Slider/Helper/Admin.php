<?php
/**
 * Slider admin helper
 * 
 * @author Activated
 */
class Activated_Slider_Helper_Admin extends Mage_Core_Helper_Abstract
{
	/**
	 * Check permission for passed action
	 */
	public function isActionAllowed($action)
	{
		return Mage::getSingleton('admin/session')->isAllowed('slider/manage/' . $action);
	}

	public function getTable($table) {
		return Mage::getSingleton('core/resource')->getTableName($table);
	}
}