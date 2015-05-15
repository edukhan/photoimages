<?php
/**
 * Banner list admin edit form container
 * 
 * @author Activated
 */
class Activated_Slider_Block_Adminhtml_Banner_Edit extends
Mage_Adminhtml_Block_Widget_Form_Container
{
	/**
	 * Initialize edit form container
	 */
	public function __construct()
	{
		$this->_objectId	= 'id';
		$this->_blockGroup	= 'slider';
		$this->_controller	= 'adminhtml_banner';
		
		parent::__construct();
		
		if (Mage::helper('slider/admin')->isActionAllowed('save')) {
			$this->_updateButton('save', 'label', 
			Mage::helper('slider')->__('Save Banner'));
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
			Mage::helper('slider')->__('Delete Slide'));
		} else {
			$this->_removeButton('delete');
		}
		
		$this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('page_content') == null) {
					tinyMCE.execCommand('mceAddControl', false, 'page_content');
				} else {
					tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
				}
			}
			
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
		$model = Mage::helper('slider')->getBannerItemInstance();
		if ($model->getId()) {
			return Mage::helper('slider')->__("Edit Banner '%s'", $this->escapeHtml($model->getTitle()));
		} else {
			return Mage::helper('slider')->__('New Banner Item');
		}
	}
}