<?php
/**
 * Banner list admin grid
 * 
 * @author Activated
 */
class Activated_Slider_Block_Adminhtml_Banner_Grid extends 
Mage_Adminhtml_Block_Widget_Grid
{
	/**
	 * Init grid default properties
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setId('bannerGrid');
		$this->setDefaultSort('position');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}
	
	/**
	 * Prepare collection for grid
	 * 
	 * @return Activated_Slider_Block_Adminhtml_Banner_Grid
	 */
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('slider/banner')->getResourceCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	/**
	 * Prepare grid columns
	 * 
	 * @return Mage_adminhtml_Block_Catalog_Search_Grid
	 */
	protected function _prepareColumns()
	{
		$this->addColumn('banner_id', array(
			'header'	=> Mage::helper('slider')->__('ID'),
			'width'		=> '50px',
			'index'		=> 'banner_id'
		));
		
		$this->addColumn('title', array(
			'header'	=> Mage::helper('slider')->__('Banner Title'),
			'index'		=> 'title'
		));
		
		$this->addColumn('action', array(
			'header'	=> Mage::helper('slider')->__('Action'),
			'width'		=> '100px',
			'type'		=> 'action',
			'getter'	=> 'getId',
			'action'	=> array(array(
				'caption'	=> Mage::helper('slider')->__('Edit'),
				'url'		=> array('base' => '*/*/edit'),
				'field'		=> 'id'
			)),
			'filter'	=> false,
			'sortable'	=> false,
			'index'		=> 'banner'
		));
		
		return parent::_prepareColumns();
	}
	
	/**
	 * Return row URL for js event handlers
	 * 
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
	
	/**
	 * Grid url getter
	 * 
	 * @return string current grid url
	 */
	public function getGridUrl()
	{
		return $this->getUrl('*/*/grid', array('_current' => true));
	}
}