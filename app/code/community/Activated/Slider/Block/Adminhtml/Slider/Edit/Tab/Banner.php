<?php
/**
 * Banner list admin grid
 * 
 * @author Activated
 */
class Activated_Slider_Block_Adminhtml_Slider_Edit_Tab_Banner extends 
Mage_Adminhtml_Block_Widget_Grid
implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

	/**
	 * Init grid default properties
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setId('bannerGrid');
		$this->setDefaultSort('position');
		$this->setDefaultDir('ASC');
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
		$sliderId = $this->_getSlider()->getId();
		$reference_table = Mage::helper('slider/admin')->getTable('slider/reference');
		
		if (empty($sliderId)) {
			$sliderId = '0';
		}
		
		$collection = Mage::getModel('slider/banner')->getCollection();
		$collection->getSelect()
					->joinLeft($reference_table, 
						'main_table.banner_id = ' . $reference_table . '.banner_id && ' . $sliderId . ' = ' . $reference_table . '.slider_id', 
						array($reference_table . '.position'));
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
		$this->addColumn('banner_referenced', array(
			'header'		=> Mage::helper('slider')->__('Included'),
			'type'			=> 'checkbox',
			'index'			=> 'banner_id',
			'values'		=> $this->_getSelectedBannerReferenced(),
			'align'			=> 'center',
			'field_name'	=> 'banner_referenced[]'
		));
		
		$this->addColumn('banner_id', array(
			'header'	=> Mage::helper('slider')->__('ID'),
			'width'		=> '50px',
			'index'		=> 'banner_id',
			'filter_index' => 'main_table.banner_id'
		));
		
		$this->addColumn('banner_title', array(
			'header'	=> Mage::helper('slider')->__('Banner Title'),
			'index'		=> 'title'
		));
		
		$this->addColumn('position', array(
			'header'		=> Mage::helper('slider')->__('Position'),
			'type'			=> 'number',
			'field_name'	=> 'position',
			'index'			=> 'position',
			'width'			=> '70px',
			'editable'		=> true
		));
		
		return parent::_prepareColumns();
	}

	/**
	* Get currently edited slider
	*
	* @return object
	*/
	protected function _getSlider()
	{
		return Mage::registry('slider');
	}
	
	/**
	 * Return row URL for js event handlers
	 * 
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/adminhtml_banner/edit', array('id' => $row->getId()));
	}
	
	/**
	 * Grid url getter
	 * 
	 * @return string current grid url
	 */
	public function getGridUrl()
	{
		return $this->getUrl('*/*/grid', array('id' => $this->_getSlider()->getId(), '_current' => true));
	}
	
	/**
	 * Prepare label for tab
	 * 
	 * @return string
	 */
	public function getTabLabel()
	{
		return Mage::helper('slider')->__('Associated Banners');
	}
	
	/**
	 * Prepare title for tab
	 * 
	 * @return string
	 */
	public function getTabTitle()
	{
		return Mage::helper('slider')->__('Associated Banners');
	}
	
	/**
	 * Return whether tab can be shown
	 * 
	 * @return bool
	 */
	public function canShowTab()
	{
		return true;
	}
	
	/**
	 * Return whether tab can be shown
	 * 
	 * @return bool
	 */
	public function isHidden()
	{
		return false;
	}
	
	/**
	 * Return selected banner references
	 * 
	 * @return array
	 */
	public function _getSelectedBannerReferenced()
	{
		$model = Mage::getModel('slider/reference')
				->getCollection()
				->addFieldToFilter(
			'slider_id', $this->_getSlider()->getId()
		);
		
		$referenced = array();
		
		if (count($model) > 0) {
			foreach($model as $item) {
				$referenced[] = $item->getBannerId();
			}
		}
		
		return $referenced;
	}
}