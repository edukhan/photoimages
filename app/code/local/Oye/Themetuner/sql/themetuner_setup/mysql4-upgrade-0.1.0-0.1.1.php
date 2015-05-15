<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
-- DROP TABLE IF EXISTS {$this->getTable('themetuner/translate')};
CREATE TABLE `{$this->getTable('themetuner/translate')}` (
  `key_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `preset_id` int(6) NOT NULL DEFAULT '0',
  `store_id` int(6) NOT NULL DEFAULT '0',
  `string` varchar(255) NOT NULL DEFAULT '',
  `translate` varchar(255) DEFAULT NULL,
  `locale` varchar(20) NOT NULL DEFAULT 'en_US',
  PRIMARY KEY (`key_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
 
$installer->endSetup();