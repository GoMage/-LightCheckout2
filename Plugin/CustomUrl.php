<?php
declare(strict_types=1);

namespace GoMage\LightCheckout\Plugin;

use GoMage\Core\Helper\Data as CoreHelper;
use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\LightCheckout\Model\IsEnableLightCheckoutForDevice;
use GoMage\LightCheckout\Setup\InstallData;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class CustomUrl
 * @package GoMage\LightCheckout\Plugin
 */
class CustomUrl
{
    /**
     * @var CoreHelper
     */
    private $helper;

    /**
     * @var IsEnableLightCheckoutForDevice
     */
    private $isEnableLightCheckoutForDevice;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * CustomUrl constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param CoreHelper $helper
     * @param IsEnableLightCheckoutForDevice $isEnableLightCheckoutForDevice
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CoreHelper $helper,
        IsEnableLightCheckoutForDevice $isEnableLightCheckoutForDevice
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
        $this->isEnableLightCheckoutForDevice = $isEnableLightCheckoutForDevice;
    }

    /**
     * @param UrlInterface $subject
     * @param null $routePath
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeGetUrl(UrlInterface $subject, $routePath = null)
    {
        if (is_string($routePath)) {
            $routePath = trim($routePath, '/');
        }
        $customUrl = $this->scopeConfig->getValue(
            CheckoutConfigurationsProvider::XML_PATH_LIGHT_CHECKOUT_GENERAL_URL,
            ScopeInterface::SCOPE_STORE
        );
        $isLightCheckoutEnabled = $this->scopeConfig->getValue(
            CheckoutConfigurationsProvider::XML_PATH_LIGHT_CHECKOUT_GENERAL_IS_ENABLED,
            ScopeInterface::SCOPE_STORE
        );

        if (('checkout' == $routePath || 'checkout/index' == $routePath || 'checkout/index/index' == $routePath)
            && $customUrl
            && $this->helper->isA(InstallData::MODULE_NAME)
            && $isLightCheckoutEnabled
            && $this->isEnableLightCheckoutForDevice->execute()
        ) {
            $routePath = $customUrl;
            return [$routePath];
        }
    }
}
