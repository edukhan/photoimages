<?php
class Oye_Themetuner_IndexController extends Mage_Core_Controller_Front_Action
{
    public function cssAction() {
        
        $tempPresetID = $this->getRequest()->getParam('preset_id', 0);
        $inAdmin = $this->getRequest()->getParam('in_admin', false);
        
        $presetID = $inAdmin ? $tempPresetID : Mage::getStoreConfig('design/theme/themetuner_preset');
        
        $cssArray = array();
        $css = '';
        if ($presetID > 0) {
            $mainSerializedCss = Mage::getModel('themetuner/preset')->load($presetID)->getCss();
            $cssArray = json_decode($mainSerializedCss, true);

            if (!empty($cssArray)) {
                foreach ($cssArray as $element) {
                    $css .= $element["name"] . " {\n";
                    if (!empty($element["styles"])) {
                        foreach ($element["styles"] as $style) {
                            if (isset($style["prop"])) {
                                $css .= "\t" . $style["prop"] . ": " . $style["value"] . " !important;\n";
                            } elseif (isset($style["name"])) {
                                $css .= "\t" . $style["name"] . ": " . $style["value"] . " !important;\n";
                            }
                        }
                    }
                    $css .= "}\n";
                }
            }
        }
        
        $this->getResponse()->setHeader('Content-Type', 'text/css');
        $this->getResponse()->setBody($css);
    }
}