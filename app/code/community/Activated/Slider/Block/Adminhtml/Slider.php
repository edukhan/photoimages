<?php
/**
 * Slider list admin grid container
 */
class Activated_Slider_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	/**
	 * Block constructor
	 */
	public function __construct()
	{
		$this->_blockGroup = 'slider';
		$this->_controller = 'adminhtml_slider';
		$this->_headerText = Mage::helper('slider')->__('Manage Slider');
		
		parent::__construct();
		
		if (Mage::helper('slider/admin')->isActionAllowed('save')) {
			$this->_updateButton('add', 'label', Mage::helper('slider')->__('Add New Slider'));
		} else {
			$this->_removeButton('add');
		}
	}
}