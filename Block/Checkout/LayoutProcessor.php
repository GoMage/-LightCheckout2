<?php

namespace GoMage\LightCheckout\Block\Checkout;

use GoMage\LightCheckout\Model\Layout\FetchArgs;
use Magento\Checkout\Block\Checkout\AttributeMerger;
use Magento\Store\Api\StoreResolverInterface;

/**
 * Class LayoutProcessor
 */
class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    /**
     * @var \Magento\Customer\Model\AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var \Magento\Ui\Component\Form\AttributeMapper
     */
    protected $attributeMapper;

    /**
     * @var AttributeMerger
     */
    protected $merger;

    /**
     * @var \Magento\Customer\Model\Options
     */
    private $options;

    /**
     * @var StoreResolverInterface
     */
    private $storeResolver;

    /**
     * @var \Magento\Shipping\Model\Config
     */
    private $shippingConfig;

    /**
     * @var \GoMage\LightCheckout\Model\Layout\FetchArgs
     */
    private $fetchArgs;

    /**
     * @param \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param \Magento\Ui\Component\Form\AttributeMapper $attributeMapper
     * @param AttributeMerger $merger
     * @param FetchArgs $definitionFetcher
     * @param \Magento\Shipping\Model\Config $shippingConfig
     * @param StoreResolverInterface $storeResolver
     */
    public function __construct(
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider,
        \Magento\Ui\Component\Form\AttributeMapper $attributeMapper,
        AttributeMerger $merger,
        FetchArgs $fetchArgs,
        \Magento\Shipping\Model\Config $shippingConfig,
        StoreResolverInterface $storeResolver,
        \Magento\Customer\Model\Options $options
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->attributeMapper = $attributeMapper;
        $this->merger = $merger;
        $this->fetchArgs = $fetchArgs;
        $this->shippingConfig = $shippingConfig;
        $this->storeResolver = $storeResolver;
        $this->options = $options;
    }

    /**
     * @return array
     */
    private function getAddressAttributes()
    {
        /** @var \Magento\Eav\Api\Data\AttributeInterface[] $attributes */
        $attributes = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );

        $elements = [];
        foreach ($attributes as $attribute) {
            $code = $attribute->getAttributeCode();
            if ($attribute->getIsUserDefined()) {
                continue;
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
     * Convert elements(like prefix and suffix) from inputs to selects when necessary
     *
     * @param array $elements address attributes
     * @param array $attributesToConvert fields and their callbacks
     *
     * @return array
     */
    private function convertElementsToSelect($elements, $attributesToConvert)
    {
        $codes = array_keys($attributesToConvert);
        foreach (array_keys($elements) as $code) {
            if (!in_array($code, $codes)) {
                continue;
            }
            $options = call_user_func($attributesToConvert[$code]);
            if (!is_array($options)) {
                continue;
            }
            $elements[$code]['dataType'] = 'select';
            $elements[$code]['formElement'] = 'select';

            foreach ($options as $key => $value) {
                $elements[$code]['options'][] = [
                    'value' => $key,
                    'label' => $value,
                ];
            }
        }

        return $elements;
    }

    /**
     * Process block js Layout.
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function process($jsLayout)
    {
        $attributesToConvert = [
            'prefix' => [$this->options, 'getNamePrefixOptions'],
            'suffix' => [$this->options, 'getNameSuffixOptions'],
        ];

        $elements = $this->getAddressAttributes();
        $elements = $this->convertElementsToSelect($elements, $attributesToConvert);

        if (isset($jsLayout['components']['checkout']['children']['configuration']['children']
            ['shipping-rates-validation']['children']
        )) {
            $jsLayout['components']['checkout']['children']['configuration']['children']
            ['shipping-rates-validation']['children'] =
                $this->processShippingRates(
                    $jsLayout['components']['checkout']['children']['configuration']['children']
                    ['shipping-rates-validation']['children']
                );
        }

        if (isset($jsLayout['components']['checkout']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children']
        )) {
            $fields = $jsLayout['components']['checkout']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'];
            $jsLayout['components']['checkout']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'] = $this->merger->merge(
                $elements,
                'checkoutProvider',
                'shippingAddress',
                $fields
            );
        }

        if (isset($jsLayout['components']['checkout']['children']['billingAddress']['children']
            ['billing-address-fieldset']['children']
        )) {
            $fields = $jsLayout['components']['checkout']['children']['billingAddress']['children']
            ['billing-address-fieldset']['children'];
            $jsLayout['components']['checkout']['children']['billingAddress']['children']
            ['billing-address-fieldset']['children'] = $this->merger->merge(
                $elements,
                'checkoutProvider',
                'billingAddress',
                $fields
            );
        }

        $jsLayout['components']['checkout']['children']['payment']['children']['renders']['children']
            = $this->mergePaymentMethodsRenders();

        return $jsLayout;
    }

    /**
     * Merge payment method renders from standard path to new path.
     *
     * @return array
     */
    private function mergePaymentMethodsRenders()
    {
        $path = '//referenceBlock[@name="checkout.root"]/arguments/argument[@name="jsLayout"]'
            . '/item[@name="components"]/item[@name="checkout"]/item[@name="children"]'
            . '/item[@name="steps"]/item[@name="children"]/item[@name="billing-step"]'
            . '/item[@name="children"]/item[@name="payment"]/item[@name="children"]'
            . '/item[@name="renders"]/item[@name="children"]';

        $definitions = $this->fetchArgs->execute('checkout_index_index', $path);

        return $definitions;
    }

    /**
     * Process shipping configuration to exclude inactive carriers.
     *
     * @param array $shippingRatesLayout
     *
     * @return array
     */
    private function processShippingRates($shippingRatesLayout)
    {
        $activeCarriers = $this->shippingConfig->getActiveCarriers(
            $this->storeResolver->getCurrentStoreId()
        );

        foreach (array_keys($shippingRatesLayout) as $carrierName) {
            $carrierKey = str_replace('-rates-validation', '', $carrierName);
            if (!array_key_exists($carrierKey, $activeCarriers)) {
                unset($shippingRatesLayout[$carrierName]);
            }
        }

        return $shippingRatesLayout;
    }
}
