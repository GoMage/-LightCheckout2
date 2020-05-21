<?php

namespace GoMage\LightCheckout\Model\Config\CheckoutAddressFieldsSorting;

use GoMage\LightCheckout\Model\Config\AddressFieldsProvider;
use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Framework\App\Area;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class FieldsProvider
{
    /**
     * @var AddressFieldsProvider
     */
    private $addressFieldsProvider;

    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var FieldsDataTransferObjectFactory
     */
    private $fieldsDataTransferObjectFactory;

    /**
     * @var State
     */
    private $state;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param AddressFieldsProvider $addressFieldsProvider
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param FieldsDataTransferObjectFactory $fieldsDataTransferObjectFactory
     * @param State $state
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     */
    public function __construct(
        AddressFieldsProvider $addressFieldsProvider,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        FieldsDataTransferObjectFactory $fieldsDataTransferObjectFactory,
        State $state,
        RequestInterface $request,
        LoggerInterface $logger
    ) {
        $this->addressFieldsProvider = $addressFieldsProvider;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->fieldsDataTransferObjectFactory = $fieldsDataTransferObjectFactory;
        $this->state = $state;
        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * @return FieldsDataTransferObject
     */
    public function get()
    {
        $fieldsDataTransferObject = $this->fieldsDataTransferObjectFactory->create();
        $visibleFields = [];
        $notVisibleFields = $this->addressFieldsProvider->get();

        $storeId = $this->detectStoreId();
        $fieldsConfig = json_decode($this->checkoutConfigurationsProvider->getAddressFieldsForm($storeId), true) ?: [];
        $sortOrder = 1;
        $isNewRow = true;
        $lastWasWide = true;
        foreach ($fieldsConfig as $fieldConfig) {
            foreach ($notVisibleFields as $key => $visibleField) {
                if ($fieldConfig['code'] == $visibleField->getAttributeCode()) {
                    $isNewRow = $this->getIsNewRow($lastWasWide, $isNewRow);
                    $visibleField->setIsWide($fieldConfig['isWide'])
                        ->setSortOrder($sortOrder)
                        ->setIsNewRow($isNewRow);
                    $visibleFields[] = $visibleField;
                    unset($notVisibleFields[$key]);
                    $lastWasWide = $fieldConfig['isWide'];
                    $sortOrder += 5;
                    break;
                }
            }
        }

        $fieldsDataTransferObject->setVisibleFields($visibleFields);
        $fieldsDataTransferObject->setNotVisibleFields($notVisibleFields);

        return $fieldsDataTransferObject;
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
     * @return int|null
     * @throws LocalizedException
     */
    private function detectStoreId()
    {
        $storeId = null;
        try {
            if ($this->state->getAreaCode() === Area::AREA_ADMINHTML && (int)$this->request->getParam('store')) {
                $storeId = (int)$this->request->getParam('store');
            }
        } catch (LocalizedException $e) {
            $this->logger->warning((string)$e, ['module' => 'GoMage_LightCheckout']);
        }

        return $storeId;
    }
}
