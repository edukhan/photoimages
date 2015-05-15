<?php
/**
 * Slider list admin edit form container
 * 
 * @author Activated
 */
class Activated_Slider_Block_Adminhtml_Slider_Edit extends
Mage_Adminhtml_Block_Widget_Form_Container
{
	/**
	 * Initialize edit form container
	 */
	public function __construct()
	{
		$this->_objectId	= 'id';
		$this->_blockGroup	= 'slider';
		$this->_controller	= 'adminhtml_slider';
	
		parent::__construct();

		if (Mage::helper('slider/admin')->isActionAllowed('save')) {
			$this->_updateButton('save', 'label',
				Mage::helper('slider')->__('Save Slider'));
			$this->_addButton('saveandcontinue', array(
				'label'		=> Mage::helper('adminhtml')->__('Save and Continue'),
				'onclick'	=> 'saveAndContinueEdit()',
				'class'		=> 'save'
			), -100);
		} else {
			$this->_removeButton('save');
		}

		if (Mage::helper('slider/admin')->isActionAllowed('delete')) {
			$this->_updateButton('delete', 'label',
				Mage::helper('slider')->__('Delete Slider'));
		} else {
			$this->_removeButton('delete');
		}
		
		$this->_formScripts[] = "
			function saveAndContinueEdit() {
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}
	
	/**
	 * Retreive text for header element depending on loaded page
	 * 
	 * @return string
	 */
	public function getHeaderText()
	{
		$model = Mage::helper('slider')->getSliderItemInstance();
		if ($model->getId()) {
			return Mage::helper('slider')->__("Edit Slider '%s'", $this->escapeHtml($model->getTitle()));
		} else {
			return Mage::helper('slider')->__('New Slider');
		}
	}
}