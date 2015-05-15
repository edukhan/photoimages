<?php
/**
 * Banner list admin edit form image tab
 * 
 * @author Activated
 */
class Activated_Slider_Block_Adminhtml_Banner_Edit_Tab_Image extends
Mage_Adminhtml_Block_Widget_Form
implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	/**
	 * Prepare form elements
	 * 
	 * @return Mage_Adminhtml_Block_Widget_Form
	 */
	protected function _prepareForm()
	{
		/**
		 * Checking if users have permissions to save information
		 */
		if (Mage::helper('slider/admin')->isActionAllowed('save')) {
			$isElementDisabled = false;
		} else {
			$isElementDisabled = true;
		}
		
		$form = new Varien_Data_Form();
		
		$form->setHtmlIdPrefix('banner_image_');
		
		$model = Mage::helper('slider')->getBannerItemInstance();
		
		$fieldset = $form->addFieldset('image_fieldset', array(
			'legend'	=> Mage::helper('slider')->__('Image'),
			'class'		=> 'fieldset-wide'
		));
		
		$this->_addElementTypes($fieldset);
		
		$fieldset->addField('image', 'image', array(
			'name'		=> 'image',
			'label'		=> Mage::helper('slider')->__('Image'),
			'title'		=> Mage::helper('slider')->__('Image'),
			'required'	=> true,
			'disabled'	=> $isElementDisabled
		));
		
		Mage::dispatchEvent('adminhtml_banner_edit_tab_image_prepare_form',
				array('form' => $form));
		
		$form->setValues($model->getData());
		$this->setForm($form);
		
		return parent::_prepareForm();
	}
	
	/**
	 * Prepare label for tab
	 * 
	 * @return string
	 */
	public function getTabLabel()
	{
		return Mage::helper('slider')->__('Image');
	}
	
	/**
	 * Prepare title for tab
	 * 
	 * @return string
	 */
	public function getTabTitle()
	{
		return Mage::helper('slider')->__('Image');
	}
	
	/**
	 * Returns whether tab can be shown
	 * 
	 * @return true
	 */
	public function canShowTab()
	{
		return true;
	}
	
	/**
	 * Returns whether tab can be shown
	 * 
	 * @return true
	 */
	public function isHidden()
	{
		return false;
	}
	
	/**
	 * Retrieve predefined additional element types
	 * 
	 * @return array
	 */
	protected function _getAdditionalElementTypes()
	{
		return array(
			'image' => Mage::getConfig()->getBlockClassName('slider/adminhtml_banner_edit_form_element_image')
		);
	}
}