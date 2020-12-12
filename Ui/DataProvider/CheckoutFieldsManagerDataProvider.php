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
              "field": "First Name",
              "label": "First Name",
              "width": 50,
              "is_enabled": {"config": {
                "checked": true
              }},
              "is_required": 1
            },
            {
              "field": "Last Name",
              "label": "Last Name",
              "width": 100,
              "is_enabled": 0,
              "is_required": 1
            }
          ]
        }';

        $result = json_decode($myData, true);

        return $result;
    }


}
