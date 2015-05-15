<?php
/**
 * Slider list admin edit form block
 * 
 * @author Activated
 */
class Activated_Slider_Block_Adminhtml_Slider_Edit_Form extends
Mage_Adminhtml_Block_Widget_Form
{
	/**
	 * Prepare form action
	 * 
	 * @return Activated_Slider_Block_Adminhtml_Banner_Edit_Form
	 */
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form(array(
			'id'		=> 'edit_form',
			'action'	=> $this->getUrl('*/*/save'),
			'method'	=> 'post'
		));
		
		$form->setUseContainer(true);
		$this->setForm($form);
		$this->setTemplate('slider/form.phtml');
		return parent::_prepareForm();
	}
	
	/**
	 * Turn banner position into JSON string
	 * 
	 * @return string
	 */
	public function getBannerJson()
	{
		$slider = Mage::registry('slider');
		$banners = $slider->getBannerPosition();;
		
		if (!empty($banners)) {
			return Mage::helper('core')->jsonEncode($banners);
		}
		return '{}';
	}
}