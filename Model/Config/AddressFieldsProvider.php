<?php

namespace GoMage\LightCheckout\Model\Config;

use Magento\Customer\Helper\Address as CustomerAddressHelper;
use Magento\Customer\Model\AttributeMetadataDataProvider;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Ui\Component\Form\AttributeMapper;
use Magento\Framework\App\RequestInterface;

class AddressFieldsProvider
{
    public static $requiredFields = [
        "firstname", "lastname", "street", "city", "region_id", "country_id", "postcode"
    ];

    public static $allowToEditFields = [
        "middlename", "prefix", "suffix", "company", "telephone", "fax", "vat_id"
    ];

    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var CustomerAddressHelper
     */
    private $customerAddressHelper;

    /**
     * @var AttributeMapper
     */
    private $attributeMapper;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * AddressFieldsProvider constructor.
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param SerializerInterface $serializer
     * @param AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param CustomerAddressHelper $customerAddressHelper
     * @param AttributeMapper $attributeMapper
     * @param RequestInterface $request
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        SerializerInterface $serializer,
        AttributeMetadataDataProvider $attributeMetadataDataProvider,
        CustomerAddressHelper $customerAddressHelper,
        AttributeMapper $attributeMapper,
        RequestInterface $request
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->serializer = $serializer;
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->customerAddressHelper = $customerAddressHelper;
        $this->attributeMapper = $attributeMapper;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getVisibleAddressAttributes()
    {
        $addressAttributes = $this->getAddressAttributesForFrontend();
        $configAddressAttributes = $this->getAddressAttributesFromConfiguration();

        $sortOrder = 10;
        $isNewRow = true;
        $lastWasWide = true;
        $visibleFields = [];
        foreach ($configAddressAttributes as $configAddressAttribute) {
            foreach ($addressAttributes as $key => $addressAttribute) {
                if ($configAddressAttribute['attributeCode'] === $addressAttribute->getAttributeCode() &&
                    $addressAttribute->getIsVisible()) {
                    $isNewRow = $this->getIsNewRow($lastWasWide, $isNewRow);
                    $addressAttribute
                        ->setIsWide($configAddressAttribute['isWide'])
                        ->setSortOrder($sortOrder)
                        ->setIsNewRow($isNewRow);
                    $visibleFields[] = $addressAttribute;
                    $lastWasWide = $configAddressAttribute['isWide'];
                    $sortOrder += 10;
                    break;
                }
            }
        }

        return $visibleFields;
    }

    /**
     * @return array
     */
    public function getAddressAttributesForFrontend()
    {
        $addressFields = [];
        /** @var AttributeInterface[] $collection */
        $collection = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );
        foreach ($collection as $key => $field) {
            if ($field->getIsUserDefined()) {
                continue;
            }
            if ('vat_id' === $field->getAttributeCode() && !$this->customerAddressHelper->isVatAttributeVisible()) {
                continue;
            } elseif ('region' === $field->getAttributeCode()) {
                continue;
            }
            $addressFields[] = $field;
        }

        return $addressFields;
    }

    /**
     * @param $lastWasWide
     * @param $isNewRow
     *
     * @return bool
     */
    private function getIsNewRow($lastWasWide, $isNewRow)
    {
        if ($lastWasWide == true) {
            $isNewRow = true;
        } elseif (!$lastWasWide && $isNewRow) {
            $isNewRow = false;
        } elseif (!$lastWasWide && !$isNewRow) {
            $isNewRow = true;
        }

        return $isNewRow;
    }

    /**
     * @return array
     */
    public function getGridAddressAttributes()
    {
        $addressAttributes = $this->getAddressAttributesForAdminGrid();
        $configAddressAttributes = $this->getAddressAttributesFromConfiguration();

        $availableConfigFields = [];
        foreach ($configAddressAttributes as $configAddressAttribute) {
            foreach ($addressAttributes as $key => $addressAttribute) {
                if ($configAddressAttribute['attributeCode'] === $addressAttribute->getAttributeCode()) {
                    $addressAttribute->setIsWide($configAddressAttribute['isWide'])
                        ->setStoreLabel($configAddressAttribute['label']);
                    $availableConfigFields[] = $addressAttribute;
                    unset($addressAttributes[$key]);
                    break;
                }
            }
        }

        return array_merge($availableConfigFields, $addressAttributes);
    }

    /**
     * @return array
     */
    public function getAddressAttributesForAdminGrid()
    {
        $addressFields = [];
        /** @var AttributeInterface[] $collection */
        $collection = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );
        foreach ($collection as $key => $field) {
            if ($field->getIsUserDefined()) {
                continue;
            }
            if ('region' === $field->getAttributeCode()) {
                continue;
            }
            $addressFields[] = $field;
        }

        return $addressFields;
    }

    /*
     * Add labels to checkout fields
     */
    public function getAddressAttributes()
    {
        /** @var AttributeInterface[] $attributes */
        $attributes = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );
        $configAddressAttributes = $this->getAddressAttributesFromConfiguration();

        $elements = [];
        foreach ($attributes as $attribute) {
            $code = $attribute->getAttributeCode();
            if ($attribute->getIsUserDefined()) {
                continue;
            }
            foreach ($configAddressAttributes as $configAddressAttribute) {
                if ($configAddressAttribute['attributeCode'] === $code) {
                    if (!empty($configAddressAttribute['label'])) {
                        $attribute->setData('store_label', $configAddressAttribute['label']);
                    }
                }
            }
            $elements[$code] = $this->attributeMapper->map($attribute);
            if (isset($elements[$code]['label'])) {
                $label = $elements[$code]['label'];
                $elements[$code]['label'] = __($label);
            }
        }

        return $elements;
    }

    /**
     * @return array|bool|float|int|string
     */
    public function getAddressAttributesFromConfiguration()
    {
        $websiteId = $this->request->getParam('website');
        try {
            $configAddressAttributes = $this->serializer->unserialize(
                $this->checkoutConfigurationsProvider->getAddressFieldsForm($websiteId)
            );
        } catch (\InvalidArgumentException $e) {
            $configAddressAttributes = [];
        }
        return $configAddressAttributes;
    }
}
