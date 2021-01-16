<?php

declare(strict_types=1);

namespace GoMage\LightCheckout\Ui\DataProvider;

use Magento\Framework\Api\Filter;
use Magento\Ui\DataProvider\AbstractDataProvider;
use GoMage\LightCheckout\Model\Config\AddressFieldsProvider;
use Magento\Customer\Helper\Address as CustomerAddressHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Helper\Address;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Config\Block\System\Config\Form;

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
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * CheckoutFieldsManagerDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param AddressFieldsProvider $addressFieldsProvider
     * @param Address $customerAddressHelper
     * @param ScopeConfigInterface $config
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        AddressFieldsProvider $addressFieldsProvider,
        CustomerAddressHelper $customerAddressHelper,
        ScopeConfigInterface $config,
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->addressFieldsProvider = $addressFieldsProvider;
        $this->customerAddressHelper = $customerAddressHelper;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->request = $request;
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
        if ($this->request->getParam('store') ||
            $this->request->getParam('section') !== 'gomage_light_checkout_configuration') {
            return [];
        }
        $websiteId = $this->request->getParam('website');
        $attributes = $this->addressFieldsProvider->getGridAddressAttributes();
        $data = [];
        foreach ($attributes as $position => $attribute) {
            if (in_array($attribute->getAttributeCode(), AddressFieldsProvider::$requiredFields)) {
                $isVisible = '1';
                $isEnabledDisabled = 1;
                $isRequired = '1';
                $isRequiredDisabled = 1;
            } elseif ($attribute->getAttributeCode() === 'vat_id') {
                $isVisible = $this->config->getValue(
                    Address::XML_PATH_VAT_FRONTEND_VISIBILITY,
                    $websiteId ? Form::SCOPE_WEBSITES : ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                    $websiteId ?: ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                );
                $isEnabledDisabled = 0;
                $isRequired = '0';
                $isRequiredDisabled = 1;
            } elseif (in_array($attribute->getAttributeCode(), AddressFieldsProvider::$allowToEditFields)) {
                $configValue = $this->config->getValue(
                    'customer/address/' . $attribute->getAttributeCode() . '_show',
                    $websiteId ? Form::SCOPE_WEBSITES : ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                    $websiteId ?: ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                );
                if ($configValue === '1' && $attribute->getAttributeCode() == 'middlename') {
                    $isVisible = '1';
                    $isEnabledDisabled = 0;
                    $isRequired = '0';
                    $isRequiredDisabled = 1;
                } elseif (!$configValue && $attribute->getAttributeCode() == 'middlename') {
                    $isVisible = '0';
                    $isEnabledDisabled = 0;
                    $isRequired = '0';
                    $isRequiredDisabled = 1;
                } elseif ($configValue === 'req') {
                    $isVisible = '1';
                    $isEnabledDisabled = 0;
                    $isRequired = '1';
                    $isRequiredDisabled = 0;
                } elseif ($configValue === 'opt') {
                    $isVisible = '1';
                    $isEnabledDisabled = 0;
                    $isRequired = '0';
                    $isRequiredDisabled = 0;
                } else {
                    $isVisible = '0';
                    $isEnabledDisabled = 0;
                    $isRequired = '0';
                    $isRequiredDisabled = 0;
                }
            } else {
                $isVisible = $attribute->getIsVisible();
                $isEnabledDisabled = 0;
                $isRequired = $attribute->getIsRequired();
                $isRequiredDisabled = 0;
            }

            $data[] = [
                'field' => $attribute->getFrontendLabel(),
                'label' => $attribute->getStoreLabel(),
                'width' => $attribute->getIsWide(),
                'is_enabled' => [
                    'value' => $isVisible,
                    'disabled' => $isEnabledDisabled
                ],
                'is_required' => [
                    'value' => $isRequired,
                    'disabled' => $isRequiredDisabled
                ],
                'position' => $position,
                'attributeCode' => $attribute->getAttributeCode()
            ];
        }

        return ['fields' => $data];
    }
}
