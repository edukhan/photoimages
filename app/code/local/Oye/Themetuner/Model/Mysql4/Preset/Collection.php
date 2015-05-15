<?php
class Oye_Themetuner_Model_Mysql4_Preset_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    private $_themeFieldExpr = "CONCAT(theme_package_dir, '|', theme_locale_dir, '|', theme_template_dir, '|', theme_skin_dir, '|', theme_layout_dir, '|', theme_default_dir)";
    
    public function _construct()
    {
        $this->_init('themetuner/preset');
    }
    
    public function addTypeFilter($type = 'global') {
    	$this->addFieldToFilter('type', array('eq' => $type));
    	return $this;
    }

    public function addThemeFilter($themeComponents) {
        $this->getSelect()->where($this->_themeFieldExpr.' = ?', $themeComponents);
        return $this;
    }
    
    public function addNameFilter($name) {
        $this->getSelect()->where('main_table.name = ?', $name);
        return $this;
    }
    
    protected function _initSelect() {
        parent::_initSelect();
        $this->getSelect()->columns(new Zend_Db_Expr(
            $this->_themeFieldExpr.' AS theme'
        ));
        $this->getSelect()->order('theme ' . Varien_Db_Select::SQL_ASC);
        
        $this->_joinFields();
        
        return $this;
    }
    
    protected function _joinFields() {
        $storeTable = Mage::getSingleton('core/resource')->getTableName('core/store');

        $this->getSelect()
            ->joinLeft(array('st' => $storeTable), 'st.store_id = main_table.store_id', array('store_name' => 'st.name'));
            
        return $this;
    }
}