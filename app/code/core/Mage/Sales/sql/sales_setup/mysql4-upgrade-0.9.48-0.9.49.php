<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/* @var $installer Mage_Sales_Model_Mysql4_Setup */
$installer = $this;
$table_prefix = Mage::getConfig()->getTablePrefix();

$this->startSetup();

$installer->run("
	DROP TABLE IF EXISTS `{$installer->getTable('sales/refunded_aggregated')}`;
    CREATE TABLE IF NOT EXISTS `{$installer->getTable('sales/refunded_aggregated')}` (
        `id`                        int(11) unsigned NOT NULL auto_increment,
        `period`                    date NOT NULL DEFAULT '0000-00-00',
        `store_id`                  smallint(5) unsigned NULL DEFAULT NULL,
        `order_status`              varchar(50) NOT NULL default '',
        `orders_count`              int(11) NOT NULL DEFAULT '0',
        `refunded`                  decimal(12,4) NOT NULL DEFAULT '0',
        `online_refunded`           decimal(12,4) NOT NULL DEFAULT '0',
        `offline_refunded`          decimal(12,4) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`, `order_status`),
        KEY `IDX_STORE_ID` (`store_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	DROP TABLE IF EXISTS `{$installer->getTable('sales/refunded_aggregated_order')}` ;
    CREATE TABLE IF NOT EXISTS `{$installer->getTable('sales/refunded_aggregated_order')}` (
        `id`                        int(11) unsigned NOT NULL auto_increment,
        `period`                    date NOT NULL DEFAULT '0000-00-00',
        `store_id`                  smallint(5) unsigned NULL DEFAULT NULL,
        `order_status`              varchar(50) NOT NULL default '',
        `orders_count`              int(11) NOT NULL DEFAULT '0',
        `refunded`                  decimal(12,4) NOT NULL DEFAULT '0',
        `online_refunded`           decimal(12,4) NOT NULL DEFAULT '0',
        `offline_refunded`          decimal(12,4) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        UNIQUE KEY `UNQ_PERIOD_STORE_ORDER_STATUS` (`period`,`store_id`, `order_status`),
        KEY `IDX_STORE_ID` (`store_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


$installer->getConnection()->addConstraint(
    $table_prefix.'SALES_REFUNDED_AGGREGATED_STORE',
    $installer->getTable('sales/refunded_aggregated'),
    'store_id',
    $installer->getTable('core/store'),
    'store_id',
    'SET NULL'
);

$installer->getConnection()->addConstraint(
    $table_prefix.'SALES_REFUNDED_AGGREGATED_ORDER_STORE',
    $installer->getTable('sales/refunded_aggregated_order'),
    'store_id',
    $installer->getTable('core/store'),
    'store_id',
    'SET NULL'
);

$this->endSetup();
