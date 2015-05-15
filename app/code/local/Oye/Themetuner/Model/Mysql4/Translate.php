<?php
 
class Oye_Themetuner_Model_Mysql4_Translate extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('themetuner/translate', 'key_id');
    }
    
    /**
     * Retrieve translation array for tune / store / locale code
     *
     * @param int $presetId
     * @param int $storeId
     * @param string|Zend_Locale $locale
     * @return array
     */
    public function getTranslationArray($presetId = null, $storeId = null, $locale = null)
    {
        if (!Mage::isInstalled()) {
            return array();
        }
        
        if (!$presetId) {
            return array();
        }
        
        if (is_null($storeId)) {
            $storeId = Mage::app()->getStore()->getId();
        }

        $adapter = $this->_getReadAdapter();
        if (!$adapter) {
            return array();
        }

        $select = $adapter->select()
            ->from($this->getMainTable(), array('string', 'translate'))
            ->where('preset_id = :preset_id')
            ->where('store_id IN (0 , :store_id)')
            ->where('locale = :locale')
            ->order('store_id');

        $bind = array(
            ':preset_id'   => $presetId,
            ':locale'   => (string)$locale,
            ':store_id' => $storeId
        );

        return $adapter->fetchPairs($select, $bind);

    }
}