<?php
class Oye_Themetuner_Model_Adminhtml_System_Config_Backend_Themes
{
    public function toOptionArray() {
        return Mage::helper('themetuner/theme')->getThemeList(array(
            array('value' => '', 'label' => '-')
        ));
    }
}