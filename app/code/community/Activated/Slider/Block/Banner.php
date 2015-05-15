<?php
/**
 * Slider list block
 * 
 * @author Activated
 */

class Activated_Slider_Block_Banner extends Mage_Core_Block_Template
{
	/**
	 * Slider id
	 */
	protected $_id = null;
	
	/**
	 * Slide collection
	 */
	protected $_bannerCollection = null;
	
	/**
	 * Retrieve slide collection
	 */
	protected function _getCollection()
	{
		return Mage::getModel('slider/banner')->getCollection();
	}
	
	/**
	 * Retrieve prepared sliders collection
	 */
	public function getCollection()
	{
		$reference_table = Mage::helper('slider/admin')->getTable('slider/reference');

		if (is_null($this->_bannerCollection)) {
			$this->_bannerCollection = $this->_getCollection();
			$this->_bannerCollection->getSelect()
					->join($reference_table,
						'main_table.banner_id = ' . $reference_table . '.banner_id and ' . $reference_table '.slider_id = "' . $this->_id . '"'
					);
		}
		
		return $this->_bannerCollection;
	}
	
	/**
	 * Return URL for resized slider item image
	 * 
	 * @param Activated_Sliders_Model_Sliders $item
	 * @param integer $width
	 * @retun string|false
	 */
	public function getImageUrl($item, $width)
	{
		return Mage::helper('slider/image')->resize($item, $width);
	}
	
	/**
	 * Set slider id
	 * 
	 * @return int
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}
	
	/**
	 * Get slider id
	 * 
	 * @return int
	 */
	public function getId() {
		return $this->_id;
	}
}