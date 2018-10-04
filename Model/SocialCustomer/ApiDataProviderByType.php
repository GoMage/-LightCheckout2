<?php

namespace GoMage\LightCheckout\Model\SocialCustomer;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ApiDataProviderByType
{
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

    /**
     * @param string $type
     *
     * @return array
     */
    public function get($type)
    {
        $data = [
            "enabled" => $this->getIsEnabledByType($type),
            "keys" => [
                'id' => $this->getAppIdByType($type),
                'key' => $this->getAppIdByType($type),
                'secret' => $this->getAppSecretByType($type),
            ]
        ];

        return array_merge($data, $this->getAdditionalConfigByType($type));
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function getIsEnabledByType($type)
    {
        return $this->scopeConfig->getValue('gomage_light_checkout_configuration/social_login_' . $type . '/enable');
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getAppIdByType($type)
    {
        return $this->scopeConfig->getValue('gomage_light_checkout_configuration/social_login_' . $type . '/app_id');
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getAppSecretByType($type)
    {
        return $this->scopeConfig->getValue('gomage_light_checkout_configuration/social_login_' . $type . '/app_secret');
    }

    /**
     * @param string $type
     *
     * @return array
     */
    private function getAdditionalConfigByType($type)
    {
        $config = [];

        $apiData = [
            'Facebook' => ['trustForwarded' => false, 'scope' => 'email, public_profile'],
            'Twitter' => ['include_email' => true],
            'Google' => [
                'scope' => 'https://www.googleapis.com/auth/plus.login'
                    . ' https://www.googleapis.com/auth/plus.me'
                    . ' https://www.googleapis.com/auth/plus.profile.emails.read'
            ]
        ];

        if (array_key_exists($type, $apiData)) {
            $config = $apiData[$type];
        }

        return $config;
    }
}
