<?php
/**
 * Slider list admin edit form main tab
 * 
 * @author Activated
 */
class Activated_Slider_Block_Adminhtml_Slider_Edit_Tab_Main
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
		$model = Mage::helper('slider')->getSliderItemInstance();
		
		/**
		 * Checking if users have permission to save information
		 */
		if (Mage::helper('slider/admin')->isActionAllowed('save')) {
			$isElementDisabled = false;
		} else {
			$isElementDisabled = true;
		}
		
		$form = new Varien_Data_Form();
		
		$form->setHtmlIdPrefix('slider_main_');
		
		$fieldset = $form->addFieldset('base_fieldset', array(
			'legend' => Mage::helper('slider')->__('Slider Item Info')
		));
		
		if ($model->getId()) {
			$fieldset->addField('slider_id', 'hidden', array(
				'name' => 'slider_id'
			));
		}
		
		$fieldset->addField('title', 'text', array(
			'name'		=> 'title',
			'label'		=> Mage::helper('slider')->__('Title'),
			'title'		=> Mage::helper('slider')->__('Title'),
			'required'	=> true,
			'disabled'	=> $isElementDisabled
		));
		
		$fieldset->addField('status', 'select', array(
			'label'		=> Mage::helper('slider')->__('Status'),
			'name'		=> 'status',
			'values'	=> array('1' => 'Enabled', '0' => 'Disabled'),
			'value'		=> 1,
			'disabled'	=> $isElementDisabled
		));
		
		$fieldset->addField('conf_effect', 'select', array(
			'name'		=> 'conf_effect',
			'label'		=> Mage::helper('slider')->__('Effect'),
			'value'		=> 'fade',
			'values'	=> array('fade' => 'Fade', 'slide' => 'Slide'),
			'disabled'	=> $isElementDisabled
		));
		
		$fieldset->addField('conf_speed', 'text', array(
			'name'		=> 'conf_speed',
			'label'		=> Mage::helper('slider')->__('Speed'),
			'title'		=> Mage::helper('slider')->__('Speed'),
			'value'		=> '5000',
			'required'	=> 'true',
			'disabled'	=> $isElementDisabled
		));
		
		$fieldset->addField('conf_navigation', 'select', array(
			'label'		=> Mage::helper('slider')->__('Use Control Navigation'),
			'name'		=> 'conf_navigation',
			'value'		=> '0',
			'values'	=> array('1' => 'Enabled', '0' => 'Disabled'),
			'disabled'	=> $isElementDisabled
		));
		
		$fieldset->addField('conf_pause', 'select', array(
			'label'		=> Mage::helper('slider')->__('Pause On Hover'),
			'name'		=> 'conf_pause',
			'value'		=> '0',
			'values'	=> array('1' => 'Enabled', '0' => 'Disabled'),
			'disabled'	=> $isElementDisabled
		));
		
		$fieldset->addField('in_position', 'hidden', array(
			'name'		=> 'in_position'
		));
	
		Mage::dispatchEvent('adminhtml_slider_edit_tab_main_prepare_form',
				array('form' => $form));
		
		if ($model->getId()) {
			$form->setValues($model->getData());
		}
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
		return Mage::helper('slider')->__('Slider Info');
	}
	
	/**
	 * Prepare title for tab
	 * 
	 * @return string
	 */
	public function getTabTitle()
	{
		return Mage::helper('slider')->__('Slider Info');
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