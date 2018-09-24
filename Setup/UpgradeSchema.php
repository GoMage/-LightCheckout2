<?php

namespace GoMage\PostCode\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.1.2', '<')) {
            $setup->startSetup();

            $setup->getConnection()->createTable(
                $setup->getConnection()->newTable(
                    $setup->getTable('gomage_postcode')
                )->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Record Id'
                )->addColumn(
                    'zip_code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    16,
                    ['unsigned' => true, 'nullable' => false],
                    'Zip Code'
                )->addColumn(
                    'encoded_data',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Data'
                )->setComment(
                    'Post codes'
                )->addIndex(
                    $setup->getIdxName(
                        $setup->getTable('gomage_postcode'),
                        ['zip_code'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    'zip_code',
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )
            );

            $setup->endSetup();
        }
    }
}
