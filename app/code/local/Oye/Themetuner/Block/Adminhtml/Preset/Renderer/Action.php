<?php
/**
 * Grid column widget for rendering action grid cells (for preset grid).
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>
 */
class Oye_Themetuner_Block_Adminhtml_Preset_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    protected function _toLinkHtml($action, Varien_Object $row)
    {
        // put preset name to link title attribute, so that javascript confirm popup can display it
        if ($this->getColumn()->getIndex() == 'delete') {
            $action['title'] = $row->getName();
        }
        if (strpos($row->getData('preset_id'), '--') !== false) {
            return '&nbsp;';
        } else {
            return parent::_toLinkHtml($action, $row);
        }
    }
}
