<?php

declare(strict_types=1);

namespace GoMage\LightCheckout\Ui\DataProvider;

use Magento\Framework\Api\Filter;
use Magento\Ui\DataProvider\AbstractDataProvider;

class CheckoutFieldsManagerDataProvider extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Disable for collection processing
     *
     * @param Filter $filter
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function addFilter(Filter $filter)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $myData = '{
          "items": [
            {
              "sku": "24-WW5",
              "product": "Push It Messenger Bag",
              "qtyToShip": 1
            },
            {
              "sku": "24-WB04",
              "product": "Push It Messenger Bag for men",
              "qtyToShip": 1
            }
          ]
        }';

        $result = json_decode($myData, true);

        return $result;
    }


}
