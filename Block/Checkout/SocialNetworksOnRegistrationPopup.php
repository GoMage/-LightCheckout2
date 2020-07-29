<?php

namespace GoMage\LightCheckout\Block\Checkout;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\LightCheckout\Model\IsEnableLightCheckout;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;

class SocialNetworksOnRegistrationPopup extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var IsEnableLightCheckout
     */
    private $isEnableLightCheckout;

    /**
     * @param Context $context
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param IsEnableLightCheckout $isEnableLightCheckout
     * @param array $data
     */
    public function __construct(
        Context $context,
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        IsEnableLightCheckout $isEnableLightCheckout,
        array $data = []
    ) {

        parent::__construct($context, $data);

        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->urlBuilder = $context->getUrlBuilder();
        $this->isEnableLightCheckout = $isEnableLightCheckout;
    }

    /**
     * @return array
     */
    public function getSocialNetworksToDisplay()
    {
        $socialNetworks = [];
        if ($this->isEnableLightCheckout->execute()) {
            $isGoogleEnabled = (bool)$this->checkoutConfigurationsProvider->getIsSocialLoginGoogleEnabled();
            $isFacebookEnabled = (bool)$this->checkoutConfigurationsProvider->getIsSocialLoginFacebookEnabled();
            $isTwitterEnabled = (bool)$this->checkoutConfigurationsProvider->getIsSocialLoginTwitterEnabled();

            if ($isGoogleEnabled == true) {
                $url = $this->urlBuilder->getUrl(
                    'lightcheckout/social/login',
                    ['type' => 'Google']
                );
                $socialNetworks[] = [
                    'urlTo' => $url,
                    'class' => 'google-logo',
                ];
            }

            if ($isFacebookEnabled == true) {
                $url = $this->urlBuilder->getUrl(
                    'lightcheckout/social/login',
                    ['type' => 'Facebook']
                );
                $socialNetworks[] = [
                    'urlTo' => $url,
                    'class' => 'facebook-logo',
                ];
            }

            if ($isTwitterEnabled == true) {
                $url = $this->urlBuilder->getUrl(
                    'lightcheckout/social/login',
                    ['type' => 'Twitter']
                );
                $socialNetworks[] = [
                    'urlTo' => $url,
                    'class' => 'twitter-logo',
                ];
            }
        }

        return $socialNetworks;
    }
}
