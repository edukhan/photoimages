<?php
/**
 * Banner list admin grid container
 */
class Activated_Slider_Block_Adminhtml_Banner 
extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	/**
	 * Block constructor
	 */
	public function __construct()
	{
		$this->_blockGroup = 'slider';
		$this->_controller = 'adminhtml_banner';
		$this->_headerText = Mage::helper('slider')->__('Manage Banner');
		
		parent::__construct();
		
		if (Mage::helper('slider/admin')->isActionAllowed('save')) {
			$this->_updateButton('add', 'label', Mage::helper('slider')->__('Add New Banner'));
		} else {
			$this->_removeButton('add');
		}
		$this->_addButton(
				'slider_flush_images_cache',
				array(
					'label'		=> Mage::helper('slider')->__('Flush Images Cache'),
					'onclick'	=> 'setLocation(\'' . $this->getUrl('*/*/flush') . '\')',
				)
		);
	}
}