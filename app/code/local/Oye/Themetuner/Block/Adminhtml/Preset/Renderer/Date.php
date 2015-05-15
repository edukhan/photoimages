<?php
class Oye_Themetuner_Block_Adminhtml_Preset_Renderer_Date extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $value = $row->getData($this->getColumn()->getIndex());
        return Mage::helper('core')->formatDate($value, $format = Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, false);
    }
}