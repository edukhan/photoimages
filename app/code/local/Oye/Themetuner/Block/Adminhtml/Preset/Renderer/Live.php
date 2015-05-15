<?php
class Oye_Themetuner_Block_Adminhtml_Preset_Renderer_Live extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $preset_id = $row->getData('preset_id'); // tune id
        return Mage::helper('themetuner')->getPresetLives($preset_id, $row);
    }
}