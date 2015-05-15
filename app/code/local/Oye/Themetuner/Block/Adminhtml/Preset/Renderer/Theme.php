<?php
class Oye_Themetuner_Block_Adminhtml_Preset_Renderer_Theme extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $value = $row->getData($this->getColumn()->getIndex());
        $valueChunks = explode('|', $value);
        return $valueChunks[0].'/'.$valueChunks[2]; // package/theme
    }
}