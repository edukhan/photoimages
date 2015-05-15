<?php
 
class Oye_Themetuner_Model_Mysql4_Preset extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('themetuner/preset', 'preset_id');
    }
}