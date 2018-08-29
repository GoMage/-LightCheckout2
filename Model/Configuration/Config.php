<?php

namespace GoMage\LightCheckout\Model\Configuration;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    const XML_PATH_LIGHT_CHECKOUT_IS_ENABLED = 'light_checkout_configuration/general/is_enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isLightCheckoutEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_IS_ENABLED);
    }
}
