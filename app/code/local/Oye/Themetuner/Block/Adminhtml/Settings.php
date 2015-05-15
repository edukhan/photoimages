<?php
class Oye_Themetuner_Block_Adminhtml_Settings extends Mage_Adminhtml_Block_Widget_Container
{
    
	public function getPreset() {
        try {
            $id = Mage::app()->getRequest()->getParam('id', null);
            return Mage::getModel('themetuner/preset')->load($id);
            
        } catch (Exception $e) {
            Mage::logException($e);
            return null;
        }
    }
    
    public function getStores() {
        return Mage::getModel('core/store')->getCollection();
    }
}
?>
