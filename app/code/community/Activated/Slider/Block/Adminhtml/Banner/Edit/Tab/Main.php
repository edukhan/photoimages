<?php
/**
 * Banner list admin edit form main tab
 * 
 * @author Activated
 */
class Activated_Slider_Block_Adminhtml_Banner_Edit_Tab_Main
extends Mage_Adminhtml_Block_Widget_Form
implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	/**
	 * Prepare form elements for tab
	 * 
	 * @return Mage_Adminhtml_Block_Widget_Form
	 */
	protected function _prepareForm()
	{
		$model = Mage::helper('slider')->getBannerItemInstance();
		
		/**
		 * Checking if users have permission to save information
		 */
		if (Mage::helper('slider/admin')->isActionAllowed('save')) {
			$isElementDisabled = false;
		} else {
			$isElementDisabled = true;
		}
		
		$form = new Varien_Data_Form();
		
		$form->setHtmlIdPrefix('banner_main_');
		
		$fieldset = $form->addFieldset('base_fieldset', array(
			'legend' => Mage::helper('slider')->__('Banner Item Info')
		));
		
		if ($model->getId()) {
			$fieldset->addField('banner_id', 'hidden', array(
				'name' => 'banner_id'
			));
		}
		
		$fieldset->addField('title', 'text', array(
			'name'		=> 'title',
			'label'		=> Mage::helper('slider')->__('Banner Title'),
			'title'		=> Mage::helper('slider')->__('Banner Title'),
			'required'	=> true,
			'disabled'	=> $isElementDisabled
		));
		
		$fieldset->addField('link', 'text', array(
			'name'		=> 'link',
			'label'		=> Mage::helper('slider')->__('Link'),
			'title'		=> Mage::helper('slider')->__('Link'),
			'required'	=> false,
			'disabled'	=> $isElementDisabled
		));
		
		Mage::dispatchEvent('adminhtml_banner_edit_tab_main_prepare_form',
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
		return Mage::helper('slider')->__('Banner Info');
	}
	
	/**
	 * Prepare title for tab
	 * 
	 * @return string
	 */
	public function getTabTitle()
	{
		return Mage::helper('slider')->__('Banner Info');
	}
	
	/**
	 * Return whether tab can be shown
	 * 
	 * @return bool
	 */
	public function canShowTab()
	{
		return true;
	}
	
	/**
	 * Return whether tab can be shown
	 * 
	 * @return bool
	 */
	public function isHidden()
	{
		return false;
	}
}