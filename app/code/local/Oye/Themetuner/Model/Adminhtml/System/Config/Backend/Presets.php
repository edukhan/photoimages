<?php
class Oye_Themetuner_Model_Adminhtml_System_Config_Backend_Presets
{
    public function toOptionArray()
    {
        $params = Mage::app()->getRequest()->getParams();
        
        if (isset($params['store'])) {
            $pathPrefix = "stores/{$params['store']}/";
        } else if (isset($params['website'])) {
            $pathPrefix = "websites/{$params['website']}/";
        } else { // default scope
            $pathPrefix = 'default/';
        }
        
        $package = (string)Mage::getConfig()->getNode($pathPrefix.'design/package/name');
        $locale = (string)Mage::getConfig()->getNode($pathPrefix.'design/theme/locale');
        $template = (string)Mage::getConfig()->getNode($pathPrefix.'design/theme/template');
        $skin = (string)Mage::getConfig()->getNode($pathPrefix.'design/theme/skin');
        $layout = (string)Mage::getConfig()->getNode($pathPrefix.'design/theme/layout');
        $default = (string)Mage::getConfig()->getNode($pathPrefix.'design/theme/default');

        $presets = Mage::helper('themetuner')->getPresets($package, $locale, $template, $skin, $layout, $default);

        $return = array(
            array('value' => 0, 'label' => '-')
        );
        foreach($presets as $preset) {
            $return[] = array('value' => $preset['value'], 'label' => $preset['label']);
        }
        return $return;
    }
}