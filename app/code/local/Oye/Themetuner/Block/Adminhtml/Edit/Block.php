<?php

class Oye_Themetuner_Block_Adminhtml_Edit_Block extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'themetuner';
        $this->_controller = 'adminhtml_edit';
        $this->_mode       = 'block';
        $this->_headerText = $this->helper('themetuner')->__('Block Insertion');

        $this->removeButton('reset');
        $this->removeButton('back');
        $this->_updateButton('save', 'label', $this->helper('themetuner')->__('Block Insertion'));
        $this->_updateButton('save', 'class', 'add-widget');
        $this->_updateButton('save', 'id', 'insert_button');
        $this->_updateButton('save', 'onclick', 'wWidget.insertWidget()');

        $this->_formScripts[] = 'wWidget = new WysiwygWidget.Widget('
            . '"widget_options_form", "select_widget_type", "widget_options", "'
            . $this->getUrl('adminhtml/widget/loadOptions') .'", "' . $this->getRequest()->getParam('widget_target_id') . '");';
    }
}
