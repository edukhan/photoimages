<?php

class Oye_Themetuner_Block_Adminhtml_Edit_Block_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Form with widget to select
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => $this->helper('themetuner')->__('Block')
        ));

        $selectValues = array(
            array('label' => $this->helper('adminhtml')->__('-- Please Select --'), 'value' => ''),
            array('label' => $this->helper('themetuner')->__('Widgets'), 'value' => $this->_getWidgetSelectOptions()),
            );

        $removedBlocks = $this->_getRemovedBlocksOptions();

        if ($removedBlocks)
        {
            $selectValues[] = array('label' => $this->helper('themetuner')->__('Restore Blocks'), 'value' => $removedBlocks);
        }

        $select = $fieldset->addField('select_widget_type', 'select', array(
            'label'                 => $this->helper('themetuner')->__('Block Type'),
            'title'                 => $this->helper('themetuner')->__('Block Type'),
            'name'                  => 'widget_type',
            'required'              => true,
            'values'                => $selectValues,
            'after_element_html'    => $this->_getWidgetSelectAfterHtml($removedBlocks),
        ));

        $select = $fieldset->addField('themetuner_column', 'hidden', array(
            'name'  => 'themetuner_column',
            'value' => Mage::registry('themetuner_column'),
            ));

        $form->setUseContainer(true);
        $form->setId('widget_options_form');
        $form->setMethod('post');
        $form->setAction($this->getUrl('*/*/buildWidget'));
        $this->setForm($form);
    }

    /**
     * Prepare options for widgets HTML select
     *
     * @return array
     */
    protected function _getWidgetSelectOptions()
    {
        $options = array();
        foreach ($this->_getAvailableWidgets(false) as $data) {
            $options[] = array(
                'label' => $data['name'], 
                'value' => $data['type'], 
                );
        }
        return $options;
    }

    protected function _getRemovedBlocksOptions()
    {
        $options = array();

        $serializedString = Mage::helper('themetuner/theme')->getCustomLayoutXml();

        if ($serializedString)
        {
            try 
            {
                /* @var $update Mage_Core_Model_Layout_Update */
                $update = Mage::app()->getLayout()->getUpdate();
                /* @var $layoutXml Mage_Core_Model_Layout_Element */
                $layoutArray = unserialize($serializedString);
                $removedBlocks = array();

                foreach ($update->getHandles() as $handle)
                {
                    if (isset($layoutArray[$handle]))
                    {
                        foreach ($layoutArray[$handle] as $block => $blockLayout)
                        {
                            if (!isset($blockLayout['insert']) && !empty($blockLayout['unsetChild']))
                            {
                                $removedBlocks[] = $block;
                            }
                        }
                    }
                }
            }
            catch (Exception $e)
            {
                Mage::logException($e);
            }
        }

        $removedBlocks = array_unique($removedBlocks);

        foreach ($removedBlocks as $removedBlock)
        {
            $options[] = array(
                'label' => $removedBlock,
                'value' => 'restore:'.$removedBlock,
            );
        }

        return $options;
    }

    /**
     * Prepare widgets select after element HTML
     *
     * @return string
     */
    protected function _getWidgetSelectAfterHtml(array $removedBlocks = array())
    {
        $html = '<p class="nm"><small></small></p>';
        $i = 1;
        foreach ($this->_getAvailableWidgets(true) as $data) {
            $html .= sprintf('<div id="widget-description-%s" class="no-display">%s</div>', $i, $data['description']);
            $i++;
        }

        $i++;
        foreach ($removedBlocks as $removedBlock) {
            $html .= sprintf('<div id="widget-description-%s" class="no-display"></div>', $i);
            $i++;
        }

        return $html;
    }

    /**
     * Return array of available widgets based on configuration
     *
     * @return array
     */
    protected function _getAvailableWidgets($withEmptyElement = false)
    {
        if (!$this->hasData('available_widgets')) {
            $result = array();
            $allWidgets = Mage::getModel('widget/widget')->getWidgetsArray();
            $skipped = $this->_getSkippedWidgets();
            foreach ($allWidgets as $widget) {
                if (is_array($skipped) && in_array($widget['type'], $skipped)) {
                    continue;
                }
                $result[] = $widget;
            }
            if ($withEmptyElement) {
                array_unshift($result, array(
                    'type'        => '',
                    'name'        => $this->helper('adminhtml')->__('-- Please Select --'),
                    'description' => '',
                ));
            }
            $this->setData('available_widgets', $result);
        }

        return $this->_getData('available_widgets');
    }

    /**
     * Return array of widgets disabled for selection
     *
     * @return array
     */
    protected function _getSkippedWidgets()
    {
        return Mage::registry('skip_widgets');
    }
}
