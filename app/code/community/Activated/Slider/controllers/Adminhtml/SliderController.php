<?php
/**
 * Slider controller
 * 
 * @author Activated
 */

class Activated_Slider_Adminhtml_SliderController extends Mage_Adminhtml_Controller_Action
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
		->_setActiveMenu('slider/manage');
		return $this;
	}
	
	/**
	 * Index action
	 */
	public function indexAction()
	{
		$this->_title($this->__('Slider'))->_title($this->__('Manage Slider'));
		
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
		$this->_title($this->__('Slider'))->_title($this->__('Manage Slider'));
		
		$model = Mage::getModel('slider/slider');
		
		$sliderId = $this->getRequest()->getParam('id');
		
		if ($sliderId) {
			$model->load($sliderId);
			
			if (!$model->getId()) {
				$this->_getSession()->addError(
					Mage::helper('slider')->__('Slider does not exist.')
				);
				return $this->_redirect('*/*/');
			}
			$this->_title($model->getTitle());
			$breadCrumb = Mage::helper('slider')->__('Edit Slider');
		} else {
			$this->_title(Mage::helper('slider')->__('New Slider'));
			$breadCrumb = Mage::helper('slider')->__('New Slider');
		}
		
		// Init breadcrumbs
		$this->_initAction()->_addBreadcrumb($breadCrumb, $breadCrumb);
		
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data)) {
			$model->addData($data);
		}
		
		Mage::register('slider', $model);
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
			$model = Mage::getModel('slider/slider');
			$referenceModel = Mage::getModel('slider/reference');
			$sliderId = $this->getRequest()->getParam('slider_id');
			$reference = Mage::getModel('slider/reference')
					->getCollection()
					->AddFieldToFilter('slider_id', $sliderId);
			
			if ($sliderId) {
				$model->load($sliderId);
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
				
				// Create array of current banner associations
				$current_ref = array();
				foreach($reference as $item) {
					$current_ref[] = $item->getBannerId();
				}
				
				// Get position info
				if (isset($data['in_position'])) {
					$position = array();
					parse_str($data['in_position'], $position);
				}
				
				// Save associated banners
				if (!empty($data['banner_referenced'])) {
					foreach($data['banner_referenced'] as $item) {
						$referenceId = $referenceModel->getCollection()
								->addFieldToFilter('slider_id', $model->getId())
								->addFieldToFilter('banner_id', $item)
								->getFirstItem()
								->getId();
						
						// Check if association already saved
						if($referenceId) {
							$referenceModel->setId($referenceId);
						}
						$referenceModel->setSliderId($model->getId());
						$referenceModel->setBannerId($item);
						
						if (isset($position[$item])) {
							$referenceModel->setPosition($position[$item]);
						}
						
						$referenceModel->save();
						$referenceModel->unsetData();
					}
					
					// Clean up de-selected
					foreach($reference as $item) {
						if (!in_array($item->getBannerId(), $data['banner_referenced'])) {
							$item->delete();
						}
					}
				} else {
					// Delete if associated banners de-selected
					foreach($reference as $item) {
						$item->delete();
					}
				}
				
				// display success message
				$this->_getSession()->addSuccess(
					Mage::helper('slider')->__('The slider has been saved.')
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
					Mage::helper('slider')->__('An error occurred while saving the slider.')
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
				$model = Mage::getModel('slider/slider');
				$model->load($itemId);
				if (!$model->getId()) {
					Mage::throwException(Mage::helper('slider')->__('Unable to find a slider.'));
				}
				$model->delete();
				
				// display success message
				$this->_getSession()->addSuccess(
					Mage::helper('slider')->__('The slider has been deleted.')
				);
			} catch (Mage_Core_Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			} catch (Exception $e) {
				$this->_getSession()->addException($e,
					Mage::helper('slider')->__('An error occurred while deleting the slider.')
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
				return Mage::getSingleton('admin/session')->isAllowed('slider/manage/save');
				break;
			case 'delete':
				return Mage::getSingleton('admin/session')->isAllowed('slider/manage/delete');
				break;
			default:
				return Mage::getSingleton('admin/session')->isAllowed('slider/manage');
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
		$model = Mage::getModel('slider/slider');
		
		$sliderId = $this->getRequest()->getParam('id');

		if ($sliderId) {
			$model->load($sliderId);
			Mage::register('slider', $model);
		}

		$this->loadLayout();
		$this->renderLayout();
	}
}