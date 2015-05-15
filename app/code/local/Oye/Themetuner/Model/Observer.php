<?php
/**
 * Themetuner observer class.
 * Needed for changing themes and store content on the fly.
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>>
 */
class Oye_Themetuner_Model_Observer {

    private static $_presetRegistered = false;

    /**
     * Change store theme on runtime. This is needed for Themetuner admin.
     * Because we can edit different store content with different theme applied to it.
     *
     * @param Varien_Event_Observer $observer
     */
    public function changeTheme(Varien_Event_Observer $observer) {
        $tempTheme = urldecode(Mage::app()->getRequest()->getParam('themetuner_theme', ''));
        
        $themeDirs = array();
        if ($tempTheme) {
            
            list(
                $themeDirs['package'],
                $themeDirs['locale'],
                $themeDirs['template'],
                $themeDirs['skin'],
                $themeDirs['layout'],
                $themeDirs['default']
            ) = explode('|', $tempTheme);
        }
        
        // set theme
        if (count($themeDirs)) {
            $packageModel = Mage::getDesign();
            foreach ($themeDirs as $type => $dir) {
                if ($type == 'package') {
                    $packageModel->setPackageName($dir);
                } else {
                    $packageModel->setTheme($type, $dir);
                }
            }
        }
    }
    
    /**
     * Change store content if specified params are preset in request.
     * This is needed for Themetuner admin because can edit and test different
     * themes and tunes with different store content.
     *
     * @param Varien_Event_Observer $observer
     */
    public function changeStore(Varien_Event_Observer $observer) {
        $tempPresetId = Mage::app()->getRequest()->getParam('themetuner_preset_id', null);
        if ($tempPresetId) {
            $preset = Mage::getModel('themetuner/preset')->load($tempPresetId);
            if ($preset && $preset->getId() && $preset->getStoreId()) {
                Mage::app()->setCurrentStore($preset->getStoreId());
            }
        }
    }

    /**
     * Enable drag and drop controls by request params for admin users
     * 
     * @param Varien_Event_Observer $observer
     */
    public function addAdminLayoutEditor(Varien_Event_Observer $observer)
    {
        /* @var $controller Mage_Core_Controller_Front_Action */
        $controller = $observer->getEvent()->getControllerAction();

        $inAdmin            = $controller->getRequest()->getParam('in_admin');
        $editorId           = $controller->getRequest()->getParam('themetuner_editor_id');
        $themetunerPresetId = $controller->getRequest()->getParam('themetuner_preset_id');
        $themetunerPresetId = $inAdmin ? $themetunerPresetId : Mage::getStoreConfig('design/theme/themetuner_preset');

        if (!self::$_presetRegistered)
        {
            Mage::register('themetuner_in_admin', $inAdmin);
            Mage::register('themetuner_editor_id', $editorId);
            Mage::register('themetuner_preset_id', $themetunerPresetId);
            self::$_presetRegistered = true;
        }
    }

    /**
     * Add drag and drop wraps to blocks
     * 
     * @param Varien_Event_Observer $observer
     */
    public function wrapLayoutEditor(Varien_Event_Observer $observer)
    {
        /* @var $block Mage_Core_Block_Abstract */
        $block     = $observer->getEvent()->getBlock();
        $transport = $observer->getEvent()->getTransport();
        $html      = $transport->getHtml();

        if (!$block->getParentBlock() || !Mage::registry('themetuner_in_admin'))
        {
            return false;
        }

        if ($block instanceof Mage_Core_Block_Text_List)
        {
            $html = '<div class="themetuner-column-add"><button class="themetuner-column-add-button" id="for::'.$block->getParentBlock()->getNameInLayout().'::'.$block->getNameInLayout().'"><span>'.Mage::helper('themetuner')->__('Add new block...').'</span></button></div>'.
                '<div class="block-wrapper themetuner-column" id="'.$block->getParentBlock()->getNameInLayout().'::'.$block->getNameInLayout().'">'.$html.'</div>';
        }
        elseif ($block instanceof Mage_Page_Block_Html_Header || $block->getParentBlock() instanceof Mage_Page_Block_Html_Header || $block instanceof Mage_Page_Block_Html_Breadcrumbs)
        {
            // do not wrap
        }
        elseif ($block instanceof Mage_Core_Block_Template && !in_array($block->getParentBlock()->getNameInLayout(), array('head', 'root')))
        {
            $html = '<div class="block-wrapper themetuner-portlet" id="'.$block->getParentBlock()->getNameInLayout().'::'.$block->getNameInLayout().'" themetuner-alias="'.$block->getBlockAlias().'">'.
                '<div class="themetuner-portlet-header"><span class="name">'.$block->getNameInLayout().'</span>'.
                '<div class="themetuner-remove"><span><img alt="'.Mage::helper('themetuner')->__('Remove').'" src="'.$block->getSkinUrl('images/themetuner/btn_remove.gif').'" /></span></div></div>'.
                '<div class="block-wrapper themetuner-portlet-content">'.$html.'</div></div>';
        }

        $transport->setHtml($html);
    }

    public function addCustomLayout(Varien_Event_Observer $observer)
    {
        $this->_updateLayout(Mage::helper('themetuner/theme')->getCustomLayoutXml());
    }

    protected function _updateLayout($serializedString)
    {
        if ($serializedString)
        {
            try
            {
                /* @var $update Mage_Core_Model_Layout_Update */
                $update = Mage::app()->getLayout()->getUpdate();
                /* @var $layoutXml Mage_Core_Model_Layout_Element */
                $layoutArray = unserialize($serializedString);

                foreach ($update->getHandles() as $handle)
                {
                    if (isset($layoutArray[$handle]))
                    {
                        $layoutXml       = '';
                        $widgetLayoutXml = '';
                        foreach ($layoutArray[$handle] as $block => $blockLayout)
                        {
                            foreach ($blockLayout as $action => $actionLayout)
                            {
                                if ('widget' == $action)
                                {
                                    $widgetLayoutXml .= join('', $actionLayout);
                                }
                                else
                                {
                                    $layoutXml .= join('', $actionLayout);
                                }
                            }
                        }
                        //echo '<pre>'.htmlentities($layoutXml).'</pre>';
                        if ($widgetLayoutXml)  // Add widget updates prior the rest to make movals available
                        {
                            $update->addUpdate($widgetLayoutXml);
                        }

                        if ($layoutXml)
                        {
                            $update->addUpdate($layoutXml);
                        }
                    }
                }
            }
            catch (Exception $e)
            {
                Mage::logException($e);
            }
        }
    }

    public function addCustomColumns(Varien_Event_Observer $observer)
    {
        $update = Mage::app()->getLayout()->getUpdate();

        foreach (Mage::helper('themetuner/theme')->getCustomLayoutColumns() as $handle) 
        {
            $update->addHandle($handle);
        }
    }
}
