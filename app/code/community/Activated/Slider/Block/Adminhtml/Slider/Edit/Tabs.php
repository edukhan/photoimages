<?php
/**
 * Slider list admin edit form tabs block
 * 
 * @author Activated
 */
class Activated_Slider_Block_Adminhtml_Slider_Edit_Tabs extends
Mage_Adminhtml_Block_Widget_Tabs
{
	/**
	 * Initialize tabs and define tabs block settings
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setId('page_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('slider')->__('Slider Item Info'));
	}
}