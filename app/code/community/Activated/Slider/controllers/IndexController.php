<?php
/**
 * Banner frontend controller
 * 
 * @author Activated
 */
class Activated_Slider_IndexController extends
Mage_Core_Controller_Front_Action
{
	/**
	 * Pre-dispatch action that allows to redirect to no route page in case
	 * of disabled extension through admin panel
	 */
	public function preDispatch()
	{
		if(!Mage::helper('slider')->isEnabled()) {
			$this->setFlag('', 'no-dispatch', true);
			$this->_redirect('noRoute');
		}
	}
	
	/**
	 * Index action
	 */
	public function indexAction()
	{
		$this->loadLayout();
		
		$this->renderLayout();
	}
}