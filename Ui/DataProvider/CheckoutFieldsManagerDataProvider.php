<?php

declare(strict_types=1);

namespace GoMage\LightCheckout\Ui\DataProvider;

use Magento\Framework\Api\Filter;
use Magento\Ui\DataProvider\AbstractDataProvider;
use GoMage\LightCheckout\Model\Config\AddressFieldsProvider;

class CheckoutFieldsManagerDataProvider extends AbstractDataProvider
{
    /**
     * @var AddressFieldsProvider
     */
    private AddressFieldsProvider $addressFieldsProvider;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        AddressFieldsProvider $addressFieldsProvider,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->addressFieldsProvider = $addressFieldsProvider;
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
        $elements = $this->addressFieldsProvider->getAddressAttributes();
        $data = [];
        foreach ($elements as $element) {
            $data[] = [
                'field' => isset($element['label']) ? $element['label']->getText() : '',
                'label' => isset($element['label']) ? $element['label']->getText() : '',
                'width' => 50, // todo: read from module config and add here correct value
                'is_enabled' => $element['visible'],
                'is_required' => $element['required'],
            ];
        }


        return ['items' => $data];
    }


}
