<?php
/**
 * Html page block, override to change logo src getter.
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>>
 */
class Oye_Themetuner_Block_Page_Html_Header extends Mage_Page_Block_Html_Header
{
    public function getLogoSrc() {
        $presetId = Mage::getStoreConfig('design/theme/themetuner_preset');
        if ($presetId) {
            $preset = Mage::getModel('themetuner/preset')->load($presetId);
            if ($preset && $preset->getId() && $preset->getLogo()) {
                return $preset->getLogo();
            }
        }
        return parent::getLogoSrc();
    }

    public function getIsHomePage()
    {
        if ($this->getRequest()->getParam('in_admin')) {
            return 'cms_index_index' == Mage::app()->getFrontController()->getAction()->getFullActionName();
        }

        return $this->getUrl('') == $this->getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true));
    }
}
