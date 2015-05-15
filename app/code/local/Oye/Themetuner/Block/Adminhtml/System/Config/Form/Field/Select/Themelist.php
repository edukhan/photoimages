<?php
/**
 * Selectbox with "Themes" list and additional Javascript functionality.
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>
 */
class Oye_Themetuner_Block_Adminhtml_System_Config_Form_Field_Select_Themelist extends Varien_Data_Form_Element_Select
{

    public function getAfterElementHtml()
    {
        $javaScript = "
            <script type=\"text/javascript\">
                //<![CDATA[
                
                document.observe('dom:loaded', function() {
                
                    var themelistElId = '{$this->getHtmlId()}';
                    
                    Event.observe($(themelistElId), 'change', function() {
                        var themeChunks = $(themelistElId).getValue().split('|');
                        if (themeChunks && themeChunks.length > 1) {
                            var packageInheritEl = $('design_package_name_inherit');
                            if (packageInheritEl) {
                                packageInheritEl.checked = false;
                                packageInheritEl.onclick();
                            }
                            $('design_package_name').setValue(themeChunks[0]);
                            
                            var localeInheritEl = $('design_theme_locale_inherit');
                            if (localeInheritEl) {
                                localeInheritEl.checked = false;
                                localeInheritEl.onclick();
                            }
                            $('design_theme_locale').setValue(themeChunks[1]);
                            
                            var templateInheritEl = $('design_theme_template_inherit');
                            if (templateInheritEl) {
                                templateInheritEl.checked = false;
                                templateInheritEl.onclick();
                            }
                            $('design_theme_template').setValue(themeChunks[2]);
                            
                            var skinInheritEl = $('design_theme_skin_inherit');
                            if (skinInheritEl) {
                                skinInheritEl.checked = false;
                                skinInheritEl.onclick();
                            }
                            $('design_theme_skin').setValue(themeChunks[3]);
                            
                            var layoutInheritEl = $('design_theme_layout_inherit');
                            if (layoutInheritEl) {
                                layoutInheritEl.checked = false;
                                layoutInheritEl.onclick();
                            }
                            $('design_theme_layout').setValue(themeChunks[4]);
                            
                            var defaultInheritEl = $('design_theme_default_inherit');
                            if (defaultInheritEl) {
                                defaultInheritEl.checked = false;
                                defaultInheritEl.onclick();
                            }
                            $('design_theme_default').setValue(themeChunks[5]);
                        }
                    });
                    
                });
                //]]>
            </script>";
        return $javaScript . parent::getAfterElementHtml();
    }
}
