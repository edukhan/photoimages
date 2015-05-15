<?php
 
class Oye_Themetuner_Model_Preset extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('themetuner/preset');
    }
    
    public function getTheme() {
        $themeChunks = array();
        foreach (array('package', 'locale', 'template', 'skin', 'layout', 'default') as $dir) {
            $themeChunks[] = $this->getData('theme_'.$dir.'_dir');
        }
        return implode('|', $themeChunks);
    }

    /**
     * 
     * @param string|null $oldLayout
     * @param array|null $newLayout
     */
    public static function mergeLayoutUpdates($oldLayout, $newLayout)
    {
        if (!$oldLayout)
        {
            if (0 == count($newLayout))
            {
                return false;
            }
    
            return serialize($newLayout);
        }
    
        if (!$newLayout)
        {
            return $oldLayout;
        }
    
        $oldLayout = unserialize($oldLayout);
         
        foreach ($newLayout as $handle => & $handleLayout)
        {
            foreach ($handleLayout as $block => $blockLayout)
            {
                if (!empty($blockLayout['columns']))
                {
                    $oldLayout[$handle][$block]['columns'] = $blockLayout['columns'];
                }
                elseif (isset($blockLayout['columns'])) // Flush columns to default value
                {
                    unset($oldLayout[$handle][$block]['columns']);
                }

                if (isset($blockLayout['insert']))
                {
                    $oldLayout[$handle][$block]['insert'] = $blockLayout['insert'];
                }

                if (isset($blockLayout['unsetChild']))
                {
                    if (isset($oldLayout[$handle][$block]['widget']) && !isset($blockLayout['insert']) && !isset($oldLayout[$handle][$block]['insert']))
                    {
                        unset($oldLayout[$handle][$block]['widget']);
                    }
                    elseif (!isset($oldLayout[$handle][$block]['unsetChild']))
                    {
                        $oldLayout[$handle][$block]['unsetChild'] = $blockLayout['unsetChild'];
                    }
                    else
                    {
                        $oldLayout[$handle][$block]['unsetChild'] = array_unique(array_merge($blockLayout['unsetChild'], $oldLayout[$handle][$block]['unsetChild']));
                    }
                }

                if (isset($blockLayout['widget']))
                {
                    $oldLayout[$handle][$block]['widget'] = $blockLayout['widget'];
                }
            }
        }
    
        $oldLayout = serialize($oldLayout);
    
        return $oldLayout;
    }
}