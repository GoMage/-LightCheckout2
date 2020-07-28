<?php

namespace GoMage\LightCheckout\Model;


class IsEnableLightCheckout
{
    /**
     * @var \GoMage\Core\Helper\Data
     */
    protected $helper;

    /**
     * @var Config\CheckoutConfigurationsProvider
     */
    protected $checkoutConfigurationsProvider;

    /**
     * @var IsEnableLightCheckoutForDevice
     */
    protected $isEnableLightCheckoutForDevice;

    /**
     * IsEnableLightCheckout constructor.
     * @param Config\CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param \GoMage\Core\Helper\Data $helper
     * @param IsEnableLightCheckoutForDevice $isEnableLightCheckoutForDevice
     */
    public function __construct(
        \GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        \GoMage\Core\Helper\Data $helper,
        \GoMage\LightCheckout\Model\IsEnableLightCheckoutForDevice $isEnableLightCheckoutForDevice
    ) {
        $this->helper = $helper;
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->isEnableLightCheckoutForDevice = $isEnableLightCheckoutForDevice;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        return $this->helper->isA(\GoMage\LightCheckout\Setup\InstallData::MODULE_NAME)
            && $this->checkoutConfigurationsProvider->isLightCheckoutEnabled()
            && $this->isEnableLightCheckoutForDevice->execute();
    }
}
