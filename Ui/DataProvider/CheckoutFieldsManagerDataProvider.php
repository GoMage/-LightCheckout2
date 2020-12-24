<?php

declare(strict_types=1);

namespace GoMage\LightCheckout\Ui\DataProvider;

use Magento\Framework\Api\Filter;
use Magento\Ui\DataProvider\AbstractDataProvider;
use GoMage\LightCheckout\Model\Config\AddressFieldsProvider;
use Magento\Customer\Helper\Address as CustomerAddressHelper;

class CheckoutFieldsManagerDataProvider extends AbstractDataProvider
{
    /**
     * @var AddressFieldsProvider
     */
    private $addressFieldsProvider;

    /**
     * @var CustomerAddressHelper
     */
    private $customerAddressHelper;

    /**
     * CheckoutFieldsManagerDataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param AddressFieldsProvider $addressFieldsProvider
     * @param CustomerAddressHelper $customerAddressHelper
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        AddressFieldsProvider $addressFieldsProvider,
        CustomerAddressHelper $customerAddressHelper,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->addressFieldsProvider = $addressFieldsProvider;
        $this->customerAddressHelper = $customerAddressHelper;
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
     * @return array[]
     */
    public function getData()
    {
        $attributes = $this->addressFieldsProvider->getGridAddressAttributes();
        $data = [];
        foreach ($attributes as $attribute) {
            $data[] = [
                'field' => $attribute->getFrontendLabel(),
                'label' => $attribute->getStoreLabel(),
                'width' => $attribute->getIsWide(),
                'is_enabled' => ($attribute->getAttributeCode() === 'vat_id') ?
                    $this->customerAddressHelper->isVatAttributeVisible() : $attribute->getIsVisible(),
                'is_required' => $attribute->getIsRequired(),
            ];
        }

        return ['items' => $data];
    }
}
