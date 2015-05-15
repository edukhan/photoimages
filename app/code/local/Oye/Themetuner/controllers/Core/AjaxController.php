<?php
/**
 * Frontend ajax controller. Rewrite by OYE.
 * Needed for loading tune specific translations.
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>>
 */
include_once("Mage/Core/controllers/AjaxController.php");
class Oye_Themetuner_Core_AjaxController extends Mage_Core_AjaxController
{
    public function tunetranslateAction ()
    {
        $translation = $this->getRequest()->getPost('translate');
        $area = $this->getRequest()->getPost('area');

        //filtering
        /** @var $filter Mage_Core_Model_Input_Filter_MaliciousCode */
        $filter = Mage::getModel('core/input_filter_maliciousCode');
        foreach ($translation as &$item) {
            $item['custom'] = $filter->filter($item['custom']);
        }

        $response = Mage::helper('core/translate')->themetunerApply($translation, $area);
        $this->getResponse()->setBody($response);
        $this->setFlag('', self::FLAG_NO_POST_DISPATCH, true);
    }
    
}
