<?php
/**
 * Translate model. Rewrite by OYE.
 * Needed for loading tune specific translations.
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>
 */
class Oye_Themetuner_Model_Core_Translate extends Mage_Core_Model_Translate
{
    const CONFIG_KEY_DESIGN_TUNE   = 'tune';
    
    /**
     * Initialization translation data
     *
     * @param   string $area
     * @return  Mage_Core_Model_Translate
     */
    public function init($area, $forceReload = false)
    {
        $this->setConfig(array(self::CONFIG_KEY_AREA=>$area));

        $this->_translateInline = Mage::getSingleton('core/translate_inline')
            ->isAllowed($area=='adminhtml' ? 'admin' : null);

        if (!$forceReload) {
            if ($this->_canUseCache()) {
                $this->_data = $this->_loadCache();
                if ($this->_data !== false) {
                    return $this;
                }
            }
            Mage::app()->removeCache($this->getCacheId());
        }

        $this->_data = array();

        foreach ($this->getModulesConfig() as $moduleName=>$info) {
            $info = $info->asArray();
            $this->_loadModuleTranslation($moduleName, $info['files'], $forceReload);
        }

        $this->_loadThemeTranslation($forceReload);
        $this->_loadDbTranslation($forceReload);
        $this->_loadTuneTranslation($forceReload = true);

        if (!$forceReload && $this->_canUseCache()) {
            $this->_saveCache();
        }

        return $this;
    }
    
    /**
     * Initialize configuration
     *
     * @param   array $config
     * @return  Mage_Core_Model_Translate
     */
    public function setConfig($config)
    {
        $this->_config = $config;
        if (!isset($this->_config[self::CONFIG_KEY_LOCALE])) {
            $this->_config[self::CONFIG_KEY_LOCALE] = $this->getLocale();
        }
        if (!isset($this->_config[self::CONFIG_KEY_STORE])) {
            $this->_config[self::CONFIG_KEY_STORE] = Mage::app()->getStore()->getId();
        }
        if (!isset($this->_config[self::CONFIG_KEY_DESIGN_PACKAGE])) {
            $this->_config[self::CONFIG_KEY_DESIGN_PACKAGE] = Mage::getDesign()->getPackageName();
        }
        if (!isset($this->_config[self::CONFIG_KEY_DESIGN_THEME])) {
            $this->_config[self::CONFIG_KEY_DESIGN_THEME] = Mage::getDesign()->getTheme('locale');
        }
        if (!isset($this->_config[self::CONFIG_KEY_DESIGN_TUNE])) {
            $this->_config[self::CONFIG_KEY_DESIGN_TUNE] = Mage::getStoreConfig('design/theme/themetuner_preset', Mage::app()->getStore()->getId());
        }
        return $this;
    }
    
    protected function _loadTuneTranslation($forceReload = false)
    {
        $presetId = (int) Mage::app()->getRequest()->getParam('themetuner_preset_id', 0);
        if ($presetId == 0) {
            $presetId = Mage::getStoreConfig('design/theme/themetuner_preset');
        }
        
        $arr = Mage::getResourceModel('themetuner/translate')->getTranslationArray($presetId, null, $this->getLocale());
        $this->_addData($arr, $this->getConfig(self::CONFIG_KEY_STORE), $forceReload);
        return $this;
    }
    
    /**
     * Retrieve cache identifier
     *
     * @return string
     */
    public function getCacheId()
    {
        if (is_null($this->_cacheId)) {
            $this->_cacheId = 'translate';
            if (isset($this->_config[self::CONFIG_KEY_LOCALE])) {
                $this->_cacheId.= '_'.$this->_config[self::CONFIG_KEY_LOCALE];
            }
            if (isset($this->_config[self::CONFIG_KEY_AREA])) {
                $this->_cacheId.= '_'.$this->_config[self::CONFIG_KEY_AREA];
            }
            if (isset($this->_config[self::CONFIG_KEY_STORE])) {
                $this->_cacheId.= '_'.$this->_config[self::CONFIG_KEY_STORE];
            }
            if (isset($this->_config[self::CONFIG_KEY_DESIGN_PACKAGE])) {
                $this->_cacheId.= '_'.$this->_config[self::CONFIG_KEY_DESIGN_PACKAGE];
            }
            if (isset($this->_config[self::CONFIG_KEY_DESIGN_THEME])) {
                $this->_cacheId.= '_'.$this->_config[self::CONFIG_KEY_DESIGN_THEME];
            }
            if (isset($this->_config[self::CONFIG_KEY_DESIGN_TUNE])) {
                $this->_cacheId.= '_'.$this->_config[self::CONFIG_KEY_DESIGN_TUNE];
            }
        }
        return $this->_cacheId;
    }
}