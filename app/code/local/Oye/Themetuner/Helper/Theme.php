<?php
class Oye_Themetuner_Helper_Theme extends Mage_Core_Helper_Abstract
{
    private $_customLayoutXml;
    
    private function _getFinalThemeValue($value) {
        list($package, $theme) = explode('/', $value);
        return $package.'|'.$theme.'|'.$theme.'|'.$theme.'|'.$theme.'|default';
    }
    
    private function _isBasePackageTheme($fullThemeName) {
        return (strpos($fullThemeName, 'base/') === 0);
    }
    
    /**
     * Get list of available themes for this Magento installation.
     *
     * @param array $mergeThemes - list of items to merge result with, default value is null
     * @return array - list of themes
     */
    public function getThemeList($mergeThemes = null) {
        
        if ($mergeThemes != null) {
            $themes = $mergeThemes;
        } else {
            $themes = array();
        }
        
        $packageThemes = Mage::getModel('core/design_source_design')->setIsFullLabel(false)->getAllOptions(false);
        
        foreach ($packageThemes as $item) {
            if (is_array($item['value'])) {
                foreach ($item['value'] as $valueItem) {
                    if (!$this->_isBasePackageTheme($valueItem['value'])) {
                        $themes[] = array('value' => $this->_getFinalThemeValue($valueItem['value']), 'label' => $valueItem['label']);
                    }
                }
            } else {
                if (!$this->_isBasePackageTheme($valueItem['value'])) {
                    $themes[] = array('value' => $this->_getFinalThemeValue($item['value']), 'label' => $item['label']);
                }
            }
        }
        
        return $themes;
    }

    /** Gets template name (columns) for current page 
     * 
     * @return string 
     */
    public function getCurrentPageLayout()
    {
        $rootBlock = Mage::app()->getLayout()->getBlock('root');

        if ($rootBlock instanceof Mage_Core_Block_Template)
        {
            return str_replace(array('page/', '.phtml'), '', $rootBlock->getTemplate());
        }
    }
    
    public function getHandles()
    {
        $handles = Mage::app()->getLayout()->getUpdate()->getHandles();

        foreach ($handles as $key => $handle)
        {
            if (0 === strpos($handle, 'MAP_') || 0 === strpos($handle, 'SHORTCUT_') || 0 === strpos($handle, 'page_') || 0 === strpos($handle, 'THEME_'))
            {
                unset($handles[$key]);
            }
        }

        return array_values($handles);
    }

    public function getCustomLayoutXml()
    {
        if (null === $this->_customLayoutXml)
        {
            $presetId = Mage::registry('themetuner_preset_id');
            $editorId = Mage::registry('themetuner_editor_id');
            $this->_customLayoutXml = '';

            if ($presetId || $editorId)
            {
                $preset    = Mage::getModel('themetuner/preset');
                $presetXml = '';
                $editorXml = '';

                if ($presetId)
                {
                    $presetXml = $preset->load($presetId)->getLayoutUpdates();
                }

                if ($editorId)
                {
                    $editorXml = Mage::app()->getCache()->load('themetuner_mage_'.$editorId, false, true);
        
                    if ($editorXml)
                    {
                        $editorXml = unserialize($editorXml);
                    }
                }

                $this->_customLayoutXml = $preset->mergeLayoutUpdates($presetXml, $editorXml);
            }
        }

        return $this->_customLayoutXml;
    }

    public function getCustomLayoutColumns()
    {
        $layoutXml = $this->getCustomLayoutXml();
        $columns   = array();
        if ($layoutXml)
        {
            $update      = Mage::app()->getLayout()->getUpdate();
            $layoutArray = unserialize($layoutXml);

            foreach ($update->getHandles() as $handle)
            {
                if (isset($layoutArray[$handle]))
                {
                    foreach ($layoutArray[$handle] as $block => $blockLayout)
                    {
                        if ('@columns' == $block)
                        {
                            foreach ($blockLayout as $action => $actionLayout)
                            {
                                $columns[$handle] = key($actionLayout);
                            }
                        }
                    }
                }
            }
        }

        return $columns;
    }
}