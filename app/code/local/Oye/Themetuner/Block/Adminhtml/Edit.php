<?php
class Oye_Themetuner_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('themetuner/edit.phtml');
    }
    
    public function getStore() {
        return Mage::getModel('core/store')->load($this->getStoreId());
    }
    
    /**
     * Get store original logo src. This is needed
     * to restore default settings.
     *
     * @return string - logo src
     */
    public function getOriginalLogo() {
        $storeId = $this->getStoreId();
        $logoSrc = Mage::getStoreConfig('design/header/logo_src', $storeId);
        
        list(
            $packageDir,
            $localeDir,
            $templateDir,
            $skinDir,
            $layoutDir,
            $defaultDir
        ) = explode('|', $this->getTheme());
        
        return $this->getSkinUrl($logoSrc, array(
            '_area' => 'frontend',
            '_package' => $packageDir,
            '_theme' => $templateDir, // XXX: use "template" part of theme, possible breakage
            '_default' => $defaultDir,
        ));
    }

    public function getLayoutSaveUrl()
    {
        return $this->getUrl('*/*/ajaxCacheLayout');
    }
    
    public function getAddBlockUrl()
    {
        return $this->getUrl('*/*/ajaxNewBlock');
    }
}
