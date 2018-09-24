<?php

namespace GoMage\LightCheckout\Model\ResourceModel\PostCode;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \GoMage\LightCheckout\Model\PostCode::class,
            \GoMage\LightCheckout\Model\ResourceModel\PostCode::class
        );
    }
}
