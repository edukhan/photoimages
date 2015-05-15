<?php
/**
 * Banner controller
 * 
 * @author Activated
 */
class Activated_Slider_Adminhtml_BannerController
extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Init actions
	 * 
	 * @return Activated_Banner_Adminhtml_SliderController
	 */
	protected function _initAction()
	{
		// load layout, set active menu and breadcrumbs
		$this->loadLayout()
		->_setActiveMenu('slider/banner');
		return $this;
	}
	
	/**
	 * Index action
	 */
	public function indexAction()
	{
		$this->_title($this->__('Banner'))->_title($this->__('Manage Banner'));
		
		$this->_initAction();
		$this->renderLayout();
	}
	
	/**
	 * Create new slide
	 */
	public function newAction()
	{
		$this->_forward('edit');
	}
	
	/**
	 * Edit slide
	 */
	public function editAction()
	{
		$this->_title($this->__('Banner'))->_title($this->__('Manage Banner'));
		
		$model = Mage::getModel('slider/banner');
		
		$bannerId = $this->getRequest()->getParam('id');
		
		if ($bannerId) {
			$model->load($bannerId);
			
			if (!$model->getId()) {
				$this->_getSession()->addError(
					Mage::helper('slider')->__('Banner does not exist.')
				);
				return $this->_redirect('*/*/');
			}
			$this->_title($model->getTitle());
			$breadCrumb = Mage::helper('slider')->__('Edit Banner');
		} else {
			$this->_title(Mage::helper('slider')->__('New Banner'));
			$breadCrumb = Mage::helper('slider')->__('New Banner');
		}
		
		// Init breadcrumbs
		$this->_initAction()->_addBreadcrumb($breadCrumb, $breadCrumb);
		
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data)) {
			$model->addData($data);
		}
		
		Mage::register('banner', $model);
		$this->renderLayout();
	}
	
	/**
	 * Save action
	 */
	public function saveAction()
	{
		$redirectPath	= '*/*';
		$redirectParams	= array();
		
		// check if data sent
		$data = $this->getRequest()->getPost();
		if ($data) {
			$data = $this->_filterPostData($data);
			$model = Mage::getModel('slider/banner');
			
			$bannerId = $this->getRequest()->getParam('banner_id');
			
			if ($bannerId) {
				$model->load($bannerId);
			}
			
			if (isset($data['image'])) {
				$imageData = $data['image']; 
				unset($data['image']);
			} else {
				$imageData = array();
			}
			$model->addData($data);
			
			try {
				$hasError = false;
				
				$imageHelper = Mage::helper('slider/image');
				
				if (isset($imageData['delete']) && $model->getImage()) {
					$imageHelper->removeImage($model->getImage());
					$model->setImage(null);
				}
				
				// upload new image
				$imageFile = $imageHelper->uploadImage('image');
				if ($imageFile) {
					if ($model->getImage()) {
						$imageHelper->removeImage($model->getImage());
					}
					$model->setImage($imageFile);
				}
				$model->save();
				
				// display success message
				$this->_getSession()->addSuccess(
					Mage::helper('slider')->__('The banner has been saved.')
				);
				
				// check if 'Save and Continue'
				if ($this->getRequest()->getParam('back')) {
					$redirectPath	= '*/*/edit';
					$redirectParams	= array('id' => $model->getId());
				}
			} catch (Mage_Core_Exception $e) {
				$hasError = true;
				$this->_getSession()->addError($e->getMessage());
			} catch (Exception $e) {
				$hasError = true;
				$this->_getSession()->addException($e,
					Mage::helper('slider')->__('An error occurred while saving the banner.')
				);
			}
			
			if ($hasError) {
				$this->_getSession()->setFormData($data);
				$redirectPath	= '*/*/edit';
				$redirectParams	= array('id' => $this->getRequest()->getParam('id'));
			}
		}
		
		$this->_redirect($redirectPath, $redirectParams);
	}
	
	/**
	 * Delete action
	 */
	public function deleteAction()
	{
		// check if we know what should be deleted
		$itemId = $this->getRequest()->getParam('id');
		if ($itemId) {
			try {
				// init model and delete
				$model = Mage::getModel('slider/banner');
				$model->load($itemId);
				if (!$model->getId()) {
					Mage::throwException(Mage::helper('slider')->__('Unable to find the banner.'));
				}
				$model->delete();
				
				// display success message
				$this->_getSession()->addSuccess(
					Mage::helper('slider')->__('The banner has been deleted.')
				);
			} catch (Mage_Core_Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			} catch (Exception $e) {
				$this->_getSession()->addException($e,
					Mage::helper('slider')->__('An error occurred while deleting the banner.')
				);
			}
		}
		
		// go to grid
		$this->_redirect('*/*/');
	}
	
	/**
	 * Check the permission to run it
	 * 
	 * $return boolean
	 */
	protected function _isAllowed()
	{
		switch ($this->getRequest()->getActionName()) {
			case 'new':
			case 'save':
				return Mage::getSingleton('admin/session')->isAllowed('slider/banner/save');
				break;
			case 'delete':
				return Mage::getSingleton('admin/session')->isAllowed('slider/banner/delete');
				break;
			default:
				return Mage::getSingleton('admin/session')->isAllowed('slider/banner');
				break;
		}
	}
	
	/**
	 * Filtering posted data. Converting localized data if need
	 * 
	 * @param array
	 * @return array
	 */
	protected function _filterPostData($data)
	{
		$data = $this->_filterDates($data, array('time_published'));
		return $data;
	}
	
	/**
	 * Grid ajax action
	 */
	public function gridAction() 
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	
	/**
	 * Flush slide image cache
	 */
	public function flushAction()
	{
		if (Mage::helper('slider/image')->flushImagesCache()) {
			$this->_getSession()->addSuccess('Cache successfully flushed.');
		} else {
			$this->_getSession()->addError('There was error during flushing cache.');
		}
		$this->_forward('index');
	}
}