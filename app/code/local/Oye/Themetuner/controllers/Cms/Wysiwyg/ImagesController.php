<?php
/**
 * Controller override for media uploader modifications.
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>
 */
include_once("Mage/Adminhtml/controllers/Cms/Wysiwyg/ImagesController.php");
class Oye_Themetuner_Cms_Wysiwyg_ImagesController extends Mage_Adminhtml_Cms_Wysiwyg_ImagesController
{
    public function onInsertAction()
    {
        $useFileUrl = (int)$this->getRequest()->getParam('use_file_url', 0) == 1 ? true : false;

        $helper         = Mage::helper('cms/wysiwyg_images');
        $storeId        = $this->getRequest()->getParam('store');
        $filename       = $this->getRequest()->getParam('filename');
        $filename       = $helper->idDecode($filename);
        $asIs           = $this->getRequest()->getParam('as_is');

        Mage::helper('catalog')->setStoreId($storeId);
        $helper->setStoreId($storeId);

        if ($useFileUrl == false) {
            $image = $helper->getImageHtmlDeclaration($filename, $asIs);
        } else {
            $image = $helper->getImageMediaUrl($filename);
        }
        $this->getResponse()->setBody($image);
    }
}