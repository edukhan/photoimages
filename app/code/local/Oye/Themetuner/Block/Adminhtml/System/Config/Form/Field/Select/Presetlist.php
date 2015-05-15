<?php
/**
 * Selectbox with "Tunes" list and additional Javascript functionality.
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>
 */
class Oye_Themetuner_Block_Adminhtml_System_Config_Form_Field_Select_Presetlist extends Varien_Data_Form_Element_Select
{

    public function getAfterElementHtml()
    {
        $javaScript = "
            <script type=\"text/javascript\">
                //<![CDATA[
                
                String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,''); };
                
                document.observe('dom:loaded', function() {
                    
                    var themeComponentElements = $$('#design_package_name, #design_theme_locale, #design_theme_template, #design_theme_skin, #design_theme_layout, #design_theme_default');
                    var tunelistElId = '{$this->getHtmlId()}';
                    var themelistElId = 'design_theme_theme_shortcut';
                    var timer = null;
                    
                    var handleThemeComponentChange = function(event) {
                        var triggeredByThemeSelector = (event.element().readAttribute('id') == themelistElId);
                        
                        if (!triggeredByThemeSelector) {
                            // theme selectbox logics
                            var themeExists = false;
                            var currentThemeComponentValues = [];
                            themeComponentElements.each(function(el) {
                                currentThemeComponentValues.push(el.getValue());
                            });
                            $$('#' + themelistElId + ' option').each(function(optEl, index) {
                                if (optEl.readAttribute('value') == currentThemeComponentValues.join('|')) {
                                    $(themelistElId).selectedIndex = index;
                                    themeExists = true;
                                    throw \$break;
                                }
                            });
                            if (!themeExists) {
                                $(themelistElId).selectedIndex = 0;
                            }
                        }
                        
                        
                        // tune selectbox logics
                        $(tunelistElId).selectedIndex = 0;
                        $$('#' + tunelistElId + ' option').each(function(optEl, indx) {
                            if (indx != 0) {
                                optEl.remove();
                            }
                        });
                        
                            
                        if (timer) {
                            clearTimeout(timer);
                            timer = null;
                        }
                        timer = setTimeout(function() {
                            var parameters = {};
                            themeComponentElements.each(function(el) {
                                var elId = el.readAttribute('id');
                                if (elId == 'design_package_name') {
                                    var type = 'package';
                                } else {
                                    var type = elId.replace('design_theme_', '');
                                }
                                parameters[type] = el.getValue().trim();
                            });
                            
                            var sourceUrl = '".Mage::getUrl('themetuner/adminhtml_themetuner/presetOptions')."';
                            new Ajax.Request(sourceUrl, {
                                method: 'post',
                                parameters: parameters,
                                onSuccess: function(transport) {
                                    var presets = transport.responseJSON;
                                    if (presets && presets.length) {
                                        presets.each(function(preset) {
                                            $(tunelistElId).insert(new Element('option', {value: preset.value}).update(preset.label));
                                        });
                                    }
                                }
                            });
                        }, (triggeredByThemeSelector ? 0 : 500));
                        
                    };
                    
                    themeComponentElements.each(function(el) {
                        el.observe('keyup', handleThemeComponentChange);
                    });
                    
                    $(themelistElId).observe('change', handleThemeComponentChange);
                });
                //]]>
            </script>";
        return $javaScript . parent::getAfterElementHtml();
    }
}
