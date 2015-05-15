<?php
/**
 * Presets aka "Tunes" grid.
 *
 * @category   Magstudio
 * @package    Magstudio_VisualDesigner
 * @licensed   Magdeveloper.com <info@magdeveloper.com>
 */
class Oye_Themetuner_Block_Adminhtml_Preset_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    private $_lastTheme = null;
    
    private $_themeCounter = 0;
    private $_themeTotal = 0;
    
    public function __construct()
	{
		parent::__construct();
		$this->setTemplate('themetuner/widget/grid.phtml');
		
		$this->setId('presetGrid');
		$this->setUseAjax(true);
		$this->setDefaultSort('theme_template_dir');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        $this->unsetChild('export_button');
        $this->unsetChild('reset_filter_button');
        $this->unsetChild('search_button');
    }
	
	protected function _beforeRowRender($item) {
	    if ($this->_lastTheme != $item->getData('theme')) {
            $this->_themeCounter = 1;
	    } else {
	        $this->_themeCounter++;
	    }
	}
	
	protected function _afterRowRender($item) {
	    if ($this->_lastTheme != $item->getData('theme')) {
            $this->_lastTheme = $item->getData('theme');
	    }
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('themetuner/preset_collection');
		
        $themes = Mage::helper('themetuner/theme')->getThemeList();
        $missingThemes = array();
        
        // filter out themes that are already in the grid
        foreach($themes as $theme) {
            $themeExists = false;
            foreach ($collection->getItems() as $preset) {
                if ($preset->getTheme() == $theme['value']) {
                    $themeExists = true;
                    break;
                }
            }
            if (!$themeExists) {
                $missingThemes[] = $theme;
            }
        }
        
        foreach ($missingThemes as $mTheme) {
            $obj = new Oye_Themetuner_Model_Preset();
            // randomly generated id, just to prevent duplicate id error (note two hyphens, these are used in code checks)
            $obj->setData('preset_id', md5(microtime()).'--'.md5(rand(0, 999999)));
            $obj->setData('theme', $mTheme['value']);
            $obj->setData('name', '-');
            $obj->setData('store_name', '-');
            $obj->setData('updated_at', '-');
            
            $collection->addItem($obj);
        }
        
		$this->setCollection($collection);
		
		return parent::_prepareCollection();
	}
	
	public function shouldRenderCell($item, $column) {
	    if ($column->getIndex() == 'theme') {
	        if ($this->_lastTheme != $item->getData('theme')) {
	            return true;
	        } else {
	            return false;
	        }
	        
	    } else {
	        return parent::shouldRenderCell($item, $column);
	    }
	}
	
	public function getRowspan($item, $column) {
	    if ($column->getIndex() == 'theme') {
	        
	        if (strpos($item->getData('preset_id'), '--') !== false) {
	            return 2;
	        }
	        
	        $collection = Mage::getModel('themetuner/preset')
	            ->getCollection()
	            ->addThemeFilter($item->getData('theme'));
	            
            $collectionCount = count($collection);
            $this->_themeTotal = $collectionCount;
	           
            return $collectionCount + 1;
	        
	    } else {
	        return parent::getRowspan($item, $column);
	    }
	}
	
	public function shouldRenderButtonRow($item) {
	    return ($this->_themeCounter == $this->_themeTotal || strpos($item->getData('preset_id'), '--') !== false);
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('theme', array(
            'header'    => Mage::helper('themetuner')->__('Theme'),
            'align'     =>'left',
            'width'     => '130px',
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => 'themetuner/adminhtml_preset_renderer_theme',
            'index'     => 'theme',
		));
		
		$this->addColumn('name', array(
            'header'    => Mage::helper('themetuner')->__('Tune'),
            'align'     =>'left',
            'width'     => '130px',
            'sortable'  => false,
            'filter'    => false,
            'index'     => 'name',
		));
		
		$this->addColumn('attached_content', array(
            'header'    => Mage::helper('themetuner')->__('Attached Content'),
            'align'     =>'left',
            'width'     => '180px',
            'sortable'  => false,
            'filter'    => false,
            'index'     => 'store_name',
		));
		
		$this->addColumn('live', array(
            'header'    => Mage::helper('themetuner')->__('Live'),
            'align'     =>'left',
            'width'     => '450px',
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => 'themetuner/adminhtml_preset_renderer_live',
            'index'     => 'live',
		));
		
		$this->addColumn('updated_at', array(
            'header'    => Mage::helper('themetuner')->__('Date Modified'),
            'align'     =>'center',
            'width'     => '50px',
            'type'      => 'datetime',
            'renderer'  => 'themetuner/adminhtml_preset_renderer_date',
            'sortable'  => false,
            'filter'    => false,
            'index'     => 'updated_at',
		));
		
		$this->addColumn('open_action',
            array(
            'header'    =>  '',
            'align'     =>'center',
            'width'     => '50px',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('themetuner')->__('Open'),
                    'url'       => array('base' => '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'renderer'  => 'themetuner/adminhtml_preset_renderer_action',
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'open',
            'is_system' => true,
        ));
        
        $this->addColumn('settings_action',
            array(
            'header'    =>  '',
            'align'     =>'center',
            'width'     => '50px',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('themetuner')->__('Settings'),
                    'url'       => array('base'=> '*/*/settings'),
                    'field'     => 'id',
                    'class'     => 'settings-popup',
                )
            ),
            'renderer'  => 'themetuner/adminhtml_preset_renderer_action',
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'settings',
            'is_system' => true,
        ));
        
        $this->addColumn('delete_action',
            array(
            'header'    =>  '',
            'align'     =>'center',
            'width'     => '50px',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('themetuner')->__('Delete'),
                    'url'       => array('base'=> '*/*/delete'),
                    'field'     => 'id',
                    'class'     => 'delete-popup',
                )
            ),
            'renderer'  => 'themetuner/adminhtml_preset_renderer_action',
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'delete',
            'is_system' => true,
        ));

		return parent::_prepareColumns();
	}
    
    public function getAddTuneButtonHtml($item) {
        $addUrl = $this->getUrl('*/*/edit', array('theme' => $item->getData('theme')));
        
        return $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'     => Mage::helper('themetuner')->__('Add Tune'),
                'onclick'   => "setLocation('".$addUrl."')",
                'class' => 'add'
            ))->toHtml();
    }

	protected function _afterLoadCollection()
	{
		$this->getCollection()->walk('afterLoad');
		parent::_afterLoadCollection();
	}
	
	public function getGridUrl()
	{
		return $this->getUrl('*/*/grid', array('_current'=> true));
	}
	
	public function getRowUrl($row)
	{
		if (strpos($row->getData('preset_id'), '--') !== false) {
		    return '';
		}
	    return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}