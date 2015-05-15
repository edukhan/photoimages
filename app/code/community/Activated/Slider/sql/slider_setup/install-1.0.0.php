<?php
$installer = $this;
$installer->startSetup();
/**
 * Create slider table
 */
$table = $installer->getConnection()
		->newTable($installer->getTable('slider/slider'))
		->addColumn('slider_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'unsigned' => true,
			'identity' => true,
			'nullable' => false,
			'primary' => true
		), 'Entity id')
		->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
			'nullable' => true
		), 'Title')
		->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
			'nullable' => false,
			'default' => 1
		), 'Status')
		->addColumn('conf_effect', Varien_Db_Ddl_Table::TYPE_TEXT, 15, array(
			'nullable' => false
		), 'Effect')
		->addColumn('conf_speed', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'unsigned' => true,
			'nullable' => false,
			'default'  => 5000
		), 'Speed')
		->addColumn('conf_navigation', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
			'nullable' => false,
			'default' => 0
		), 'Use control navigation')
		->addColumn('conf_pause', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
			'nullable' => false,
			'default' => 0
		), 'Pause on hover')
		->addIndex($installer->getIdxName(
				$installer->getTable('slider/slider'),
				array('slider_id'),
				Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
		),
				array('slider_id'),
				array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
		)
		->setComment('Activated slider');
$installer->getConnection()->createTable($table);

/**
 * Create banners table
 */
$table = $installer->getConnection()
		->newTable($installer->getTable('slider/banner'))
		->addColumn('banner_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'unsigned' => true,
			'identity' => true,
			'nullable' => false,
			'primary' => true
		), 'Entity id')
		->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
			'nullable' => true
		), 'Title')
		->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
			'nullable' => true,
			'default' => null
		), 'Image media path')
		->addColumn('caption', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
			'nullable' => true,
			'default' => null
		), 'Caption')
		->addColumn('link', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
			'nullable' => true,
			'default' => null
		), 'Link')
		->addIndex($installer->getIdxName(
			$installer->getTable('slider/banner'),
			array('banner_id'),
			Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
		),
			array('banner_id'),
			array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
		)
		->setComment('Activated slides');
$installer->getConnection()->createTable($table);

/*
 * Slider banner reference
 */
$table = $installer->getConnection()
		->newTable($installer->getTable('slider/reference'))
		->addColumn('reference_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'unsigned' => true,
			'identity' => true,
			'nullable' => false,
			'primary' => true
		), 'Entity id')
		->addColumn('slider_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'unsigned' => true,
			'nullable' => false
		), 'Slider id')
		->addColumn('banner_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'unsigned' => true,
			'nullable' => false
		), 'Banner id')
		->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'unsigned' => true,
			'nullable' => false,
			'default'  => 0
		), 'Position')
		->addIndex($installer->getIdxName('slider/reference', 'slider_id'), 'slider_id')
		->addForeignKey($installer->getFkName('slider/reference', 'slider_id', 'slider/slider', 'slider_id'),
		'slider_id', $installer->getTable('slider/slider'), 'slider_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->addIndex($installer->getIdxName('slider/reference', 'banner_id'), 'banner_id')
		->addForeignKey($installer->getFkName('slider/reference', 'banner_id', 'slider/banner', 'banner_id'),
		'banner_id', $installer->getTable('slider/banner'), 'banner_id', 
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
		->setComment('Banner reference');
$installer->getConnection()->createTable($table);

$installer->endSetup();