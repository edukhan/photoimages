<?php
/**
 * Inline Translations PHP part. Rewrite by OYE.
 * Needed for loading tune specific translations.
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>
 */
class Oye_Themetuner_Model_Core_Translate_Inline extends Mage_Core_Model_Translate_Inline
{
    /**
     * Flag about inserted styles and scripts for inline translates - Themetuner specific
     *
     * @var bool
     */
    protected $_isThemetunerScriptInserted    = false;
    
    /**
     * Is enabled and allowed Inline Translates
     *
     * @param mixed $store
     * @return bool
     */
    public function isAllowed($store = null)
    {
        if (is_null($store)) {
            $store = Mage::app()->getStore();
        }
        if (!$store instanceof Mage_Core_Model_Store) {
            $store = Mage::app()->getStore($store);
        }

        if (is_null($this->_isAllowed)) {
            if (Mage::getDesign()->getArea() == 'adminhtml') {
                $active = Mage::getStoreConfigFlag('dev/translate_inline/active_admin', $store);
            } else {
                $active = Mage::getStoreConfigFlag('dev/translate_inline/active', $store);
            }

            // Themetuner inline translations override,
            // if $presetId is present then display inline translations when store frontend is shown in Themetuner admin
            $presetId = (int) Mage::app()->getRequest()->getParam('themetuner_preset_id', 0);
            if ($presetId) {
                $active = true;
            }
            
            $this->_isAllowed = $active && Mage::helper('core')->isDevAllowed($store);
        }

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */

        return $translate->getTranslateInline() && $this->_isAllowed;
    }
    
    /**
     * Parse and save edited translate - Themetuner specific scripts
     *
     * @param array $translate
     * @return Mage_Core_Model_Translate_Inline
     */
    public function processThemetunerAjaxPost($translate)
    {
        /* @var $resource Oye_Themetuner_Model_Mysql4_Translate_String */
        $resource = Mage::getResourceModel('themetuner/translate_string');
        foreach ($translate as $t) {
            if (empty($t['custom'])) {
                $resource->deleteTranslate($t['original'], null, null, $t['preset_id']);
            } else {
                $storeId = 0;
                $resource->saveTranslate($t['original'], $t['custom'], null, $storeId, $t['preset_id']);
            }
        }

        return $this;
    }

    /**
     * Replace translate templates to HTML fragments. $presetId parameter and related logics added by OYE.
     *
     * @param array|string $body
     * @return Mage_Core_Model_Translate_Inline
     */
    public function processResponseBody(&$body)
    {
        $presetId = (int) Mage::app()->getRequest()->getParam('themetuner_preset_id', 0);
        
        if (!$this->isAllowed()) {
            if (Mage::getDesign()->getArea() == 'adminhtml') {
                $this->stripInlineTranslations($body);
            }
            return $this;
        }

        if (is_array($body)) {
            foreach ($body as &$part) {
                $this->processResponseBody($part);
            }
        } else if (is_string($body)) {
            $this->_content = $body;

            $this->_specialTags();
            $this->_tagAttributes();
            $this->_otherText();
            if ($presetId) {
                $this->_insertThemetunerInlineScriptsHtml();
                $this->_content = str_replace('translate="[{', 'translate="[{preset_id:'.$presetId.',', $this->_content);
            } else {
                $this->_insertInlineScriptsHtml();
            }
            
            $body = $this->_content;
        }

        return $this;
    }

    /**
     * Add translate js to body - Themetuner specific scripts
     */
    protected function _insertThemetunerInlineScriptsHtml()
    {
        if ($this->_isThemetunerScriptInserted || stripos($this->_content, '</body>')===false) {
            return;
        }
        
        $baseJsUrl = Mage::getBaseUrl('js');
        $url_prefix = Mage::app()->getStore()->isAdmin() ? 'adminhtml' : 'core';
        $ajaxUrl = Mage::getUrl($url_prefix . '/ajax/tunetranslate',
            array('_secure'=>Mage::app()->getStore()->isCurrentlySecure()));
        $trigImg = Mage::getDesign()->getSkinUrl('images/fam_book_open.png');

        ob_start();
        $magentoSkinUrl = Mage::getDesign()->getSkinUrl('lib/prototype/windows/themes/magento.css');
?>

<script type="text/javascript" src="<?php echo $baseJsUrl ?>prototype/window.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $baseJsUrl ?>prototype/windows/themes/default.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $magentoSkinUrl; ?>"/>

<script type="text/javascript" src="<?php echo $baseJsUrl ?>themetuner/translate_inline.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $baseJsUrl ?>themetuner/translate_inline.css"/>

<div id="translate-inline-trig"><img src="<?php echo $trigImg ?>" alt="[TR]"/></div>
<script type="text/javascript">
    new TranslateInline('translate-inline-trig', '<?php echo $ajaxUrl ?>', '<?php
        echo Mage::getDesign()->getArea() ?>');
</script>
        
        <?php $html = ob_get_clean();

        $this->_content = str_ireplace('</body>', $html . '</body>', $this->_content);
        
        $this->_isThemetunerScriptInserted = true;
    }
}
