<?php
/**
 * System config form block, override by Oye.
 * Added "Tunelist" frontend type
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>
 */
class Oye_Themetuner_Block_Adminhtml_System_Config_Form extends Mage_Adminhtml_Block_System_Config_Form
{
    /**
     * Enter description here...
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        $types = parent::_getAdditionalElementTypes();
        $types['themetuner_presetlist'] = Mage::getConfig()->getBlockClassName('themetuner/adminhtml_system_config_form_field_select_presetlist');
        $types['themetuner_themelist'] = Mage::getConfig()->getBlockClassName('themetuner/adminhtml_system_config_form_field_select_themelist');
        return $types;
    }

}
