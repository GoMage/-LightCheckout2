<?php

namespace GoMage\LightCheckout\Model;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\LightCheckout\Model\Config\Source\OperationSystemsForDevices;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class IsEnableLightCheckoutForDevice
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * IsEnableLightCheckoutForDevice constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if (!class_exists(\Mobile_Detect::class)) {
            return true;
        }

        $detect = new \Mobile_Detect();

        if (!$detect->isMobile()) {
            return (bool)$this->scopeConfig->getValue(
                CheckoutConfigurationsProvider::XML_PATH_LIGHT_CHECKOUT_DEVICES_DESKTOP,
                ScopeInterface::SCOPE_STORE
            );
        }

        if ($detect->isTablet()) {
            $devices = explode(',', (string) $this->scopeConfig->getValue(
                CheckoutConfigurationsProvider::XML_PATH_LIGHT_CHECKOUT_DEVICES_TABLET,
                ScopeInterface::SCOPE_STORE
            ));
        } else {
            $devices = explode(',', (string) $this->scopeConfig->getValue(
                CheckoutConfigurationsProvider::XML_PATH_LIGHT_CHECKOUT_DEVICES_TABLET,
                ScopeInterface::SCOPE_STORE
            ));
        }

        if ($detect->isAndroidOS()) {
            return in_array(OperationSystemsForDevices::ANDROID, $devices);
        }
        if ($detect->isBlackBerryOS()) {
            return in_array(OperationSystemsForDevices::BLACKBERRY, $devices);
        }
        if ($detect->isiOS()) {
            return in_array(OperationSystemsForDevices::IOS, $devices);
        }

        return in_array(OperationSystemsForDevices::OTHER, $devices);
    }
}
