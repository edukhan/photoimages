<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
-- DROP TABLE IF EXISTS {$this->getTable('themetuner_preset')};
CREATE TABLE `{$this->getTable('themetuner_preset')}` (
  `preset_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(6) NOT NULL DEFAULT '0',
  `name` text NOT NULL,
  `css` longtext NOT NULL,
  `template` varchar(20) NOT NULL,
  `layout_updates` longtext NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'global',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `logo` varchar(255) DEFAULT NULL,
  `theme_package_dir` varchar(100) DEFAULT NULL,
  `theme_locale_dir` varchar(100) DEFAULT NULL,
  `theme_template_dir` varchar(100) DEFAULT NULL,
  `theme_skin_dir` varchar(100) DEFAULT NULL,
  `theme_layout_dir` varchar(100) DEFAULT NULL,
  `theme_default_dir` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`preset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
 
$installer->endSetup();