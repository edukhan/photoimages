<?php
class Oye_Themetuner_Block_Adminhtml_Preset extends Mage_Adminhtml_Block_Widget_Container
{
    
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('themetuner/grid.phtml');
    }
	protected function _prepareLayout()
    {
        $this->setChild('grid', $this->getLayout()->createBlock('themetuner/adminhtml_preset_grid', 'preset.grid'));
        return parent::_prepareLayout();
    }
    
 	public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_new_button');
    }
    
	public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }
}
?>
