<?php

namespace GoMage\LightCheckout\Model;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\LightCheckout\Model\Config\Source\OperationSystemsForDevices;
use Mobile_Detect;

class IsEnableLightCheckoutForDevice
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var Mobile_Detect
     */
    private $mobileDetect;

    /**
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param Mobile_Detect $mobileDetect
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        Mobile_Detect $mobileDetect
    ) {
        $this->mobileDetect = $mobileDetect;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if (!class_exists(Mobile_Detect::class)) {
            return true;
        }

        if (!$this->mobileDetect->isMobile()) {
            return (bool)$this->checkoutConfigurationsProvider->isShowOnDesktopAndLaptop();
        }

        if ($this->mobileDetect->isTablet()) {
            $devices = explode(',', $this->checkoutConfigurationsProvider->getShowOnTabletOperationSystems());
        } else {
            $devices = explode(',', $this->checkoutConfigurationsProvider->getShowOnSmartphoneOperationSystems());
        }

        if ($this->mobileDetect->isAndroidOS()) {
            return in_array(OperationSystemsForDevices::ANDROID, $devices);
        }
        if ($this->mobileDetect->isBlackBerryOS()) {
            return in_array(OperationSystemsForDevices::BLACKBERRY, $devices);
        }
        if ($this->mobileDetect->isiOS()) {
            return in_array(OperationSystemsForDevices::IOS, $devices);
        }

        return in_array(OperationSystemsForDevices::OTHER, $devices);
    }
}
