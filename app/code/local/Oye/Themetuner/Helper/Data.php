<?php
class Oye_Themetuner_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Get presets matching spcific theme settings.
	 *
	 * @param string $package - package dir
	 * @param string $locale - locale dir
	 * @param string $template - template dir
	 * @param string $skin - skin dir
	 * @param string $layout - layout dir
	 * @param string $default - default dir
	 * @return array
	 */
    public function getPresets($package, $locale, $template, $skin, $layout, $default) {
	    $presets = Mage::getModel('themetuner/preset')->getCollection();
        
        $presets->addFieldToFilter('theme_package_dir', $package);
        $presets->addFieldToFilter('theme_locale_dir', $locale);
        $presets->addFieldToFilter('theme_template_dir', $template);
        $presets->addFieldToFilter('theme_skin_dir', $skin);
        $presets->addFieldToFilter('theme_layout_dir', $layout);
        $presets->addFieldToFilter('theme_default_dir', $default);

        $result = array();
        foreach($presets as $preset) {
            $result[] = array('value' => $preset->getId(), 'label' => $preset->getName());
        }
        return $result;
	}
	
	/**
	 * Get live site names where specified preset is attached to.
	 *
	 * @param integer $presetId - preset id
	 * @param object $preset - preset object, default value is null
	 * @return string - list of store names that have this preset atached to, separated by commas
	 */
	public function getPresetLives($presetId, $preset = null) {
	    
	    if (!$preset) {
	        $preset = Mage::getModel('themetuner/preset')->load($presetId);
	    }
	    foreach (array('package_dir', 'locale_dir', 'template_dir', 'skin_dir', 'layout_dir', 'default_dir') as $themeProp) {
            $$themeProp = $preset->getData('theme_'.$themeProp);
        }
        
        $liveStores = array();
        foreach (Mage::app()->getStores() as $store) {
            
            $store_preset_id = Mage::getStoreConfig('design/theme/themetuner_preset', $store->getId());
            
            $store_package_dir = Mage::getStoreConfig('design/package/name', $store->getId());
            $store_locale_dir = Mage::getStoreConfig('design/theme/locale', $store->getId());
            $store_template_dir = Mage::getStoreConfig('design/theme/template', $store->getId());
            $store_skin_dir = Mage::getStoreConfig('design/theme/skin', $store->getId());
            $store_layout_dir = Mage::getStoreConfig('design/theme/layout', $store->getId());
            $store_default_dir = Mage::getStoreConfig('design/theme/default', $store->getId());
            
            if (
                $presetId == $store_preset_id && $package_dir == $store_package_dir && $locale_dir == $store_locale_dir &&
                $template_dir == $store_template_dir && $skin_dir == $store_skin_dir && $layout_dir == $store_layout_dir &&
                $default_dir == $store_default_dir
            ) {
                $liveStores[] = $store->getName();
                
            }
        }

        return count($liveStores) ? implode(', ', $liveStores) : '-';
	}
	
    public function getButtonHtml($attributes = array()) {
        return $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'     => isset($attributes['title']) ? $attributes['title'] : '',
                'onclick'   => isset($attributes['onclick']) ? $attributes['onclick'] : '',
                'class' => isset($attributes['class']) ? $attributes['class'] : '',
                'id' => isset($attributes['id']) ? $attributes['id'] : '',
            ))->toHtml();
    }

    /** Admin domain for cross-domain iframes
     *
     * @return string
     */
    public function getAdminDomain()
    {
        $baseUrl = Mage::getStoreConfig(Mage_Adminhtml_Model_System_Config_Backend_Admin_Custom::XML_PATH_SECURE_BASE_URL, 0);
        $domain  = '';

        if (false === strpos($baseUrl, '{{'))
        {
            $domain = parse_url($baseUrl, PHP_URL_HOST);
        }
        else
        {
            $domain = !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';

            if (!$domain)
            {
                $domain = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            }
        }

        if (0 === strpos($domain, 'www.'))
        {
            $domain = substr($domain, 4);
        }

        return $domain;
    }
}